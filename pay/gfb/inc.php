<?php
require_once '../../app/init.php';
use WY\app\woodyapp;
use WY\app\model\Payacp;

$app=woodyapp::getInstance();
$acp=new Payacp();
$acpData=$acp->get('gfb');


extract($acpData);

function GetBankCode($bankid){
	$bankcode="";
	if($bankid=='ICBC') $bankcode="ICBC";
	if($bankid=='ABC')	$bankcode="ABC";
	if($bankid=='BOC') $bankcode="BOC";
	if($bankid=='CCB') $bankcode="CCB";
	if($bankid=='CMB') $bankcode="CMB";
	if($bankid=='SPDB') $bankcode="SPDB";
	if($bankid=='GDB') $bankcode="GDB";
	if($bankid=='BOCO') $bankcode="BOCO";
	if($bankid=='PSBS') $bankcode="PSBS";
	if($bankid=='CTTIC') $bankcode="CTTIC";
	if($bankid=='CMBC') $bankcode="CMBC";
	if($bankid=='HXB') $bankcode="HXB";
	if($bankid=='CIB') $bankcode="CIB";
	if($bankid=='SHB') $bankcode="SHB";
	if($bankid=='SRCB') $bankcode="SRCB";
	if($bankid=='BCCB') $bankcode="BCCB";
	if($bankid=='PINGANBANK') $bankcode="PINGANBANK";
	if($bankid=='weixin') $bankcode="WEIXIN";
	if($bankid=='alipay') $bankcode="ALIPAY";
	if($bankid=='alipaywap') $bankcode="ALIPAYWAP";
	if($bankid=='wxh5') $bankcode="WEIXINWAP";
	if($bankid=='qq') $bankcode="QQ";
	return $bankcode;
}
?>
