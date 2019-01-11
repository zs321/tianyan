<?php

require_once 'inc.php';

use WY\app\model\Handleorder;


$oid = $_POST['traceno'];
$amt = $_POST['amount'];
$handle=@new Handleorder($oid,$amt);
$handle->updateUncard();

?>