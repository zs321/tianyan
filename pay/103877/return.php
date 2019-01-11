<?php
require_once 'inc.php';
use WY\app\model\Handleorder;
if(!empty($_POST['result']) && $_POST['result']=='1000'){
	echo '付款成功';
}else{
	echo '付款失败';
}
?>
