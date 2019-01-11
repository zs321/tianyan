<?php
require_once 'inc.php';
use WY\app\model\Handleorder;
$json= file_get_contents('php://input');
$data=json_decode($json,true);
if($data){
    echo '00';
 	$handle=@new Handleorder($data['corp_flow_no'],$data['totalAmount']);
    $handle->updateUncard();
} else {
    echo 'signerr';
}
?>
