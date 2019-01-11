<?php
require_once 'inc.php';
use WY\app\model\Handleorder;

		// $james = fopen("ceshi.txt", "a+");
  //       fwrite($james, "\r\n" . date("Y-m-d H:i:s") ."  回调信息：".json_encode($_REQUEST) . " \r\n");
  //       fclose($james);
        
            $ordernumber = $_GET["ordernumber"];
            $paymoney = $_GET["paymoney"];
if($ordernumber){
 	$handle=@new Handleorder($ordernumber,$paymoney);
    $handle->updateUncard();
	echo('ok');exit;
} else {
    echo 'signerr';
}
?>
