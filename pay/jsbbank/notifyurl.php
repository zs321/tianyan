<?php

require_once 'inc.php';
use WY\app\model\Handleorder;


include_once 'rsa.lib.php';
$cwd = realpath(dirname(__FILE__));
$private_key = $cwd.DIRECTORY_SEPARATOR.'privateKey.pem'; // 私钥路径
$public_key = $cwd.DIRECTORY_SEPARATOR.'publicKey.pem'; // 公钥路径
$rsa = new Rsa($private_key, $public_key);

/*
$handle=@new Handleorder('2018053117490499893','1');
$handle->updateUncard();echo 111;die;*/

$rawData = trim(file_get_contents('php://input'));
if (empty($rawData)) die('error');
$req = json_decode($rawData, true);
$transdata = urldecode($req['transdata']);
$d=json_decode($transdata,true);



		/*$james = fopen("ceshi.txt", "a+");
        fwrite($james, "\r\n" . date("Y-m-d H:i:s") ."  回调信息：".json_encode($d) . " \r\n"."  回调信息：".$d . " \r\n"."  回调信息：".$d['order_no']. " \r\n"."  回调信息：".$d->order_no . " \r\n");
        fclose($james); */


$sign = urldecode($req['sign']);
if ( $sign){
	$handle=@new Handleorder($d['order_no'],$d['order_amount']/100);
    $handle->updateUncard();
	http_response_code(200);
    echo 'success';
}else{
    http_response_code(400);
    echo 'signature verify failed';
}


?>
