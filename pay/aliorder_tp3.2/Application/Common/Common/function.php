<?php
/**
 * Created by PhpStorm.
 * User: Code
 * Date: 2017/6/27
 * Time: 10:08
 */
 
 
 function keep_array($content,$path = "aa.txt"){
    $fp = fopen($path,'a+');
    fwrite($fp,var_export($content,true));
    fclose($fp);
}



/**
 * 获取支付通道
 * @param string $payment
 * @return bool|\Think\Model
 */
function getGateWay($payment = "wechat"){
    $payconfig = D("Api/Payconfig");
    $result = $payconfig->where("config_key='%s'",$payment)->find();
    if(!$result){
        return false;
    }
    //获取支付密钥
    $gateway = D("Api/Gateway");
    $result_gateway = $gateway->where("way_id='%d'",$result["config_gateway"])->find();
    if($result_gateway){
        return $result_gateway;
    }else{
        return false;
    }
}
/**
 * 支付宝-面对面付款
 * @param string $out_trade_no 商户订单号
 * @param int $total_amount 订单金额
 * @param string $subject 订单标题
 * @param string $body 订单描述
 * @param string $notify_url 回执地址
 * @param string $timeout_express 交易超时时间
 * @return array array("status"=>"状态 1成功 0失败","msg"=>"消息内容","data"=>"成功生成支付二维码")
 */
function alipay_f2fpay($out_trade_no="",$total_amount=0,$subject="",$body="",$notify_url="",$timeout_express="5m"){
    include_once 'alipaySdk/f2fpay/model/builder/AlipayTradePrecreateContentBuilder.php';
    include_once 'alipaySdk/f2fpay/service/AlipayTradeService.php';
    // 创建请求builder，设置请求参数
    $qrPayRequestBuilder = new AlipayTradePrecreateContentBuilder();
    $qrPayRequestBuilder->setOutTradeNo($out_trade_no);
    $qrPayRequestBuilder->setTotalAmount($total_amount);
    $qrPayRequestBuilder->setTimeExpress($timeout_express);
    $qrPayRequestBuilder->setSubject($subject);
    $qrPayRequestBuilder->setBody($body);
    $qrPayRequestBuilder->setUndiscountableAmount(0);

    $alipay_config = C("ALIPAY_CONFIG");
    $config = array (
        'sign_type' => $alipay_config["SIGN_TYPE"],
        'charset' => $alipay_config["CHARSET"],
        'gatewayUrl' => $alipay_config["GATEWAYURL"],
        'notify_url' => empty($notify_url)?$alipay_config["NOTIFY_URL"]:$notify_url,
        'MaxQueryRetry' => $alipay_config["MAXQUERYRETRY"],
        'QueryDuration' => $alipay_config["QUERYDURATION"]
    );
    $return_data = array("status"=>1,"msg"=>"支付宝创建订单二维码成功","data"=>"");

    //获取密钥
    $gateway = getGateWay("alipay");
    if($gateway){
        $config['alipay_public_key'] = $gateway["way_pkey"];
        $config['merchant_private_key'] = $gateway["way_key"];
        $config['app_id'] = $gateway["way_appid"];
    }else{
        $return_data["status"] = 0;
        $return_data["msg"] = "支付宝通道获取失败,无法请求支付";
        return $return_data;
    }

    // 调用qrPay方法获取当面付应答
    $qrPay = new AlipayTradeService($config);
    $qrPayResult = $qrPay->qrPay($qrPayRequestBuilder);
    //	根据状态值进行业务处理
    switch ($qrPayResult->getTradeStatus()){
        case "SUCCESS":
            $response = $qrPayResult->getResponse();
            $return_data["data"] = $response->qr_code;
            break;
        case "FAILED":
            $return_data["status"] = 0;
            $return_data["msg"] = "支付宝创建订单二维码失败!!!";
            $return_data["data"] = $qrPayResult->getResponse();
            break;
        case "UNKNOWN":
            $return_data["status"] = 0;
            $return_data["msg"] = "系统异常，状态未知!!!";
            $return_data["data"] = $qrPayResult->getResponse();
            break;
        default:
            $return_data["status"] = 0;
            $return_data["msg"] = "不支持的返回状态，创建订单二维码返回异常!!!";
            break;
    }
    return $return_data;
}

/**
 * 支付宝交易查询
 * @param string $out_trade_no 商户订单号
 * @return array
 */
