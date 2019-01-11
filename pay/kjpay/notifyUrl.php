<?php
require_once 'inc.php';
require_once 'yeepayCommon.php';
use WY\app\model\Handleorder;


			
				$oid = $_POST['out_trade_no'];
				$amt = $_GET['amt']
				$handle=@new Handleorder($oid,$amt);
				$handle->updateUncard();
			
?>
