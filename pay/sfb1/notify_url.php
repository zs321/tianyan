<?php
require_once 'inc.php';
include_once './sdk/acp_service.php';
use WY\app\model\Handleorder;
file_put_contents('post_notice.txt', json_encode($_REQUEST));



$params = array();
if (!$_POST) {
	echo "error";
    exit;
}
foreach ($_POST as $key => $val) {
    if (!isset($params[$key])) {  //取值方式，key参考文档
        $params[$key] = $val;
    }
}
if (!AcpService::validate($params)) {
    //应答报文验签失败
    echo "error";
    exit;
}  else{
	
	//验签通过之后 判断订单是否成功

$code = $params["respCode"]; 
if ($code == "00") {  //支付成功
    $handle=new Handleorder($params["orderId"],$params["txnAmt"]/100);
    $handle->updateUncard();echo "success";
} else {  //支付失败
   //执行执行自己的逻辑
}
}



?>
