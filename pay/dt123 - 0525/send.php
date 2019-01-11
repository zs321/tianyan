<?php
require_once 'inc.php';
$version='1.0';
$customerid=$userid;
$orderid=$_REQUEST['orderid'];

$total_fee=(int)$_REQUEST['price']*100;

function clearxss($tempurl)
{
	if($tempurl)
	{
		$tempurl=str_replace("<","",$tempurl);
		$tempurl=str_replace(">","",$tempurl);
		$tempurl=str_replace("\"","",$tempurl);
		$tempurl=str_replace("\'","",$tempurl);
		$tempurl=str_replace(";","",$tempurl);
		$tempurl=str_replace("(","",$tempurl);
		$tempurl=str_replace(")","",$tempurl);
		$tempurl=str_replace(" ","",$tempurl);
	}
	return 	$tempurl;
}


$paytype=clearxss($_REQUEST['bankcode']);

if($paytype=='CCB'){
	$type='1004';
}elseif($paytype=='ABC'){
	$type='1004';
}elseif($paytype=='ICBC'){
	$type='1001';
}elseif($paytype=='CEB'){
	$type='1008';
}elseif($paytype=='PSBC'){
	$type='1006';
}elseif($paytype=='BCCB'){
	$type='1016';
}elseif($paytype=='SHBANK'){
	$type='1025';
}else{
	echo '不支持该银行';die;	
}

	$version='1.0';
	$spid='1526431974967'; 
	$spbillno=$orderid;
	$tranAmt=$total_fee;
	$cardType='1';
	$channel='1';
	$userType='1';
	$bankSegment=$type;
	$backUrl='http://'.$_SERVER['HTTP_HOST'].'/pay/dt123/notify.php';
	$notifyUrl='http://'.$_SERVER['HTTP_HOST'].'/pay/dt123/notify.php';
	$productName='pay';
	$productDesc='pay';
	$key='9031f6c60a424168b52f0464c4014f59';
	$url='http://yjpay.py6627.cn/pay/gatewayPay';
	$str="backUrl=$backUrl&bankSegment=$bankSegment&cardType=$cardType&channel=$channel&notifyUrl=$notifyUrl&productDesc=$productDesc&productName=$productName&spbillno=$spbillno&spid=$spid&tranAmt=$tranAmt&userType=$userType&version=$version&key=$key";
	$sign=strtoupper(md5($str));

?>

<html>
<head>
<title>pay</title>
</head>
<body onload="document.pay.submit();">
<form name="pay" action="<?php echo $url ?>" method="post">
<input type="hidden" name="req_data" value="
	<xml>
		<version><?php echo $version; ?></version>
		<spid><?php echo $spid; ?></spid>
		<spbillno><?php echo $spbillno; ?></spbillno>
		<tranAmt><?php echo $tranAmt; ?></tranAmt>
		<cardType><?php echo $cardType; ?></cardType>
		<channel><?php echo $channel; ?></channel>
		<userType><?php echo $userType; ?></userType>
		<bankSegment><?php echo $bankSegment; ?></bankSegment>
		<backUrl><?php echo $backUrl; ?></backUrl>
		<notifyUrl><?php echo $notifyUrl; ?></notifyUrl>
		<productName><?php echo $productName; ?></productName>
		<productDesc><?php echo $productDesc; ?></productDesc>
		<sign><?php echo $sign; ?></sign>
	</xml>
"/>
</form>
</body>
</html>