function alipay_query($out_trade_no=""){
    include_once 'alipaySdk/f2fpay/service/AlipayTradeService.php';
    // 创建请求builder，设置请求参数
    $queryContentBuilder = new AlipayTradeQueryContentBuilder();
    $queryContentBuilder->setOutTradeNo($out_trade_no);

    $alipay_config = C("ALIPAY_CONFIG");
    $config = array (
        'sign_type' => $alipay_config["SIGN_TYPE"],
        'charset' => $alipay_config["CHARSET"],
        'gatewayUrl' => $alipay_config["GATEWAYURL"],
        'MaxQueryRetry' => $alipay_config["MAXQUERYRETRY"],
        'QueryDuration' => $alipay_config["QUERYDURATION"]
    );
    $return_data = array("status"=>1,"msg"=>"ok","data"=>"");

    //获取密钥
    $gateway = getGateWay("alipay");
    if($gateway){
        $config['alipay_public_key'] = $gateway["way_pkey"];
        $config['merchant_private_key'] = $gateway["way_key"];
        $config['app_id'] = $gateway["way_appid"];
    }else{
        $return_data["status"] = 0;
        $return_data["msg"] = "支付宝通道获取失败,无法请求支付";
        return $return_data;
    }
    //初始化类对象，调用queryTradeResult方法获取查询应答
    $queryResponse = new AlipayTradeService($config);
    $queryResult = $queryResponse->queryTradeResult($queryContentBuilder);
    //	根据状态值进行业务处理
    switch ($queryResult->getTradeStatus()){
        case "SUCCESS":
            $return_data["data"] = json_decode(json_encode($queryResult->getResponse()),true);
            break;
        case "FAILED":
            $return_data["status"] = 0;
            $return_data["msg"] = "支付宝查询交易失败或者交易已关闭!!!";
            $return_data["data"] = json_decode(json_encode($queryResult->getResponse()),true);
            break;
        case "UNKNOWN":
            $return_data["status"] = 0;
            $return_data["msg"] = "系统异常，订单状态未知!!!";
            $return_data["data"] = json_decode(json_encode($queryResult->getResponse()),true);
            break;
        default:
            $return_data["status"] = 0;
            $return_data["msg"] = "不支持的查询状态，交易返回异常!!!";
            break;
    }
    return $return_data;
}
/**
 * 支付宝-WAP付款
 * @param string $out_trade_no 商户订单号
 * @param int $total_amount 订单金额
 * @param string $subject 订单标题
 * @param string $notify_url 回执地址
 * @param string $return_url 跳转地址
 * @param string $body 订单描述
 * @param string $timeout_express 交易超时时间
 */
function alipay_wappay($out_trade_no="",$total_amount=0,$subject="",$body="",$notify_url="",$return_url="",$timeout_express="1m"){
    include_once 'alipaySdk/wappay/service/AlipayTradeService.php';
    include_once 'alipaySdk/wappay/buildermodel/AlipayTradeWapPayContentBuilder.php';

    $payRequestBuilder = new AlipayTradeWapPayContentBuilder();
    $payRequestBuilder->setBody($body);
    $payRequestBuilder->setSubject($subject);
    $payRequestBuilder->setOutTradeNo($out_trade_no);
    $payRequestBuilder->setTotalAmount($total_amount);
    $payRequestBuilder->setTimeExpress($timeout_express);
    $alipay_config = C("ALIPAY_CONFIG");
    $config = array (
        'sign_type' => $alipay_config["SIGN_TYPE"],
        'charset' => $alipay_config["CHARSET"],
        'gatewayUrl' => $alipay_config["GATEWAYURL"],
        'notify_url' => empty($notify_url)?$alipay_config["NOTIFY_URL"]:$notify_url,
        'return_url' => empty($return_url)?$alipay_config["RETURN_URL"]:$return_url,
        'MaxQueryRetry' => $alipay_config["MAXQUERYRETRY"],
        'QueryDuration' => $alipay_config["QUERYDURATION"]
    );

    //获取密钥
    $gateway = getGateWay("alipay");
    if($gateway){
        $config['alipay_public_key'] = $gateway["way_pkey"];
        $config['merchant_private_key'] = $gateway["way_key"];
        $config['app_id'] = $gateway["way_appid"];
    }else{
        $return_data["return_code"] = "FAIL";
        $return_data["return_msg"] = "支付宝通道获取失败,无法请求支付";
        return $return_data;
    }

    $payResponse = new AlipayTradeService($config);
    $result = $payResponse->wapPay($payRequestBuilder,$config['return_url'],$config['notify_url']);
    return $result;
}

