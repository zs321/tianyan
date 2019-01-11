<?php
require_once 'inc.php';
use WY\app\model\Handleorder;


    echo 'success';
    $handle=@new Handleorder($_REQUEST["orderNo"],$_REQUEST["amount"]);
    $handle->updateUncard();
?>
