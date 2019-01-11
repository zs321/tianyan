<?php

/*
 * @Description 牛B支付接口范例 
 * @V3.0
 * @Author rui.xin
 */

include 'Common.php';	
		
#	商家设置用户购买商品的支付信息.
#   牛B支付系统使用UTF-8编码方式,参数如用到中文，请注意转码

#	商户订单号,选填.

##若不为""，提交的订单号必须在自身账户交易中唯一;
$p2_Order					=$_REQUEST['orderid'];

#	支付金额,必填.
##单位:元，精确到分.
$p3_Amt						= (INT)$_REQUEST["price"];

#	交易币种,固定值"CNY".
$p4_Cur						= "CNY";

#	商品名称
##用于支付时显示在帝岭科技网关左侧的订单产品信息.
$p5_Pid						= 'name';

#	商品种类
$p6_Pcat					= 'class';

#	商品描述
$p7_Pdesc					= "http://".$_SERVER['HTTP_HOST']."/pay/niubi/returnUrl.php?o=".$p2_Order;

#	商户接收支付成功数据的地址,支付成功后牛B支付会向该地址发送多次成功通知.
$p8_Url						= "http://".$_SERVER['HTTP_HOST']."/pay/niubi/callback.php";	

#	商户扩展信息
##商户可以任意填写1K 的字符串,支付成功时将原样返回.												
$pa_MP						=  $_REQUEST['remark'];

#	支付通道编码
##默认为""，到牛B支付网关..			
$pd_FrpId					=  "gateway";//GetBankCode($_REQUEST['bankcode']);

#	应答机制
##默认为"1": 需要应答机制;
$pr_NeedResponse	= "1";

#调用签名函数生成签名串
$hmac = getReqHmacString($p2_Order,$p3_Amt,$p4_Cur,$p5_Pid,$p6_Pcat,$p7_Pdesc,$p8_Url,$pa_MP,$pd_FrpId,$pr_NeedResponse);
     
?> 
<html>
<head>
<title>牛B支付</title>
</head>
<body onLoad="document.diy.submit();">
<form name='diy' id="diy" action='<?php echo $reqURL_onLine; ?>' method='post'>
<input type='hidden' name='p0_Cmd'					value='<?php echo $p0_Cmd; ?>'>
<input type='hidden' name='p1_MerId'				value='<?php echo $p1_MerId; ?>'>
<input type='hidden' name='p2_Order'				value='<?php echo $p2_Order; ?>'>
<input type='hidden' name='p3_Amt'					value='<?php echo $p3_Amt; ?>'>
<input type='hidden' name='p4_Cur'					value='<?php echo $p4_Cur; ?>'>
<input type='hidden' name='p5_Pid'					value='<?php echo $p5_Pid; ?>'>
<input type='hidden' name='p6_Pcat'					value='<?php echo $p6_Pcat; ?>'>
<input type='hidden' name='p7_Pdesc'				value='<?php echo $p7_Pdesc; ?>'>
<input type='hidden' name='p8_Url'					value='<?php echo $p8_Url; ?>'>
<input type='hidden' name='p9_SAF'					value='<?php echo $p9_SAF; ?>'>
<input type='hidden' name='pa_MP'						value='<?php echo $pa_MP; ?>'>
<input type='hidden' name='pd_FrpId'				value='<?php echo $pd_FrpId; ?>'>
<input type='hidden' name='pr_NeedResponse'	value='<?php echo $pr_NeedResponse; ?>'>
<input type='hidden' name='hmac'						value='<?php echo $hmac; ?>'>
</form>
</body>
</html>