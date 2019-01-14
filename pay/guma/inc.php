<?php
require_once '../../app/init.php';
use WY\app\woodyapp;
use WY\app\model\Payacp;

$app=woodyapp::getInstance();
$acp=new Payacp();
$acpData=$acp->get('guma');
extract($acpData);

function checkSign($param=array(),$sign="",$secret = ""){
    unset($param["sign"]);
    $secret = empty($secret)?$userkey:$secret;
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
    $secret = empty($secret)? "" :$secret;
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
    $secret = empty($secret)?"":$secret;
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
        return json_decode($rst,true);
    }else{
        return array("return_code"=>"FALL","return_msg"=>"服务器请求失败");
    }
}

/**
 * 生成支付二维码
 * @param string $data
 * @param string $title
 */
function qrCode($data="",$title=""){
    require_once 'phpqrcode.php';
    //生成二维码图片
    $object = new \QRcode();
    $url=$data;//网址或者是文本内容
    $level=3;
    $size=10;
    $errorCorrectionLevel =intval($level) ;//容错级别
    $matrixPointSize = intval($size);//生成图片大小
    $file_path = "Qrcode/".$title.".png";
//    $file_path = RUNTIME_PATH."Qrcode/".$title.".png";
    $object->png($url, $file_path, $errorCorrectionLevel, $matrixPointSize, 2);
    return ltrim($file_path,".");
}

function Post($url,$post_data){
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_HEADER, 0);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_POST, 1);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $post_data);
    $data = curl_exec($curl);
    curl_close($curl);
    return $data;
}

function httpGet($url,$get_data){
    foreach ($get_data as $k => $v) {
        $data[] = $k.'='.$v;
    }
    $p_str = implode('&', $data);
    $url .= '?'.$p_str;
//    dump($url,1,1);
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
//    curl_setopt($ch, CURLOPT_HEADER, 1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    $output = curl_exec($ch);
    curl_close($ch);

    return $output;
}



?>
