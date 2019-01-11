<?php
require_once 'inc.php';
use WY\app\model\Handleorder;
$out_no=$_POST['out_no'];
$amount=$_POST['amount']/100;
if($sign==$mysign){
    echo 'success';
 	$handle=@new Handleorder($out_no,$amount);
    $handle->updateUncard();
} else {
    echo 'signerr';
}
?>
