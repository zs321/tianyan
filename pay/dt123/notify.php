<?php
require_once 'inc.php';
use WY\app\model\Handleorder;

$json= file_get_contents('php://input');
$data=json_decode($json,true);

 $code=$data['code'];
 $msg=$data['msg'];
 $orderno=$data['orderNo'];
 $amount=$data['amount'];
 $orderid=$data['orderId'];
 $sign=$data['sign'];
 
//{"sign":"1232412b89cf5347ad7c5a1def4e8669","amount":"10","orderNo":"2018042515590644596","code":"00","msg":"付款成功","orderId":"218676047412192J"}


file_put_contents("json.txt", $json, FILE_APPEND);
file_put_contents("orderNo.txt", $code.$msg.$orderno.$amount, FILE_APPEND);


$mysign=md5($code.$orderno.$orderid.$userkey);


if($sign==$mysign){
    if($code=='00'){
     echo 'SUCCESS';
 	$handle=@new Handleorder($orderno,$amount);
    $handle->updateUncard();
    } else {
        echo 'fail';
    }
} else {
    echo 'signerr';
}
?>
