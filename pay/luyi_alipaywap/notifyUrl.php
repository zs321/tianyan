<?php
require_once 'inc.php';
require_once 'yeepayCommon.php';
use WY\app\model\Handleorder;

function sign($para) {
	$arg  = "";
	while (list ($key, $val) = each ($para)) {
		$arg.=$val;
	}
	//ȥ�����һ��&�ַ�
	//$arg = substr($arg,0,count($arg)-2);
	
	//�������ת���ַ�����ôȥ��ת��
	if(get_magic_quotes_gpc()){$arg = stripslashes($arg);}
	
	return $arg;
}
function createLinkstring($para) {
	$arg  = "";
	while (list ($key, $val) = each ($para)) {
		$arg.=$key."=".$val."&";
	}
	//ȥ�����һ��&�ַ�
	$arg = substr($arg,0,count($arg)-2);
	
	//�������ת���ַ�����ôȥ��ת��
	if(get_magic_quotes_gpc()){$arg = stripslashes($arg);}
	
	return $arg;
}



$key			=	$merchantKey;		//MD5��Կ����ȫ������


$data['return_code']		=		$_REQUEST['return_code'];  //1:��Ϣ���سɹ�
$data['return_msg']			=		$_REQUEST['return_msg'];

$data['amount']				=		$_REQUEST['amount'];
$data['trade_flow']			=		$_REQUEST['trade_flow']; //ƽ̨��ˮ��
			
$data['out_trade_no']		=		$_REQUEST['out_trade_no']; //�̻�������
$data['status']				=		$_REQUEST['status']; //�ɹ���success

$data['remark']				=		$_REQUEST['remark']; //�ɹ���success

$data['notify_url']			=		"http://".$_SERVER['HTTP_HOST']."/pay/luyi_alipay/notifyUrl.php";


$tsign						=		$_REQUEST['sign'];

ksort($data);



$sign						=		md5(sign($data).$key);

if($sign==$tsign){


				if ($status=="success"){

				
				$amount=$data['amount']/100;
				$handle=@new Handleorder($data['out_trade_no'],$amount);
				$handle->updateUncard();
					echo "success";
				}else{
				
					echo "err";
				}


}else{

	echo "ǩ����֤ʧ��";

}


?>
