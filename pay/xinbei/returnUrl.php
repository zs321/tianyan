<?php
require_once 'inc.php';
use WY\app\model\Pushorder;

$orderid=isset($_GET['OrderId']) ? $_GET['OrderId'] : '';
$push=new Pushorder($orderid);
echo $push->ajax();
?>
