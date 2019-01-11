<?php
require_once 'inc.php';
use WY\app\model\Handleorder;
$status=$_POST['status'];
$customerid=$_POST['customerid'];
$sdorderno=$_POST['sdorderno'];
$total_fee=$_POST['total_fee'];
$paytype=$_POST['paytype'];
$sdpayno=$_POST['sdpayno'];
$remark=$_POST['remark'];
$sign=$_POST['sign'];
$mysign=md5('customerid='.$customerid.'&status='.$status.'&sdpayno='.$sdpayno.'&sdorderno='.$sdorderno.'&total_fee='.$total_fee.'&paytype='.$paytype.'&'.$userkey);
file_put_contents("notify.log",$mysign.'-'.$sign);
if($sign==$mysign){
    if($status=='1'){
     echo 'success';
 	$handle=@new Handleorder($sdorderno,$total_fee);
    $handle->updateUncard();
	file_put_contents("ok.log","success");
    } else {
        echo 'fail';
    }
} else {
    echo 'signerr';
}
?>