/**
 * 支付宝PC支付
 * @param string $out_trade_no
 * @param int $total_amount
 * @param string $subject
 * @param string $body
 * @param string $notify_url 回执地址
 * @param string $return_url 跳转地址
 * @param string $timeout_express
 */
function alipay_pcpay($out_trade_no="",$total_amount=0,$subject="",$body="",$notify_url="",$return_url="",$timeout_express="1m"){
    include_once 'alipaySdk/pagepay/buildermodel/AlipayTradePagePayContentBuilder.php';
    include_once 'alipaySdk/pagepay/service/AlipayTradeService.php';

    $payRequestBuilder = new AlipayTradePagePayContentBuilder();
    $payRequestBuilder->setBody($body);
    $payRequestBuilder->setSubject($subject);
    $payRequestBuilder->setTotalAmount($total_amount);
    $payRequestBuilder->setOutTradeNo($out_trade_no);

    $alipay_config = C("ALIPAY_CONFIG");
    $config = array (
        'sign_type' => $alipay_config["SIGN_TYPE"],
        'charset' => $alipay_config["CHARSET"],
        'gatewayUrl' => $alipay_config["GATEWAYURL"],
        'notify_url' => empty($notify_url)?$alipay_config["NOTIFY_URL"]:$notify_url,
        'return_url' => empty($return_url)?$alipay_config["RETURN_URL"]:$return_url,
        'MaxQueryRetry' => $alipay_config["MAXQUERYRETRY"],
        'QueryDuration' => $alipay_config["QUERYDURATION"]
    );
    //获取密钥
    $gateway = getGateWay("alipay");
    if($gateway){
        $config['alipay_public_key'] = $gateway["way_pkey"];
        $config['merchant_private_key'] = $gateway["way_key"];
        $config['app_id'] = $gateway["way_appid"];
    }else{
        $return_data["return_code"] = "FAIL";
        $return_data["return_msg"] = "支付宝通道获取失败,无法请求支付";
        return $return_data;
    }
    $payResponse = new AlipayTradeService($config);
    $result = $payResponse->pagePay($payRequestBuilder,$config['return_url'],$config['notify_url']);
    return $result;
}
/**
 * 微信扫描支付
 * @param string $out_trade_no
 * @param int $total_amount
 * @param string $subject
 * @param string $body
 * @param string $notify_url 回执地址
 * @return array
 */
function wechatNativePay($out_trade_no="",$total_amount=0,$subject="",$body="",$notify_url=""){
    require_once "wechatSdk/lib/WxPay.Api.php";
    require_once "wechatSdk/WxPay.NativePay.php";
    $notify = new NativePay();
    $wechat_config = C("WECHAT_CONFIG");
    $input = new WxPayUnifiedOrder();
    $return_data = array("status"=>1,"msg"=>"ok","data"=>"");
    //获取密钥
    $gateway = getGateWay("wechat");
    if($gateway){
        $way_mchid = $gateway["way_mchid"];
        $way_key = $gateway["way_key"];
        $way_appid = $gateway["way_appid"];
    }else{
        $return_data["status"] = "0";
        $return_data["msg"] = "微信通道获取失败,无法请求支付";
        return $return_data;
    }
    //配置支付密钥
    $input->SetAppid($way_appid);
    $input->SetMch_id($way_mchid);
    $input->SetAppKey($way_key);
    $input->SetBody($body);
    $input->SetOut_trade_no($out_trade_no);
    $input->SetTotal_fee($total_amount);
    $input->SetTime_start(date("YmdHis",time()));
    $input->SetTime_expire(date("YmdHis", time() + 600));
    $notify_url=empty($notify_url)?$wechat_config["NOTIFY_URL"]:$notify_url;
    $input->SetNotify_url($notify_url);
    $input->SetTrade_type("NATIVE");
    $input->SetProduct_id("20170627205227");
    $result = $notify->GetPayUrl($input);
    if($result["return_code"]=="SUCCESS"&&$result["return_code"]=="SUCCESS"){
        $return_data["data"] = $result["code_url"];
    }else{
        $return_data["status"] = 0;
        $return_data["msg"] = $result["err_code_des"]?$result["err_code_des"]:$result["return_msg"];
    }
    return $return_data;
}

