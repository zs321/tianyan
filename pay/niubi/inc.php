<?php
require_once '../../app/init.php';
header("Content-type: text/html; charset=utf-8");
use WY\app\woodyapp;
use WY\app\model\Payacp;

$app=woodyapp::getInstance();
$acp=new Payacp();
$acpData=$acp->get('niubi');


extract($acpData);

$p1_MerId			= $userid;																										#测试使用
$merchantKey	= $userkey;		#测试使用

$logName	= "BANK_HTML.log";

?>
