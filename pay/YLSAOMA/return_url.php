<?php
use WY\app\model\Pushorder;

$orderid = $_GET['order_id'];
$push = new Pushorder($orderid);
$push->sync();
?>
