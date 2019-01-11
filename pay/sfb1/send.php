<?php
require_once 'inc.php';
include_once './sdk/acp_service.php';

$orderid = $_GET['orderid'];
$money = $_GET['price']*100;

switch ($_GET['bankcode']) {
	case 'weixin':
		$txnSubType = '02';
		$productCode = '0202';
        $pt = 'pc';
        $pay_type = '微信';
        $img1 = 'wx_txt.png';
		$img2 = 'wx.jpg';
		break;
    case 'alipay':
	case 'alipaywap':
		$txnSubType = '03';
		$productCode = '0203';
		$pt = 'pc';
        $pay_type = '支付宝';
        $img1 = 'ali_txt.png';
        $img2 = 'ali.jpg';
		break;
	
}
//请求参数封装
$params = array(
    //以下信息非特殊情况不需要改动 
    'version' => '1.0', //版本号
    'signMethod' => '01', //签名方法
    'txnType' => '02', //交易类型
    'txnSubType' => $txnSubType, //接口类型
    "productCode"=> $productCode, //产品编码
     //以下信息需根据实际参数进行配置
    'merId' => $userid,//开通商户后 分配的商户号
    'merchantNum' => $userkey,//开通商户后 分配的商户编码
    'backUrl' => 'http://'.$_SERVER['HTTP_HOST'].'/pay/sfb1/notify_url.php',//交易成功后的通知地址
    'orderId' => $orderid, //订单编号
    'txnAmt' => $money, //交易金额 单位分
    'txnTime' => date('YmdHis'), //订单发送时间
    // 'reqReserved' => "my order",   //商户自保留字段
);
AcpService::sign($params); // 签名
$url = SDK_BACK_TRANS_URL; 

$result_arr = AcpService::post($params, $url);
if (count($result_arr) <= 0) {
    //没收到200应答的情况
    echo ('没收到200应答<br/>');
    return;
}

// echo ('应答数据:' . json_encode($result_arr));
if (!AcpService::validate($result_arr)) {
    echo ('应答报文验签失败');
    return;
}
$code = $result_arr["respCode"];
if ($code == "66") {
	$file = qrCode($result_arr['payInfo'],$result_arr['orderId']);
	if ($_GET['bankcode']=='alipaywap') {
        header("location:".$result_arr['payInfo']);
    }else{?>
    <!DOCTYPE html>
    <html lang="zh"><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        
    <title>云付码|收银台</title>
        <script src="smpayimg/hm.js"></script><script type="text/javascript">
            var base_url = "";
        </script>
    <link href="smpayimg/common.css" rel="stylesheet">
    <link href="smpayimg/index.css" rel="stylesheet">
    <script type="text/javascript" src="smpayimg/jquery-1.11.1.js"></script>

    </head>
    <body class="wx-pay" rlt="1">
        <div class="head">
            <div class="main">
                <div class="logo-area">
                    <h1>云付码</h1>
                    <h2>收银台</h2>
                </div>
            </div>
        </div>
        <div class="main">
            <div class="pay-content">
                <p>
                    <strong>请您及时付款,以便订单尽快处理！</strong>请您在提交订单后<span>24小时</span>内完成支付，否则订单会自动取消。
                </p>
                <p class="order-id">支付金额：<span id="PaysumOfMoney"><?php echo number_format($money/100,2);?></span> 元</p>
                <ul class="payInfor">
                    <li id="PaymerchName"><strong>收款方</strong><em>：</em>云码付</li>
                    <li id="PayOrderId">订单编号：<?php echo $orderid;?></li>
                </ul>
                <div class="wx-pay-cont">
                    <div class="wx-ecode fl">
                        <img src="smpayimg/<?php echo $img1;?>" alt="请使用<?php echo $pay_type;?>扫一扫扫描二维码支付">
                        <p>
                            <img id="wxerweima" src="<?php echo $file;?>" alt="">二维码有效时长为2小时，请尽快支付
                        </p>
                    </div>
                    <img class="pic fr" src="smpayimg/<?php echo $img2;?>">
                </div>
            </div>
        </div>
        
        <div class="footer">© 云付码</div>
        
        <script src="smpayimg/index.js"></script>

    </body></html>
    <?php  
    }
} else {
    echo $result_arr["respMsg"];
}
/**
 * 生成支付二维码
 * @param string $data
 * @param string $title
 */
function qrCode($data="",$title=""){
    require_once './phpqrcode/phpqrcode.php';
    //生成二维码图片
    $object = new \QRcode();
    $url=$data;
    $level=3;
    $size=10;
    $errorCorrectionLevel =intval($level) ;//容错级别
    $matrixPointSize = intval($size);//生成图片大小
    $file_path = './qrcode/'.$title.".png";
    $qrcode_path = 'http://'.$_SERVER['HTTP_HOST'].'/pay/sfb1/qrcode/'.$title.".png";
    $object->png($url, $file_path, $errorCorrectionLevel, $matrixPointSize, 2);
    return $qrcode_path;
}

?>
