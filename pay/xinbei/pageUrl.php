<?php
require_once 'inc.php';

use WY\app\model\Handleorder;
// 通讯密钥
$tonkeyKey = $userkey;

// 获取表单参数
$version = $_POST["Version"];
$merchantCode = $_POST["MerchantCode"];
$orderId = $_POST["OrderId"];
$orderDate = $_POST["OrderDate"];
$tradeIp = $_POST["TradeIp"];
$serialNo = $_POST["SerialNo"];
$amount = $_POST["Amount"];
$payCode = $_POST["PayCode"];
$state = $_POST["State"];
$message = $_POST["Message"];
$finishTime = $_POST["FinishTime"];
$qq = $_POST["QQ"];
$telephone = $_POST["Telephone"];
$goodsName = $_POST["GoodsName"];
$goodsDescription = $_POST["GoodsDescription"];
$remark1 = $_POST["Remark1"];
$remark2 = $_POST["Remark2"];
$signValue = $_POST["SignValue"];

$signText = 'Version=['.$version.']MerchantCode=['.$merchantCode.']OrderId=['.$orderId.']OrderDate=['.$orderDate.']TradeIp=['.$tradeIp.']SerialNo=['.$serialNo.']Amount=['.$amount.']PayCode=['.$payCode.']State=['.$state.']FinishTime=['.$finishTime.']TokenKey=['.$tonkeyKey.']';
$md5Sign = strtoupper(md5($signText));

if($signValue == $md5Sign)
{
	if($state=='8888') {
		// 请根据您的成功业务逻辑进行处理
		// 并请在进行业务逻辑内容处理时注意判断订单重复性的问题，本平台异步通知会发送多次通知
			$handle=@new Handleorder($orderId,$amount);
			 $handle->updateUncard();
		
		// 业务逻辑处理完成后请打印OK表示成功接收，并不能打印其他编码内容
		echo 'ok';
	}
}
else{
	echo '验签失败';
}
?>