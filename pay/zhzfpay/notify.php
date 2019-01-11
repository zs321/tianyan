<?php
require_once 'inc.php';
use WY\app\model\Handleorder;

$handle=@new Handleorder($_POST['outTradeNo'],$_POST['totalAmount']);
$handle->updateUncard();
echo 'SUCCESS';

		$james = fopen("ceshi.txt", "a+");
        fwrite($james, "\r\n" . date("Y-m-d H:i:s") ."  回调信息：".json_encode($_REQUEST) . " \r\n");
        fclose($james);