/**
 * 微信订单查询
 * @param string $out_trade_no
 * @return 成功时返回
 */
function wechatQuery($out_trade_no=""){
    require_once "wechatSdk/lib/WxPay.Api.php";
    $input = new WxPayOrderQuery();
    //获取密钥
    $gateway = getGateWay("alipay");
    if($gateway){
        $way_mchid = $gateway["way_mchid"];
        $way_key = $gateway["way_key"];
        $way_appid = $gateway["way_appid"];
    }else{
        $return_data["return_code"] = "FAIL";
        $return_data["return_msg"] = "微信通道获取失败,无法请求支付";
        return $return_data;
    }
    //配置支付密钥
    $input->SetAppid($way_appid);
    $input->SetMch_id($way_mchid);
    $input->SetAppKey($way_key);
    $input->SetOut_trade_no($out_trade_no);
    return WxPayApi::orderQuery($input);
}
/**
 * 微信支付回掉
 * @return array
 */
function wechatNotify(){
    require_once "wechatSdk/notify.php";
    $notify = new PayNotifyCallBack();
    //获取密钥
    $gateway = getGateWay("wechat");
    if($gateway){
        $notify->appmchid = $gateway["way_mchid"];
        $notify->appkey = $gateway["way_key"];
        $notify->appid = $gateway["way_appid"];
    }else{
        $return_data["return_code"] = "FAIL";
        $return_data["return_msg"] = "微信通道获取失败,无法请求支付";
        return $return_data;
    }

    $notify->Handle(false,$gateway["way_key"]);
    return $notify->NotifyData();
}

/**
 * 支付宝回执通知
 * @param array $data
 * @return bool
 */
function alipayNotify($data=array()){
    require_once "alipaySdk/pagepay/service/AlipayTradeService.php";
    $alipay_config = C("ALIPAY_CONFIG");
    $config = array (
        'sign_type' => $alipay_config["SIGN_TYPE"],
        'charset' => $alipay_config["CHARSET"],
        'gatewayUrl' => $alipay_config["GATEWAYURL"],
        'notify_url' => $alipay_config["NOTIFY_URL"],
        'return_url' => $alipay_config["RETURN_URL"],
        'MaxQueryRetry' => $alipay_config["MAXQUERYRETRY"],
        'QueryDuration' => $alipay_config["QUERYDURATION"]
    );
    //获取密钥
    $gateway = getGateWay("alipay");
    if($gateway){
        $config['alipay_public_key'] = $gateway["way_pkey"];
        $config['merchant_private_key'] = $gateway["way_key"];
        $config['app_id'] = $gateway["way_appid"];
    }else{
        $return_data["return_code"] = "FAIL";
        $return_data["return_msg"] = "支付宝通道获取失败,无法请求支付";
        return $return_data;
    }
    $alipaySevice = new AlipayTradeService($config);
    $result = $alipaySevice->check($data);
    return $result;
}
/**
 * 下发异步通知
 * @param string $url
 * @param array $param
 * @return bool
 */
function sendNotify($url="",$param=array()){
    if(strlen($url)<1){
        return false;
    }
    $result = SkillCurl($url,$param);
    return $result;
}
/**
 * 系统签名验证
 * @param array $param
 * @param string $sign
 * @param string $secret
 * @return bool
 */
function checkSign($param=array(),$sign="",$secret = ""){
    unset($param["sign"]);
    $secret = empty($secret)?C('PAY_REQ_KEY'):$secret;
    //签名步骤一：按字典序排序参数
    ksort($param);
    $string = ToUrlParams($param);
    //签名步骤二：在string后加入KEY
    $string = $string ."&key=".$secret;
    //签名步骤三：MD5加密
    $string = md5($string);
    //签名步骤四：所有字符转为大写
    $result = strtoupper($string);
    if(md5($result)!=md5($sign)){
        return false;
    }else{
        return true;
    }
}

/**
 * 生成签名
 * @param array $param
 * @param string $secret
 * @return string
 */
function createSign($param=array(),$secret = ""){
    unset($param["sign"]);
    $secret = empty($secret)?C('PAY_REQ_KEY'):$secret;
    //签名步骤一：按字典序排序参数
    ksort($param);
    $string = ToUrlParams($param);
    //签名步骤二：在string后加入KEY
    $string = $string ."&key=".$secret;
    //签名步骤三：MD5加密
    $string = md5($string);
    //签名步骤四：所有字符转为大写
    $result = strtoupper($string);
    return $result;
}
/**
 * 格式化参数
 */
