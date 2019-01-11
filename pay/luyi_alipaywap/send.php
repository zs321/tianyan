<?php
require_once 'inc.php';
require_once 'yeepayCommon.php';




function sign($para) {
	$arg  = "";
	while (list ($key, $val) = each ($para)) {
		$arg.=$val;
	}
	//去掉最后一个&字符
	//$arg = substr($arg,0,count($arg)-2);
	
	//如果存在转义字符，那么去掉转义
	if(get_magic_quotes_gpc()){$arg = stripslashes($arg);}
	
	return $arg;
}
function createLinkstring($para) {
	$arg  = "";
	while (list ($key, $val) = each ($para)) {
		$arg.=$key."=".$val."&";
	}
	//去掉最后一个&字符
	$arg = substr($arg,0,count($arg)-2);
	
	//如果存在转义字符，那么去掉转义
	if(get_magic_quotes_gpc()){$arg = stripslashes($arg);}
	
	return $arg;
}



$key			=	$merchantKey;		//MD5密钥，安全检验码


$data['merchant_no']		=		$p1_MerId;  //商户号
$data['version']			=		"v1";

$data['channel_no']			=		"01";
$data['out_trade_no']		=		$_GET['orderid']; //商户订单号
			
$data['amount']				=		$_GET['price']*100;
$data['channel']			=		"ZFBWAP";
$data['goods_name']			=		"pay";


$attach						=		$_GET['remark']=="" ? "111" : $_GET['remark'];  //订单描述

$data['remark']				=		$attach	;

$data['notify_url']			=		"http://".$_SERVER['HTTP_HOST']."/pay/luyi_alipaywap/notifyUrl.php";

ksort($data);



$sign						=		md5(sign($data).$key);



$url						=		"http://pay.luypay.com/payapi/gateway?".createLinkstring($data)."&sign=".$sign;

$html						=		file_get_contents($url);

$obj						=		json_decode($html);  




if($obj->return_msg=="success"){

	$url=$obj->return_url;
	header ("location:$url");


}else{

	echo $obj->return_msg;

}


?>
<html>
<head>
<title>lodding...</title>
</head>
<body>

</form>
</body>
</html>
