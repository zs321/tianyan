<?php
require_once 'inc.php';
require_once 'yeepayCommon.php';







            $apiurl			=	"http://app.intergou.com/PayBank.aspx";	//���ؽӿڵ�ַ
            $partner		=	$p1_MerId;  //�̻���
            $key			=	$merchantKey;		//MD5��Կ����ȫ������
			
            $ordernumber	=	$_GET['orderid']; //�̻�������
			
            $banktype		=	GetBankCode($_GET['bankcode']); //֧������
			
            $attach			=	$_GET['remark'];  //��������
			
            $paymoney		=	$_GET['price']; // ������
			
            $callbackurl	= "http://".$_SERVER['HTTP_HOST']."/pay/gfb_weixin/notifyUrl.php"; //�������첽֪ͨҳ��·��
			
            $hrefbackurl	= "http://".$_SERVER['HTTP_HOST']."/pay/gfb_weixin/returnUrl.php"; //ҳ����תͬ��֪ͨҳ��·��
			
            $signSource = sprintf("partner=%s&banktype=%s&paymoney=%s&ordernumber=%s&callbackurl=%s%s", $partner, $banktype, $paymoney, $ordernumber, $callbackurl, $key); //�ַ������Ӵ���
            $sign = md5($signSource);  //�ַ������ܴ���
            $postUrl = $apiurl. "?banktype=".$banktype;
			$postUrl.="&partner=".$partner;
            $postUrl.="&paymoney=".$paymoney;
            $postUrl.="&ordernumber=".$ordernumber;
            $postUrl.="&callbackurl=".$callbackurl;
            $postUrl.="&hrefbackurl=".$hrefbackurl;
            $postUrl.="&attach=".$attach;
            $postUrl.="&sign=".$sign;
			header ("location:$postUrl");


?>
<html>
<head>
<title>lodding...</title>
</head>
<body>

</form>
</body>
</html>