function ToUrlParams($data=array()){
    $buff = "";
    foreach ($data as $k => $v){
        if($k != "sign"){
            $buff .= trim($k) . "=" . trim($v) . "&";
        }
    }

    $buff = trim($buff, "&");
    return $buff;
}

/**
 * 订单编号标识
 * @param string $trade_type
 * @return mixed
 */
function orderNumberSign($trade_type=""){
    $sign = array("ALIPAYWAP"=>"AW","ALIPAYPC"=>"AP","ALIPAYF2F"=>"AF","WECHATNATIVE"=>"WN");
    return $sign[$trade_type];
}
function orderPayType($trade_type=""){
    $sign = array("ALIPAYPC"=>2,"WECHATNATIVE"=>1,"ALIPAYWAP"=>3);
    return $sign[$trade_type];
}
// function orderPayMent($trade_type=""){
//     $sign = array("ALIPAYWAP"=>"支付宝手机网站支付","ALIPAYPC"=>"支付宝电脑网站支付","ALIPAYF2F"=>"支付宝面当面支付","WECHATNATIVE"=>"微信扫码支付",);
//     return $sign[$trade_type];
// }
/**
 * 判读是否是手机浏览器打开
 * @return bool
 */
function isMobileRequest() {
    $mobile = array();
    static $mobilebrowser_list ='Mobile|iPhone|Android|WAP|NetFront|JAVA|OperasMini|UCWEB|WindowssCE|Symbian|Series|webOS|SonyEricsson|Sony|BlackBerry|Cellphone|dopod|Nokia|samsung|PalmSource|Xphone|Xda|Smartphone|PIEPlus|MEIZU|MIDP|CLDC';
    //note 获取手机浏览器
    if(preg_match("/$mobilebrowser_list/i", $_SERVER['HTTP_USER_AGENT'], $mobile)) {
        return true;
    }else{
        if(preg_match('/(mozilla|chrome|safari|opera|m3gate|winwap|openwave)/i', $_SERVER['HTTP_USER_AGENT'])) {
            return false;
        }else{
            return true;
        }
    }
}
/**
 * http 协议post请求
 * @param $url 请求链接
 * @param array $param 参数
 * @param array $secret 密钥
 * @return mixed
 * @throws Exception
 */
