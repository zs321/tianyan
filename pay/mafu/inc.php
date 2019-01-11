<?php
require_once '../../app/init.php';
use WY\app\woodyapp;
use WY\app\model\Payacp;

$app=woodyapp::getInstance();
$acp=new Payacp();
$acpData=$acp->get('mafu');


extract($acpData);

function GetBankCode($bankid){
	$bankcode="";
	if($bankid=='ICBC') $bankcode="ICBC";
	if($bankid=='ABC')	$bankcode="ABC";
	if($bankid=='BOCSH') $bankcode="BOC";
	if($bankid=='CCB') $bankcode="CCB";
	if($bankid=='CMB') $bankcode="CMB";
	if($bankid=='SPDB') $bankcode="SPDB";
	if($bankid=='GDB') $bankcode="GDB";
	if($bankid=='BOCOM') $bankcode="BOCO";
	if($bankid=='PSBC') $bankcode="PSBS";
	if($bankid=='CNCB') $bankcode="CTTIC";
	if($bankid=='CMBC') $bankcode="CMBC";
	if($bankid=='CEB') $bankcode="CIB";
	if($bankid=='HXB') $bankcode="HXB";
	if($bankid=='CIB') $bankcode="CIB";
	if($bankid=='BOS') $bankcode="SHB";
	if($bankid=='SRCB') $bankcode="SRCB";
	if($bankid=='PAB') $bankcode="PINGANBANK";
	if($bankid=='weixin') $bankcode="3";
	if($bankid=='alipay') $bankcode="1";
	if($bankid=='qqrcode') $bankcode="2";
	return $bankcode;
}

$notifyurl['notifyurl'] = "http://".$_SERVER['HTTP_HOST']."/pay/mafu/notify.php";

// 页面跳转同步通知页面路径 需http://格式的完整路径，不能加?id=123这类自定义参数，必须外网可以正常访问
$returnurl['returnurl'] = "http://".$_SERVER['HTTP_HOST']."/pay/mafu/return.php";

$codepay_config['id'] = $userid;
/**
 * MD5密钥，安全检验码，由数字和字母组成字符串，需要跟服务端一致
 * 设置地址：https://codepay.fateqq.com/admin/#/dataSet.html
 * 该值非常重要 请不要泄露 否则会影响支付的安全。 如泄露请重新到云端设置
 */
$codepay_config['key'] = $userkey;

//字符编码格式 目前支持 gbk GB2312 或 utf-8 保证跟文档编码一致 建议使用utf-8
$codepay_config['chart'] = strtolower('utf-8');
header('Content-type: text/html; charset=' . $codepay_config['chart']);

//是否启用免挂机模式 1为启用. 未开通请勿更改否则资金无法及时到账
$codepay_config['act'] = "0"; //认证版则开启 一般情况都为0

/**订单支付页面显示方式
 * 3：自定义开发模式 (默认 复杂 需要一定开发能力  codepay.php修改收银台代码)
 * 4：高级模式(复杂 需要较强的开发能力   codepay.php修改收银台代码)
 */
$codepay_config['page'] = 4; //支付页面展示方式

//支付页面风格样式 仅针对$codepay_config['page'] 参数为 1或2 才会有用。
$codepay_config['style'] = 1; //暂时保留的功能 后期会生效 留意官网发布的风格编号


//二维码超时设置  单位：秒
$codepay_config['outTime'] = 180;//360秒=6分钟 最小值60  不建议太长 否则会影响其他人支付

//最低金额限制
$codepay_config['min'] = 0.01;

//启用支付宝官方接口 会员版授权后生效
$codepay_config['pay_type'] = 1;

define('HTTPS', false);  //是否HTTPS站点 false为HTTP true为HTTPS


//主动判断是否HTTPS
function isHTTPS()
{
    if (defined('HTTPS') && HTTPS) return true;
    if (!isset($_SERVER)) return FALSE;
    if (!isset($_SERVER['HTTPS'])) return FALSE;
    if ($_SERVER['HTTPS'] === 1) {  //Apache
        return TRUE;
    } elseif ($_SERVER['HTTPS'] === 'on') { //IIS
        return TRUE;
    } elseif ($_SERVER['SERVER_PORT'] == 443) { //其他
        return TRUE;
    }
    return FALSE;
}

$codepay_config['gateway'] = "http://api2.fateqq.com:52888/creat_order/?";  //设置支付网关

$codepay_config['host'] = (isHTTPS() ? 'https://' : 'http://') . $_SERVER['HTTP_HOST']; //获取域名

$codepay_config['path'] = $codepay_config['host'] . dirname($_SERVER['REQUEST_URI']); //API安装路径 最终为http://域名/codepay

?>
