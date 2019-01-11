<?php
require_once 'inc.php';
use WY\app\model\Pushorder;

$orderid=isset($_GET['o']) ? $_GET['o'] : '';
$push=new Pushorder($orderid);
echo $push->ajax();
?>
