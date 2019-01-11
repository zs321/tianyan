<?php
require_once '../../app/init.php';
header("Content-type: text/html; charset=utf-8");
use WY\app\woodyapp;
use WY\app\model\Payacp;

$app=woodyapp::getInstance();
$acp=new Payacp();
$acpData=$acp->get('shanyun');


extract($acpData);

	

	$config = array(
			'merchant_id'		=>		$userid,		//商户号，请至闪云支付后台查看
			'secretKey'			=>		$userkey,	//secretKey，请至闪云支付后台查看
			'gateway'			=>		'https://pay.3394.net/cashier',		//支付网关，请勿修改
			'notify_url'		=>		"http://".$_SERVER['HTTP_HOST']."/pay/shanyun/notify.php",//异步回调地址，请配置外网能访问的地址
			'return_url'		=>		"http://".$_SERVER['HTTP_HOST']."/pay/shanyun/return.php"//同步回调地址，请配置外网能访问的地址
			);


	/*
	*计算签名函数
	*/
	function getParamString($params)
	{
		//空值不参与签名
	    foreach ($params as $key => $value) {
	        if (is_null($value) || $value == "") {
	            unset($params[$key]);
	        }
	    }
	    //计算签名（sign、title,params,secretKey不参加）
	    $paramsSign = array();
	    foreach ($params as $key => $value) {
	        if ($key != 'sign' && $key != 'title' && $key != 'params' && $key != 'secretKey') {
	            $paramsSign[$key] = $value;
	        }
	    }

	    //按照key排序
	    ksort($paramsSign);

	    $r = http_build_query($paramsSign) . '&' . $params['secretKey'];

	    //加密获取sign
	    $sign           = strtoupper(md5(urldecode($r))); //对该字符串进行 MD5 计算，得到签名，并转换大写
	    //设置请求参数
	    $params['sign'] = $sign;
	    unset($params['secretKey']);

	    return $params;
	}

?>
