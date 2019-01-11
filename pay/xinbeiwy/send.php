<?php
require_once 'inc.php';
// 网关地址
$postUrl = 'https://gws.xbeionline.com/Gateway/XbeiPay';

// 通讯密钥
$tonkeyKey = $userkey;

// 获取表单参数
$version = "V1.0";
$merchantCode = $userid;
$orderId = $_REQUEST['orderid'];
$amount =  (int)$_REQUEST['price'];
$asyNotifyUrl = "http://".$_SERVER['HTTP_HOST']."/pay/xinbeiwy/NotifyUrl.php";
$synNotifyUrl = "http://".$_SERVER['HTTP_HOST']."/pay/xinbeiwy/returnUrl.php";
$orderDate = date("YmdHis",time());
$tradeIp = $_SERVER["REMOTE_ADDR"];
$payCode =GetBankCode($_REQUEST['bankcode']);
$cardNo = "";
$cardPassword = "";
$qq = "";
$telephone = "";
$goodsName = "";
$goodsDescription = "";
$remark1 = $_REQUEST["remark"];
$remark2 = "";

$signText = 'Version=['.$version.']MerchantCode=['.$merchantCode.']OrderId=['.$orderId.']Amount=['.$amount.']AsyNotifyUrl=['.$asyNotifyUrl.']SynNotifyUrl=['.$synNotifyUrl.']OrderDate=['.$orderDate.']TradeIp=['.$tradeIp.']PayCode=['.$payCode.']TokenKey=['.$tonkeyKey.']';

$md5Sign = strtoupper(md5($signText));
?>
<html>
<head>
<title>pay</title>
</head>
<body onLoad="document.form1.submit();">
<form id="form1" name="form1" method="post" action="<?php echo "$postUrl"; ?>">
    <input type="hidden" id="Version" name="Version" value="<?php echo "$version"; ?>"/>
    <input type="hidden" id="MerchantCode" name="MerchantCode" value="<?php echo "$merchantCode"; ?>" />
    <input type="hidden" id="OrderId" name="OrderId" value="<?php echo "$orderId"; ?>" />
    <input type="hidden" id="Amount" name="Amount" value="<?php echo "$amount"; ?>" />
    <input type="hidden" id="AsyNotifyUrl" name="AsyNotifyUrl" value="<?php echo "$asyNotifyUrl"; ?>" />
    <input type="hidden" id="SynNotifyUrl" name="SynNotifyUrl" value="<?php echo "$synNotifyUrl"; ?>" />
    <input type="hidden" id="OrderDate" name="OrderDate" value="<?php echo "$orderDate"; ?>"  />
    <input type="hidden" id="TradeIp" name="TradeIp" value="<?php echo "$tradeIp"; ?>" />
    <input type="hidden" id="PayCode" name="PayCode" value="<?php echo "$payCode"; ?>" />
    <input type="hidden" id="CardNo" name="CardNo" value="<?php echo "$cardNo"; ?>" />
    <input type="hidden"  id="CardPassword" name="CardPassword" value="<?php echo "$cardPassword"; ?>" />
    <input type="hidden"  id="QQ" name="QQ" value="<?php echo "$qq"; ?>" />
    <input type="hidden"  id="Telephone" name="Telephone" value="<?php echo "$telephone"; ?>" />
    <input type="hidden"  id="GoodsName" name="GoodsName" value="<?php echo "$goodsName"; ?>" />
    <input type="hidden"  id="GoodsDescription" name="GoodsDescription" value="<?php echo "$goodsDescription"; ?>" />
    <input type="hidden"  id="Remark1" name="Remark1" value="<?php echo "$remark1"; ?>" />
    <input type="hidden"  id="Remark2" name="Remark2" value="<?php echo "$remark2"; ?>" />
    <input type="hidden"  id="SignValue" name="SignValue" value="<?php echo "$md5Sign"; ?>" />
</form>
</body>
</html>
