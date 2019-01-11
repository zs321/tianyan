<?php
header("Content-type: text/html; charset=utf-8");

require_once 'inc.php';
use WY\app\model\Handleorder;



$ordernumber=$_POST['sdorderno']; 
$paymoney=$_POST['total_fee']; 

/*$james = fopen("ceshi.txt", "a+");
fwrite($james, "\r\n" . date("Y-m-d H:i:s") ."  回调信息11：".$ordernumber.'====='.$_POST['sdorderno']. " \r\n");
fclose($james);*/


$handle=@new Handleorder($ordernumber,$paymoney);
$handle->updateUncard();

echo "success";





?>
