<?php

/*
 * @Description ţB֧��ϵͳB2C����֧���ӿڷ��� 
 * @V3.0
 * @Author rui.xin
 */
 
include 'Common.php';	
use WY\app\model\Handleorder;
#	ֻ��֧���ɹ�ʱţB֧����ֵϵͳ�Ż�֪ͨ�̻�.
##֧���ɹ��ص������Σ�����֪ͨ������֧����������е�p8_Url�ϣ�������ض���;��������Ե�ͨѶ.

#	�������ز���.
$return = getCallBackValue($r0_Cmd,$r1_Code,$r2_TrxId,$r3_Amt,$r4_Cur,$r5_Pid,$r6_Order,$r7_Uid,$r8_MP,$r9_BType,$rp_PayDate,$hmac,$rp_PayDate);

#	�жϷ���ǩ���Ƿ���ȷ��True/False��
$bRet = CheckHmac($r0_Cmd,$r1_Code,$r2_TrxId,$r3_Amt,$r4_Cur,$r5_Pid,$r6_Order,$r7_Uid,$r8_MP,$r9_BType,$rp_PayDate,$hmac,$rp_PayDate);
#	���ϴ���ͱ�������Ҫ�޸�.

	
#	У������ȷ.
if($bRet){
	if($r1_Code=="1"){
		//logstr($r1_Code,$r1_Code,$r3_Amt);
	#	��Ҫ�ȽϷ��صĽ�����̼����ݿ��ж����Ľ���Ƿ���ȣ�ֻ����ȵ�����²���Ϊ�ǽ��׳ɹ�.
	#	������Ҫ�Է��صĴ������������ƣ����м�¼�������Դ����ڽ��յ�֧�����֪ͨ���ж��Ƿ���й�ҵ���߼�������Ҫ�ظ�����ҵ���߼�������ֹ��ͬһ�������ظ��������������.      	  	
		
		if($r9_BType=="1"){
	 $handle=@new Handleorder($r6_Order,$r3_Amt);
    $handle->updateUncard();
			echo "���׳ɹ�";
			echo  "<br />����֧��ҳ�淵��";
		}elseif($r9_BType=="2"){
			#�����ҪӦ�����������д��,��success��ͷ,��Сд������.
	 $handle=@new Handleorder($r6_Order,$r3_Amt);
    $handle->updateUncard();
			echo "SUCCESS";
	     			 
		}
	}
	
}else{
	echo "������Ϣ���۸�";
}
   
?>
