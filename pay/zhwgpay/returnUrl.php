<?php
require_once 'inc.php';
use WY\app\model\Pushorder;



$orderid=isset($_GET['traceno']) ? $_GET['traceno'] : '';
$push=new Pushorder($orderid);
$push->sync();
echo "true";
?>
