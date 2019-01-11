<?php
require_once '../../app/init.php';
use WY\app\woodyapp;
use WY\app\model\Payacp;

$app=woodyapp::getInstance();
$acp=new Payacp();
$acpData=$acp->get('103877');


extract($acpData);

function GetBankCode($bankid){
	$bankcode=array();
	if($bankid=='ICBC') $bankcode[]="102";$bankcode[]="中国工商银行";
	if($bankid=='ICBC') $bankcode[]="103";$bankcode[]="中国农业银行";
	if($bankid=='ICBC') $bankcode[]="104";$bankcode[]="中国银行";
	if($bankid=='ICBC') $bankcode[]="105";$bankcode[]="中国建设银行";
	if($bankid=='ICBC') $bankcode[]="301";$bankcode[]="交通银行";
	if($bankid=='ICBC') $bankcode[]="302";$bankcode[]="中信银行";
	if($bankid=='ICBC') $bankcode[]="303";$bankcode[]="中国光大银行";
	if($bankid=='ICBC') $bankcode[]="304";$bankcode[]="华夏银行";
	if($bankid=='ICBC') $bankcode[]="305";$bankcode[]="中国民生银行";
	if($bankid=='ICBC') $bankcode[]="306";$bankcode[]="广发银行";
	if($bankid=='ICBC') $bankcode[]="313";$bankcode[]="北京银行";
	if($bankid=='ICBC') $bankcode[]="316";$bankcode[]="浙商银行";
	if($bankid=='ICBC') $bankcode[]="401";$bankcode[]="上海银行";
	if($bankid=='ICBC') $bankcode[]="403";$bankcode[]="中国邮政储蓄银行";
	if($bankid=='ICBC') $bankcode[]="408";$bankcode[]="宁波银行";
	return $bankcode;
}
?>
