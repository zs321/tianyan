<?php
require_once 'inc.php';

use WY\app\model\Handleorder;
// 通讯密钥
$tonkeyKey = $userkey;

// 获取表单参数
$version = $_REQUEST["Version"];
$merchantCode = $_REQUEST["MerchantCode"];
$orderId = $_REQUEST["OrderId"];
$orderDate = $_REQUEST["OrderDate"];
$tradeIp = $_REQUEST["TradeIp"];
$serialNo = $_REQUEST["SerialNo"];
$amount = $_REQUEST["Amount"];
$payCode = $_REQUEST["PayCode"];
$state = $_REQUEST["State"];
$message = $_REQUEST["Message"];
$finishTime = $_REQUEST["FinishTime"];
$qq = $_REQUEST["QQ"];
$telephone = $_REQUEST["Telephone"];
$goodsName = $_REQUEST["GoodsName"];
$goodsDescription = $_REQUEST["GoodsDescription"];
$remark1 = $_REQUEST["Remark1"];
$remark2 = $_REQUEST["Remark2"];
$signValue = $_REQUEST["SignValue"];

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
	
	}
}
else{
	echo '验签失败';
}
?>