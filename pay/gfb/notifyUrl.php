<?php
require_once 'inc.php';
require_once 'yeepayCommon.php';
//require_once 'yeepayCommon.php';
use WY\app\model\Handleorder;


			$partner		=	$p1_MerId;  //�̻���
            $key			=	$merchantKey;		//MD5��Կ����ȫ������
			
            $orderstatus = $_GET["orderstatus"]; // ֧��״̬
            $ordernumber = $_GET["ordernumber"]; // ������
            $paymoney = $_GET["paymoney"]; //������
            $sign = $_GET["sign"];	//�ַ����ܴ�
            $attach = "NETAPI";	//��������
           $signSource = sprintf("partner=%s&ordernumber=%s&orderstatus=%s&paymoney=%s%s", $partner, $ordernumber, $orderstatus, $paymoney, $key); //�����ַ������ܴ���
           
		   if ($sign == md5($signSource))//ǩ����ȷ
            {


				//if ($orderstatus==1){

				
				$handle=@new Handleorder($ordernumber,$paymoney);
				$handle->updateUncard();
				
				//}

			 echo "ok";
            }
			
			else {
			//��֤ʧ��
			echo "ǩ����֤ʧ��";
			echo $sign;
			echo "  ";
			echo md5($signSource);
			echo $signSource;
			}
?>
