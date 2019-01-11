<?php
require_once 'inc.php';
$customerid=$userid;
$orderid=$_REQUEST['orderid'];
$total_fee=$_REQUEST['price'];
$paytype=GetBankCode($_REQUEST['bankcode']);


$url='https://c.heepay.com/quick/pc/index.do';
$merchantId='103877';
$key='580c5e8d2a770ba3bfc5fc0861a242fc';
$merchantOrderNo=$orderid;
$merchantUserId=rand(1,999999);
$productCode='HY_B2CEBANKPC';
$payAmount=$total_fee;
$requestTime=date("YmdHis",time());
$version='1.0';
$notifyUrl='http://'.$_SERVER['HTTP_HOST'].'/pay/103877/notify.php';
$callBackUrl='http://'.$_SERVER['HTTP_HOST'].'/pay/103877/return.php';
$description='pay';
$sign=md5("merchantId=$merchantId&merchantOrderNo=$merchantOrderNo&merchantUserId=$merchantUserId&notifyUrl=$notifyUrl&payAmount=$payAmount&productCode=$productCode&version=$version&key=$key");
$onlineType='simple';
$bankId=$paytype[0];
$bankCardType=$paytype[1];


?>

<html>
<head>
<title>pay to bank</title>
</head>
<body onload="document.pay.submit();">
<form name="pay" action="<?php echo $url ?>" method="post">
<input type="hidden" name="merchantId" value="<?php echo $merchantId ?>">
<input type="hidden" name="merchantOrderNo" value="<?php echo $merchantOrderNo ?>">
<input type="hidden" name="merchantUserId" value="<?php echo $merchantUserId ?>">
<input type="hidden" name="productCode" value="<?php echo $productCode ?>">
<input type="hidden" name="payAmount" value="<?php echo $payAmount ?>">
<input type="hidden" name="requestTime" value="<?php echo $requestTime ?>">
<input type="hidden" name="version" value="<?php echo $version ?>">
<input type="hidden" name="notifyUrl" value="<?php echo $notifyUrl ?>">
<input type="hidden" name="callBackUrl" value="<?php echo $callBackUrl ?>">
<input type="hidden" name="description" value="<?php echo $description ?>">
<input type="hidden" name="sign" value="<?php echo $sign ?>">
<input type="hidden" name="onlineType" value="<?php echo $onlineType ?>">
<input type="hidden" name="bankId" value="<?php echo $bankId ?>">
<input type="hidden" name="bankCardType" value="<?php echo $bankCardType ?>">
</form>
</body>
</html>