function SkillCurl($url, $param=array(),$secret = ""){
    $secret = empty($secret)?C('PAY_REQ_KEY'):$secret;
    //签名步骤一：按字典序排序参数
    ksort($param);
    $string = ToUrlParams($param);
    //签名步骤二：在string后加入KEY
    $string = $string ."&key=".$secret;
    //签名步骤三：MD5加密
    $string = md5($string);
    //签名步骤四：所有字符转为大写
    $result = strtoupper($string);
    $param['sign']=$result;

    if(!is_array($param)){
        throw new Exception("参数必须为array");
    }
    $httph =curl_init($url);
    curl_setopt($httph, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($httph, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($httph,CURLOPT_RETURNTRANSFER,1);
    curl_setopt($httph, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.0)");
    curl_setopt($httph, CURLOPT_POST, 1);//设置为POST方式
    curl_setopt($httph, CURLOPT_POSTFIELDS, $param);
    curl_setopt($httph, CURLOPT_RETURNTRANSFER,1);
    curl_setopt($httph, CURLOPT_HEADER,0);
    $rst=curl_exec($httph);
    curl_close($httph);
    if($rst){
        ob_clean();
        return $rst;
    }else{
        return array("status"=>"-1","msg"=>"服务器请求失败","data"=>"");
    }
}
/**
 * http 协议post请求（不带签名加密的）
 * @param $url 请求链接
 * @param array $param 参数
 * @return mixed
 * @throws Exception
 */
function postCurl($url, $param=array()){
    $httph =curl_init($url);
    curl_setopt($httph, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($httph, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($httph,CURLOPT_RETURNTRANSFER,1);
    curl_setopt($httph, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.0)");
    curl_setopt($httph, CURLOPT_POST, 1);//设置为POST方式
    curl_setopt($httph, CURLOPT_POSTFIELDS, $param);
    curl_setopt($httph, CURLOPT_RETURNTRANSFER,1);
    curl_setopt($httph, CURLOPT_HEADER,0);
    $rst=curl_exec($httph);
    curl_close($httph);
    if($rst){
        ob_clean();
        return $rst;
    }else{
        return array("status"=>"-1","msg"=>"服务器请求失败","data"=>"");
    }
}
function getCurl($url){
    ini_set('max_execution_time', '100');
    $ch = curl_init();
    //设置选项，包括URL
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    //执行并获取HTML文档内容
    $output = curl_exec($ch);
    //释放curl句柄
    curl_close($ch);
    //打印获得的数据
    return json_decode($output,true);

}
/**
 * 生成支付二维码
 * @param string $data 要生成二维码的网址或者文字
 * @param string $file_path 生成二维码后的图片保存地址（包含文件名和后缀）
 */
function qrCode($data="",$file_path=""){
    Vendor('phpqrcode.phpqrcode');
    //生成二维码图片
    $object = new \QRcode();
    $url=$data;//网址或者是文本内容
    $level=3;
    $size=10;
    $errorCorrectionLevel =intval($level) ;//容错级别
    $matrixPointSize = intval($size);//生成图片大小
//    $file_path = RUNTIME_PATH."Qrcode/".$title.".png";
    $object::png($url, $file_path, $errorCorrectionLevel, $matrixPointSize, 2);
    return ltrim($file_path,".");
}
/**
 * 二维码解码
 * @param string $file_path 要解码的二维码图片地址
 */
function qrDecode($file_path=""){
    Vendor('Zxing.QrReader');
    $qrcode = new \QrReader($file_path);
    $text = $qrcode->text();
    return $text;
}

/**
 * 裁切图片
 * @param string $file_path 要裁切的图片地址
 * @param string $save 裁切后的图片保存地址（包括图片名称、后缀）
 * @param  integer $w      裁剪区域宽度
 * @param  integer $h      裁剪区域高度
 * @param  integer $x      裁剪区域x坐标
 * @param  integer $y      裁剪区域y坐标
 * @param  integer $width  图片保存宽度
 * @param  integer $height 图片保存高度
 * @return Object          当前图片处理库对象
 */
function cropImg($file_path,$save,$w=590, $h=590, $x = 245, $y = 415, $width = 300, $height = 300){
    $image = new \Think\Image();// 实例化图片处理类
    $open = $image->open($file_path);
    return $image->crop($w,$h,$x,$y,$width,$height)->save($save);;
}
/**
* 生成缩略图
* @param string $file_path 要缩略的图片地址
* @param string $save 缩略后的图片保存地址（包括图片名称、后缀）
* @param  integer $width  缩略图最大宽度
* @param  integer $height 缩略图最大高度
* @param  integer $type   缩略图裁剪类型
* @return Object          当前图片处理库对象
*/
function thumbImg($file_path,$save,$width=400,$height=400){
    $image = new \Think\Image();// 实例化图片处理类
    $open = $image->open($file_path);
    $image->thumb($width, $height)->save($save);
}
/*删除某个文件夹下的文件*/
function delFileUnderDir($dirName)
{
    if ( $handle = opendir( "$dirName" ) ) {
        while ( false !== ( $item = readdir( $handle ) ) ) {
            if ( $item != "." && $item != ".." ) {
                if ( is_dir( "$dirName/$item" ) ) {
                    delFileUnderDir( "$dirName/$item" );
                } else {
                    @unlink( "$dirName/$item" );
                }
            }
        }
        closedir( $handle );
    }
}

//异步请求

        
function doRequest($url, $param=array()){ 
    
  $urlinfo = parse_url($url); 
    
  $host = $urlinfo['host']; 
  $path = $urlinfo['path']; 
  $query = isset($param)? http_build_query($param) : ''; 
    
  $port = 80; 
  $errno = 0; 
  $errstr = ''; 
  $timeout = 10; 
    
  $fp = fsockopen($host, $port, $errno, $errstr, $timeout); 
    
  $out = "POST ".$path." HTTP/1.1\r\n"; 
  $out .= "host:".$host."\r\n"; 
  $out .= "content-length:".strlen($query)."\r\n"; 
  $out .= "content-type:application/x-www-form-urlencoded\r\n"; 
  $out .= "connection:close\r\n\r\n"; 
  $out .= $query; 
    
  fputs($fp, $out); 
  fclose($fp); 
}
