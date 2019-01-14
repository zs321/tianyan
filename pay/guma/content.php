<?php
require_once 'inc.php';
header("Content-type: text/html; charset=utf-8");


// 收到传过来的订单号，将订单号取出支付的地址进行控制跳转
$redis = new Redis();
$redis->connect("127.0.0.1",6379);
$orderid = $_GET["orderid"];
$url = $redis->get($orderid);

header("Location:$url");







