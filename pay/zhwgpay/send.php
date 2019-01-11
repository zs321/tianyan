<?php
	require_once 'inc.php';

	$merchno='333370172300001';
	$amount=$_GET['price'];
	$traceno=$_GET['orderid'];
	$channel='2';
	$bankCode=GetBankCode($_GET['bankcode']);
	$settleType='2';
	$notifyUrl="http://".$_SERVER['HTTP_HOST']."/pay/zhwgpay/notifyUrl.php";
	$returnUrl="http://".$_SERVER['HTTP_HOST']."/pay/zhwgpay/returnUrl.php";
	$key='DC966D183DA5D746448D73C696CBA45E';
	$str="amount=$amount&bankCode=$bankCode&channel=$channel&merchno=$merchno&notifyUrl=$notifyUrl&returnUrl=$returnUrl&settleType=$settleType&traceno=$traceno&$key";
	$signature=md5($str);
	$url="http://api.gzgo360.com/gateway.do?m=order";
?>
<html>
<head> 
<title>pay to bank</title>
</head>
<body onload="document.pay.submit();">
<form name="pay" action="<?php echo $url ?>" method="post">
<input type="hidden" name="merchno" value="<?php echo $merchno ?>">
<input type="hidden" name="amount" value="<?php echo $amount ?>">
<input type="hidden" name="traceno" value="<?php echo $traceno ?>">
<input type="hidden" name="channel" value="<?php echo $channel ?>">
<input type="hidden" name="bankCode" value="<?php echo $bankCode ?>">
<input type="hidden" name="settleType" value="<?php echo $settleType ?>">
<input type="hidden" name="notifyUrl" value="<?php echo $notifyUrl ?>">
<input type="hidden" name="returnUrl" value="<?php echo $returnUrl ?>">
<input type="hidden" name="signature" value="<?php echo $signature ?>">
</form>
</body>
</html>
