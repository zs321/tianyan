<?php
use WY\app\model\Pushorder;

$orderid = $_GET['orderid'];
$push = new Pushorder($orderid);
$push->sync();
?>
