<?php

include_once 'rsa.lib.php';
$cwd = realpath(dirname(__FILE__));
$private_key = $cwd.DIRECTORY_SEPARATOR.'privateKey.pem'; // 私钥路径
$public_key = $cwd.DIRECTORY_SEPARATOR.'publicKey.pem'; // 公钥路径
$rsa = new Rsa($private_key, $public_key);


	if($_GET['bankcode']=='ICBC') $bankcode="ICBC";       //工商银行
	if($_GET['bankcode']=='ABC')	$bankcode="ABC";	  //农业银行
	if($_GET['bankcode']=='BOC') $bankcode="BOC";		  //中国银行
	if($_GET['bankcode']=='CCB') $bankcode="CCB";		  //建设银行
	if($_GET['bankcode']=='CMB') $bankcode="CMB";		  //招商银行
	if($_GET['bankcode']=='CMBC') $bankcode="CMBC";		  //民生银行
	if($_GET['bankcode']=='PINGANBANK') $bankcode="SPAB"; //平安银行
	if($_GET['bankcode']=='PSBS') $bankcode="PSBC";		  //中国邮政储蓄银行
	if($_GET['bankcode']=='SHB') $bankcode="BOSC";	  		//上海银行
	if($_GET['bankcode']=='CIB') $bankcode="CIB";       	//兴业银行


//var_dump($rsa);die;
$merchant_code = '10096'; //商户编号
$appno_no = '10'; //应用编号(APP ID)
$orderId = $_GET['orderid']; //商户订单号(每次请求不能重复)
$data = array(
    'merchantno' => $merchant_code,
    'appno' => $appno_no,
    'merchantorder' => $orderId,
    'bankcode' => $bankcode,
    'amount' => $_GET['price'],
    'paytype' => 'wangguan',  //快捷为：kuaijie
    'customerno' => 'Neo1314',
    'notifyurl' => 'http://'.$_SERVER['HTTP_HOST'].'/pay/jsbbank/notifyurl.php',
	'returnurl' => 'http://'.$_SERVER['HTTP_HOST'].'/pay/jsbbank/returnurl.php',
    'itemname' => 'test-php-demo',  //必须英文,此字段不能是中文
    'itemno' => 'product-123'
);
$json = json_encode($data);
$sign = $rsa->getSignature($json);
//var_dump($sign);
$post = array(
    'transdata' => urlencode($json),
    'sign' => urlencode($sign),
	'signtype' => 'RSA'
);

//请求接口
$api = 'http://www.runhejr.com/paycore/transfer/pretransfer';
$curl = curl_init();
curl_setopt($curl, CURLOPT_URL, $api);
curl_setopt($curl, CURLOPT_HTTPHEADER, array("Content-Type: application/json; charset=utf-8"));
curl_setopt($curl, CURLOPT_POST, 1);
curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($post));
curl_setopt($curl, CURLOPT_TIMEOUT, 300);
curl_setopt($curl, CURLOPT_HEADER, 0);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
$response = '';
$response = curl_exec($curl);
$curl_errno = curl_errno($curl);
$curl_error = curl_error($curl);
curl_close($curl);
if ($curl_errno > 0) {
    echo '连接失败：'.$curl_error;
}else{
	$postUrl="http:".str_replace('(toPay)','("toPay")',str_replace('\"','',(trim(trim($response,'{"callPaymentStatus":true,"html":"","message":"待支付","payUrl":"'),'\r\n"}"'))));
		header ("location:$postUrl");
}