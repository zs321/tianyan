<?php
require_once 'inc.php';
use WY\app\model\Handleorder;
header("Content-Type: text/html; charset=UTF-8");
date_default_timezone_set('PRC');


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



include("web_sdk.php");
$webApp=new webApp();



$data['ord_no']		=		$_POST['ord_no'];
$data['timestamp']	=		$_POST['timestamp'];
$data['rand_str']	=		$_POST['rand_str'];
$data['out_no']		=		$_POST['out_no'];
$data['status']		=		$_POST['status'];
$data['amount']		=		$_POST['amount'];
$data['pay_time']	=		$_POST['pay_time'];




$data['open_key']	=		$webApp::open_key;
ksort($data);

//echo md5(sha1(createLinkstring($data)));



$data['sign']		=		$_POST['sign'];




if ($data['status']==1){




	$ad['out_no']		=$data['out_no'];
	$ad['ord_no']		= $data['ord_no'];
	$result = $webApp->api("order/view",$ad);
	
	


	if ($result['data']['status']==1){
	
	$orderid			=		$data['out_no'];
	$money				=		$result['data']['trade_amount']/100;

	

	$handle=@new Handleorder($orderid,$money);
    $handle->updateUncard();

	echo "notify_success";
	}

}

?>