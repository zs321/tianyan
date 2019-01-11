<?php
header("Content-type: text/html; charset=utf-8");

require_once 'inc.php';
use WY\app\model\Handleorder;



$ordernumber='2018052948101100'; 
//$paymoney=$_POST['total_fee']; 

$total_fee=$this->model()->select('total_fee')->from('orders')->where(array('fields' => 'orderid=?', 'values' => array($ordernumber)))->fetchRow();
var_dump($total_fee);die;

/*$james = fopen("ceshi.txt", "a+");
fwrite($james, "\r\n" . date("Y-m-d H:i:s") ."  回调信息11：".$ordernumber.'====='.$_POST['sdorderno']. " \r\n");
fclose($james);*/


$handle=@new Handleorder($ordernumber,$paymoney);
$handle->updateUncard();

echo "success";





?>
