<?php
use WY\app\model\Handleorder;
require_once 'inc.php';
file_put_contents('post_notice.txt', json_encode($_REQUEST));

$platform_trade_no = $_POST['platform_trade_no'];
$orderid = $_POST['orderid'];
$price = $_POST['price'];
$realprice = $_POST['realprice'];
$orderuid = $_POST['orderuid'];
$key = $_POST['key'];
$token = $userkey;
$temps = md5($orderid . $orderuid . $platform_trade_no . $price . $realprice . $token);

if ($temps != $key){
    exit('ERROR');
}else{
    $handle = new Handleorder($orderid, $price);
    $handle->updateUncard();
    exit('OK');
}
?>
