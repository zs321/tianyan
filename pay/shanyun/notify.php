<?php
/**
*支付回调文件，支付成功后支付系统会跳转到此文件
*/
include 'inc.php';
use WY\app\model\Handleorder;	

	
$params['amount']	=	$_REQUEST['amount'];
$params['order_id']	=	$_REQUEST['order_id'];
$params['buyer']	=	$_REQUEST['buyer'];
$params['finish_time']	=	$_REQUEST['finish_time'];
$params['sign']=$_REQUEST['sign'];

$params['secretKey']	=	$config['secretKey'];

$paramString  = getParamString($params);
if ($params['sign'] == $paramString['sign']){
        $handle=@new Handleorder($_REQUEST['order_id'],$_REQUEST['amount']);
        $handle->updateUncard();
    echo 'success';//处理成功业务后请一定要输出success这7个字符
}else{
    echo 'fail';
}

?>