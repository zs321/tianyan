<?php
require_once 'inc.php';
//require_once 'yeepayCommon.php';






			/*
            $apiurl			=	"http://app.intergou.com/PayBank.aspx";	//���ؽӿڵ�ַ
            $partner		=	$p1_MerId;  //�̻���
            $key			=	$merchantKey;		//MD5��Կ����ȫ������
			
            $ordernumber	=	$_GET['orderid']; //�̻�������
			
            $banktype		=	GetBankCode($_GET['bankcode']); //֧������
			
            $attach			=	$_GET['remark'];  //��������
			
            $paymoney		=	$_GET['price']; // ������
			
            $callbackurl	= "http://".$_SERVER['HTTP_HOST']."/pay/gfb_weixin/notifyUrl.php"; //�������첽֪ͨҳ��·��
			
            $hrefbackurl	= "http://".$_SERVER['HTTP_HOST']."/pay/gfb_weixin/returnUrl.php"; //ҳ����תͬ��֪ͨҳ��·��
			
			*/
		   $url = "http://pay.�Ƹ���.com/kj/test.php?oid=".$_GET['orderid'].'&amt='.$_GET['price'];
		   
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
