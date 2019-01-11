<?php
require_once 'inc.php';
use WY\app\model\Pushorder;

if(!empty($_GET['orderid'])){
	$orderid=$_GET['orderid'];
	$data['mchtNo']='888584054110506';
	$data['payOrderNo']=$orderid;
	ksort($data);
	reset($data);
	$md5str = "";
	foreach ($data as $key => $val) {
		$md5str = strtolower($md5str . $key . "=" . $val . "&");
	} 
	$key='1318A8EB7DFAD92C9AF5CAE71360F870';
	$md5str .= "key=" . $key;
	$data['sign']=strtoupper(md5($md5str)); 
	$url='http://159h1552q8.imwork.net/NewPay/qhsl-gsyf/query';
	$header[]='Content-Type: application/json; charset=utf-8';
	$header[]='Content-Length:' . strlen(json_encode($data));
	$res=curl_get_https($url, $data,$header); 
	$res=json_decode($res);
	if($res->respCode=='00000'){
		$url='http://'.$_SERVER['HTTP_HOST'].'/pay/8885840541/notifyurl.php?orderNo='.$orderid."&amount=".$res->amount;
		curl_get($url);
	}
}

$orderid=isset($_REQUEST['orderid']) ? $_REQUEST['orderid'] : '';
$push=new Pushorder($orderid);
$push->sync();

	function curl_get_https($url, $data=array(), $header=array(), $timeout=30){ 
		$ch = curl_init(); 
		$data = json_encode( $data );
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // 跳过证书检查 
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2); // 从证书中检查SSL加密算法是否存在 
		curl_setopt($ch, CURLOPT_URL, $url); 
		curl_setopt($curl, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $header); 
		curl_setopt($ch, CURLOPT_POST, true); 
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data); 
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
		curl_setopt($ch, CURLOPT_TIMEOUT, $timeout); 
	
		$response = curl_exec($ch); 
	
		if($error=curl_error($ch)){ 
			die($error); 
		} 
	
		curl_close($ch); 
	
		return $response; 
	}
	
	function curl_get($url){
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
		$output = curl_exec($ch);
		curl_close($ch);
		return $output;
	}
?>
