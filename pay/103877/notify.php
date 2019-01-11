<?php
header("Content-type: text/html; charset=utf-8");

require_once 'inc.php';
use WY\app\model\Handleorder;



$ordernumber=$_POST['transNo']; 
$paymoney=$_POST['payAmount']; 


$handle=@new Handleorder($ordernumber,$paymoney);
$handle->updateUncard();

echo "success";





?>
