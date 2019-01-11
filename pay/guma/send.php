<?php
require_once 'inc.php';
/*
$_REQUEST:Array
(
    [wg] => gumajierugetway001
    [orderid] => 2018120715075384368
    [price] => 2.00
    [bankcode] => gumagetway
    [remark] =>
    [userid] => 10000
)
$param = array (
'ali_account' => '17172607760@163.com', //接入商账号
'attach' => '', //附加数据
'body' => '购买商品-在线支付',   //写死
'net_gate_url' => 'http://api6.1899pay.com:81',   //如果没有固码 请求手机的地址组成   不知道啥规则  先写死
'notice_url' => 'http://api.1899pay.com//Services/txqpc/callback.aspx',   //异步返回地址
'out_trade_no' => 'OD181122170449000',   //平台生成订单号   有没有特定生成规则
'return_url' => 'http://api.1899pay.com//Services/txqpc/jump.aspx',   //不知道有啥用  先写死
'sign' => '7402EF6CC2D4B297AE038BE929D9B5ED',   //用于对接固码平台验证
'total_fee' => '0.02', //金额
'trade_type' => 'ALIPAYPC', //支付终端

);
Array
(
    [id] => 69
    [code] => guma
    [name] => 固码
    [email] => 2897483365@qq.com
    [userid] =>
    [userkey] => 企业
    [lasttime] =>
)
*/

$submit['ali_account'] = $email;
$submit['attach'] = $_REQUEST['remark'];
$submit['body'] = '购买商品-在线支付';
$submit['net_gate_url'] = 'http://api6.1899pay.com:81';
$submit['notice_url'] = 'http://'.$_SERVER['HTTP_HOST'].'/pay/guma/notify.php';
$submit['out_trade_no'] = $_REQUEST['orderid'];
$submit['return_url'] = 'http://'.$_SERVER['HTTP_HOST'].'/pay/guma/return.php';
$submit['total_fee'] = number_format($_REQUEST['price'],2,".","");
$submit['trade_type'] = 'ALIPAYPC';   //从demo程序传过来的
$submit['sign'] = createSign($submit);

?>
<!doctype html>
<html xmlns="http://www.w3.org/1999/html">
<head>
    <meta charset="utf8">
    <title>正在转到付款页</title>
</head>

<!--<body onLoad="document.pay.submit()">-->
</body>


    <form name="pay" action="http://ai.1899pay.com/index.php/Api/Pay/set" method="post">
        ali_account:<input type="text" name="ali_account" value="<?php echo $submit['ali_account']?>"><br />
        attach:<input type="text" name="attach" value="<?php echo $submit['attach']?>"><br />
        body:<input type="text" name="body" value="<?php echo $submit['body']?>"><br />
        net_gate_url:<input type="text" name="net_gate_url" value="<?php echo $submit['net_gate_url']?>"><br />
        notice_url:<input type="text" name="notice_url" value="<?php echo $submit['notice_url']?>"><br />
        out_trade_no:<input type="text" name="out_trade_no" value="<?php echo $submit['out_trade_no']?>"><br />
        return_url:<input type="text" name="return_url" value="<?php echo $submit['return_url']?>"><br />
        sign: <input type="text" name="sign" value="<?php echo $submit['sign']?>"><br />
        total_fee:<input type="text" name="total_fee" value="<?php echo $submit['total_fee']?>"><br />
        trade_type: <input type="text" name="trade_type" value="<?php echo $submit['trade_type']?>"><br />
    <button type="submit" onclick="document.pay.submit()">提交</button>
</form>

</body>
</html>
