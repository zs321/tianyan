<?php

use WY\app\model\Handleorder;
require_once 'inc.php';
/**
 * 支付回调地址
 * 用于异步通知支付状态
 */
$keySign = $userkey;//密钥

$returnText = file_get_contents('php://input');

$post = $returnText;
$posts = explode('|',$post);
$sign = $posts[0];
$paramsJson = $posts[1];
$paramsJsonBase64 = base64_encode($paramsJson);
$paramsJsonBase64Md5 = md5($paramsJsonBase64);
$signMyself = strtoupper(md5($keySign.$paramsJsonBase64Md5));

if($sign != $signMyself){
    echo 'sign Error';
	
	file_put_contents("Error.txt",$returnText, FILE_APPEND);
}
else{
    //获取传递的值
    $params = json_decode($paramsJson,true);
	
	file_put_contents("Json.txt",$paramsJson, FILE_APPEND);
	
    // 这里会有一些逻辑的,具体有商户定义
    $handle = new Handleorder($params['data']['orderId'], $params['data']['orderAmount']);
    $handle->updateUncard();
	
    //成功一定要输出success这七个英文字母.
    echo 'success';
}

