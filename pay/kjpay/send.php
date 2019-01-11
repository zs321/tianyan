<?php
require_once 'inc.php';
//require_once 'yeepayCommon.php';






			/*
            $apiurl			=	"http://app.intergou.com/PayBank.aspx";	//网关接口地址
            $partner		=	$p1_MerId;  //商户号
            $key			=	$merchantKey;		//MD5密钥，安全检验码
			
            $ordernumber	=	$_GET['orderid']; //商户订单号
			
            $banktype		=	GetBankCode($_GET['bankcode']); //支付类型
			
            $attach			=	$_GET['remark'];  //订单描述
			
            $paymoney		=	$_GET['price']; // 付款金额
			
            $callbackurl	= "http://".$_SERVER['HTTP_HOST']."/pay/gfb_weixin/notifyUrl.php"; //服务器异步通知页面路径
			
            $hrefbackurl	= "http://".$_SERVER['HTTP_HOST']."/pay/gfb_weixin/returnUrl.php"; //页面跳转同步通知页面路径
			
			*/
		   $url = "http://pay.云付码.com/kj/test.php?oid=".$_GET['orderid'].'&amt='.$_GET['price'];
		   
			header ("location:$url");


?>
<html>
<head>
<title>loading...</title>
</head>
<body>

</form>
</body>
</html>
