﻿<?php
require_once 'inc.php';
use WY\app\model\Handleorder;
$status=$_GET['status'];
$customerid=$_GET['customerid'];
$sdorderno=$_GET['sdorderno'];
$total_fee=$_GET['total_fee'];
$paytype=$_GET['paytype'];
$sdpayno=$_GET['sdpayno'];
$remark=$_GET['remark'];
$sign=$_GET['sign'];

$mysign=md5('customerid='.$customerid.'&status='.$status.'&sdpayno='.$sdpayno.'&sdorderno='.$sdorderno.'&total_fee='.$total_fee.'&paytype='.$paytype.'&'.$userkey);

if($sign==$mysign){
    if($status=='1'){
        echo '付款成功';
 	$handle=@new Handleorder($sdorderno,$total_fee);
    $handle->updateUncard();
    } else {
        echo 'fail';
    }
} else {
    echo 'sign error';
}
?>
