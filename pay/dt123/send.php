<?php
require_once 'inc.php';
$version='1.0';
$customerid=$userid;
$sdorderno=$_REQUEST['orderid'];

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


if ($paytype=='Alipay' ||  $paytype=='wxgzh' || $paytype=='Alipaywap' || $paytype=='jbypc' || $paytype=='wyweixin'|| $paytype=='wyjd'|| $paytype=='wykjbank' || $paytype=='wyqq'|| $paytype=='quickbank' || $paytype=='Qqwallet'){

	$bankcode="";
}else{

	$paytype	="bank";
	$bankcode=clearxss($_REQUEST['bankcode']);

}


$notifyurl='http://'.$_SERVER['HTTP_HOST'].'/pay/dt123/notify.php';
$returnurl='http://'.$_SERVER['HTTP_HOST'].'/pay/dt123/return.php';

$sign=md5($customerid.'dt10021'.$sdorderno.$total_fee.$notifyurl.$userkey);

?>
<!doctype html>
<html>
<head>
    <meta charset="utf8">
    <title>正在转到付款页</title>
</head>
<body onLoad="document.pay.submit()">
    <form name="pay" action="http://interface.dt123.xyz:8080/Pay/pay.jsp" method="post">
        <input type="hidden" name="agentNo" value="dt10021">
        <input type="hidden" name="mercNo" value="<?php echo $customerid?>">
        <input type="hidden" name="amount" value="<?php echo $total_fee?>">
        <input type="hidden" name="orderNo" value="<?php echo $sdorderno?>">
        <input type="hidden" name="callback" value="<?php echo $notifyurl?>">
        <input type="hidden" name="flag" value="1">
        <input type="hidden" name="sign" value="<?php echo $sign?>">
 
    </form>
</body>
</html>
