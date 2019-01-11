<?php
header("content-type:application/json; charset=utf-8");
require_once 'inc.php';

$sdorderno=$_REQUEST['orderid'];
$total_fee=$_REQUEST['price'];

$notify_url='http://'.$_SERVER['HTTP_HOST'].'/pay/smalipay/notify.php';//异步通知地址
$return_url='http://'.$_SERVER['HTTP_HOST'].'/pay/smalipay/return.php';//异步通知地址


$code="fcfec15e061232307585e67ad84b57d4";//自己的密钥

$merchantId='9096';
$totalAmount=$total_fee;
$desc='pay';
$corp_flow_no=$sdorderno;
$notify_url=$notify_url;
$return_url=$return_url;
$type='2';

//签名加密
$sign=MD5($merchantId."pay".$totalAmount.$corp_flow_no.$code);


header('Location: http://www.muipay.com/api/Payonline/order_pay/?merchantId='.$merchantId."&type=".$type.'&totalAmount='.$totalAmount.'&sign='.$sign.'&desc='.$desc.'&corp_flow_no='.$corp_flow_no.'&notify_url='.$notify_url.'&return_url='.$return_url);

 
?>
