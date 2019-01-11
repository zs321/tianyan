<?php
header("content-type:application/json; charset=utf-8");
require_once 'inc.php';

$sdorderno=$_REQUEST['orderid'];
$total_fee=$_REQUEST['price'];

$notify_url='http://'.$_SERVER['HTTP_HOST'].'/pay/zhzfpay/notify.php';//异步通知地址
$return_url='http://'.$_SERVER['HTTP_HOST'].'/pay/zhzfpay/return.php';//异步通知地址

require __DIR__ .DIRECTORY_SEPARATOR.'PayLib.php';

file_put_contents('debug.log', PHP_EOL.str_repeat('=', 80).PHP_EOL, FILE_APPEND);

// AES 秘钥
$base64edAESKey = 'v3fotW5LXw5AED5QxRswBQ==';
//===========================================================================
// rsa sha1 签名私钥
$private_key = 'MIICeAIBADANBgkqhkiG9w0BAQEFAASCAmIwggJeAgEAAoGBAOTYIqkjyCrIHdIeOAvTwaggG6mAhXU6byrW5SIqAXE3znaiBeOeDVNWJzs/pQtXuTn6fB1LoU3Q93hPcLkh7kdoH3+BJDzoPWZ5tPyzgua2nad9xMNNphfRYDVTiEoAxOnFc3aNI22gse+wPS0Ll29/LGp+z3e/p+e1cRP/ibFJAgMBAAECgYEA3pVbISisiPAcEUNTQC23LtAMF9Hp/RvZBNIADDrPLFAbgUgWck5Ip8YkYnyFC4NHphz8m4H0Yrvd+CdMfMWD/BkPRf3eafhnJlHGKyGqsAXLmGh/mvJbleE3NH9LS1N/0+pPam58mAjvkujxoPQ0v5BxHyS7r14lBMkvxiXN9AECQQD8B2zTpvsXDWJFwjKYmKRkWCs3JOaOJmWX6MTY3qPSE6mFW/93blDAs1kEioB01ZsbKiE3fIubZVcFEzI90nCXAkEA6HMxd+GYWA7+UdeOklhz/XhBdtlsOeHZDG8glOFhsHJguURcnov2TG4G5L1t+qdnpZzTeNKVrSyT2ECE4gVJHwJAVwiZZF39x/AvR7fQkTHlU2G/SsPLert3ygXwNJRuLlXr7MngZvYJnQJSc2cBBVfewHrEDc1MyNUuP+ppJ0BM8QJBALdi6gwiNwaCDbKT1S8wCZJXZY5WSkQAIjTlF1dd2KxUEGsZu9h5o3747wdXS4UMvYCzEUOpH9zX5mwdurh2YxECQQDuPsVpoJlevwbIuRymGzvYvVZvDP2N+O4rN0lrJnlhTXkYdsRLSw92QcBX0jRqjwl/LwEMPt8EaK25xJ6rEc07';
//===========================================================================
// rsa sha1 验签公钥
$public_key = 'MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQCfRRqiTyiDRvgPwAnHm+odB6kEY1O51Zh5rlr3iSYEgDKfO00yD6ZCAh6MlKfYT0DD+WKN91lt6t9g/u0Cw2WJwGeUiOEWUDso/MiOGmdGYrfsarEzGCTSRmu1tIdwFKNi9HThcMTs7aU99lBtoGIYu2mxsXoWnLbdExZ9TaOBgwIDAQAB';
//===========================================================================
//商户号
$mchId = '1000000000000020';
//节点号
$nodeId = '10000012';
// 支付网关 URL
$gateway = 'https://120.78.196.14/testPay';
//===========================================================================
//转换 key 格式
$private_key = "-----BEGIN RSA PRIVATE KEY-----\n".wordwrap($private_key, 64, "\n", TRUE)."\n-----END RSA PRIVATE KEY-----";
$public_key  = "-----BEGIN PUBLIC KEY-----\n".wordwrap($public_key, 64, "\n", TRUE)."\n-----END PUBLIC KEY-----";
//===========================================================================

//订单日期
$orderTime = date('YmdHis');

//请求表单初始化
$form = [
	'version'    => '1.0',
	'nodeId'     => $nodeId,
	'orgId'      => $mchId,
	'orderTime'  => $orderTime,
	'txnType'    => 'T20302',
	'signType'   => 'RSA',
	'charset'    => 'UTF-8',
	'bizContext' => '',
	'sign'       => '',
	'reserve1'   => '',
];
// 业务参数，不同类型有差异，需对应修改
$bizContext = [
	'outTradeNo'  => $sdorderno,
	'totalAmount' => $total_fee, //限额 150~20000
	'currency'    => 'CNY',
	'body'        => 'pay',
	'detail'      => 'pay',
	'pageUrl'     => $return_url,
	'notifyUrl'   => $notify_url,
	'orgCreateIp' => $_SERVER['REMOTE_ADDR'],
	'deviceInfo'  => '',
	'appName'     => '',
	'appId'       => '',
	'feeRate'     => '0.5', //根据需求修改
	'dfFee'       => '2',
	'reserve1'    => ''
];

// 1. 业务参数 json 编码
$bizContextJson = json_encode($bizContext);

file_put_contents('debug.log', 'origin bizContext:'.PHP_EOL.$bizContextJson.PHP_EOL, FILE_APPEND);

// 2. 业务参数签名
$bizContextSign = PayLib::rsaSHA1Sign($bizContextJson, $private_key);
// 3. 业务参数加密
$bizContextAESEncrypt = PayLib::AESEncrypt($bizContextJson, $base64edAESKey);

// 4. 回填表单
$form['sign']       = $bizContextSign;
$form['bizContext'] = $bizContextAESEncrypt;

file_put_contents('debug.log', 'request form:'.PHP_EOL.json_encode($form).PHP_EOL, FILE_APPEND);

// 5. 发送请求
$response = PayLib::postForm($gateway, $form);
file_put_contents('debug.log', 'response body:'.PHP_EOL.$response.PHP_EOL, FILE_APPEND);

// 解析响应 json
$response = json_decode($response, TRUE);

// 业务参数解密
$bizContextAESDecrypt = PayLib::AESDecrypt($response['bizContext'], $base64edAESKey);
file_put_contents('debug.log', 'bizContext AES Decrypt:'.PHP_EOL.$bizContextAESDecrypt.PHP_EOL, FILE_APPEND);
$r=json_decode($bizContextAESDecrypt,true);
if(!empty($r['payUrl'])){
	header("location:".$r['payUrl']);die;
}else{
	echo $bizContextAESDecrypt;
}

// 验签
$verify = PayLib::rsaSHA1Verify($bizContextAESDecrypt, $response['sign'], $public_key);

file_put_contents('debug.log', 'verify result:'.PHP_EOL.$verify.PHP_EOL, FILE_APPEND);
