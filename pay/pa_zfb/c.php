<?php
require_once 'inc.php';
use WY\app\model\Handleorder;
header("Content-Type: text/html; charset=UTF-8");
date_default_timezone_set('PRC');



	$orderid			=		"2017090219332615044";
	$money				=		"1.00";

	

	$handle=@new Handleorder($orderid,$money);
    $handle->updateUncard();

?>