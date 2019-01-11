<?php
// echo md5('123456');die;

require_once 'inc.php';

$remark = $_GET['remark'];
$wg = $_GET['wg'];

if($wg == 'alipay' || $wg =='alipaywap'){
	$pay_code="22";
}else{
	$pay_code="0";
	
	echo "通道代码有错".$wg;
}

$newArr = array();
$data = [
    "orderAmount"=>$_GET['price'], //金额
    "orderId"=> $_GET['orderid'],//订单号
    "partner"=>"120180630000770000000579071661", //商户号
    'payMethod'=>$pay_code,
    "payType"=>"syt",
    "signType"=>"MD5",
    "version"=>"1.0",
];

$key = $userkey; //key 
ksort($data);
$postString = http_build_query($data);
$signMyself = strtoupper(md5($postString.$key));
$data["sign"] = $signMyself;

$data['productName'] = $_GET['remark'];
$data['productId'] = '9677';
$data['productDesc'] = $_GET['remark'];
$data['notifyUrl'] = 'http://'.$_SERVER['HTTP_HOST'].'/pay/sytpay/notify_url.php';
$postString = http_build_query($data);
$url = "http://qr.sytpay.cn/api/v1/create.php?".$postString."";
header("Location: " .$url);