<?php
use WY\app\model\Handleorder;
require_once 'inc.php';
file_put_contents('post_notice.txt', json_encode($_REQUEST));

$params = $_POST;
$params['key'] = $userkey;

if (!verify_sign($params)){
    exit('FAIL');
}else{
    $handle = new Handleorder($params['order_id'], $params['amount']);
    $handle->updateUncard();
    exit('OK');
}
?>
