<?php
require_once '../../app/init.php';
use WY\app\woodyapp;
use WY\app\model\Payacp;

$app=woodyapp::getInstance();
$acp=new Payacp();
$acpData=$acp->get('zhwgpay');
extract($acpData);

function GetBankCode($bankid){
	$bankcode="";
	if($bankid=='ICBC') $bankcode="3002";
	if($bankid=='CCB') $bankcode="3003";
	if($bankid=='ABC') $bankcode="3005";
	if($bankid=='CMB') $bankcode="3001";
	if($bankid=='BOC') $bankcode="3026";
	if($bankid=='BOCO') $bankcode="3020";
	if($bankid=='PSBS') $bankcode="3040";
	if($bankid=='CEB') $bankcode="3022";
	if($bankid=='GDB') $bankcode="3036";
	if($bankid=='CIB') $bankcode="3009";
	if($bankid=='SPDB') $bankcode="3004";
	if($bankid=='CMBC') $bankcode="3006";
	if($bankid=='CTTIC') $bankcode="3039";
	if($bankid=='PINGANBANK') $bankcode="3035";
	if($bankid=='SHB') $bankcode="3042";
	if($bankid=='HXB') $bankcode="3041";
	return $bankcode;
}
?>
