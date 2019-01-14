﻿<?php
require_once 'inc.php';

$submit['ali_account'] = $email;  //账号
$submit['attach'] = $_REQUEST['remark'];
$submit['body'] = '购买商品-在线支付';
$submit['net_gate_url'] = $nate_gate_url;  //后台添加
$submit['notice_url'] = 'http://'.$_SERVER['HTTP_HOST'].'/pay/guma/notify.php';  //支付完成之后的异步回调地址
$submit['out_trade_no'] = $_REQUEST['orderid'];
$submit['return_url'] = 'http://'.$_SERVER['HTTP_HOST'].'/pay/guma/return.php'; //暂时没啥用
$submit['total_fee'] = number_format($_REQUEST['price'],2,".","");
$submit['trade_type'] = $_REQUEST['trade_type'];   //从demo程序传过来的
$submit['sign'] = createSign($submit);

$url = "http://ai.1899pay.com/index.php/Api/Pay/set/";

$redis = new Redis();
$redis->connect("127.0.0.1",6379);
$data = Post($url,$submit);

//$data = json_decode($data,true);

$data = array (
    'return_code' => 'SUCCESS',
    'return_msg' => '',
    'trade_type' => 'ALIPAYPC',
    'out_trade_no' => '2019011414124814227',
    'attach' => '',
    'pay_url' => 'https://qr.alipay.com/fkx002078grhvhqhiatd1e7?t=1544695479236',
    'result_code' => 'SUCCESS',
    'err_code' => '',
    'err_code_des' => '',
    'sign' => '097CA4C6B066D115E60FDBAA2C64F92D',
    'bendi' => '1',
);
//支付宝都是pay_url  微信是code_url
if($data["bendi"] == 6){
    //吱口令  加载吱口令的页面
    isset($data["kouling"])?$kouLing = $data["kouling"]:$kouLing = false;
    echo $kouLing;
    die();
}else if($data["bendi"] == 1){
    //本地固码与自动存为固码的数据
    isset($data["pay_url"])?$payUrl = $data["pay_url"]:$payUrl = false;
    $redis->set($submit['out_trade_no'],$payUrl);
}else if($data["bendi"] == 2){
    //自动码
    isset($data["pay_url"])?$payUrl = $data["pay_url"]:$payUrl = false;
    $redis->set($submit['out_trade_no'],$payUrl);
}else if($data["bendi"] == 3){
    //账号状态离线
    echo $data["return_msg"];
    die();
}else if($data["bendi"] == 4){
    //通道终端异常
    echo $data["return_msg"];
    die();
}
$payUrl = "http://".$_SERVER['SERVER_NAME']."/pay/guma/content.php?orderid=".$submit['out_trade_no'];

?>



<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>在线支付
    </title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="format-detection" content="telephone=no" />
    <meta http-equiv="content-type" content="text/html;charset=UTF-8" />
    <link href="css/base.css" rel="stylesheet" />
    <script src="js/jquery-1.8.2.js" type="text/javascript"></script>
    <script src="js/qrcode.js" type="text/javascript"></script>
    <script type="text/javascript">
        var timer;
        var autoRedirectCount = 3;
        var payurl = "<?php echo $payUrl; ?>";
        var uaa = navigator.userAgent;
        var isiOS = !!uaa.match(/\(i[^;]+;( U;)? CPU.+Mac OS X/); //ios终端
        $(function () {
            if (autoRedirectCount > 0) {
                timer = window.setInterval(function () {
                    if (autoRedirectCount == 0) {
                        window.clearInterval(timer);
                        $("#btnpay").html("下一步");
                        $("#btnpay").attr("class", "bt");
                        $("#btnpay").removeAttr("disabled");
                        if (isiOS) {
                            //  window.location.href = 'http://api.abcapi.cn/Services/tzzfb/WebForm3.aspx?parm=2088331522142414,k190112141270000,1.00';
                        } else {
                            //  goalipay();
                        }
                    } else {
                        $("#btnpay").html("下一步<span style='color:#01AAED'>(" + autoRedirectCount + ")</span>");
                    }
                    autoRedirectCount--;
                }, 1000);
            } else {
                // goalipay();
            }
        });
        function GetQrCode(text, type) {
            $('#qr_container').qrcode({
                render: 'canvas',
                text: utf16to8(text),
                height: 216,
                width: 216,
                src: type + '.png'
            });
        }
        function goalipay() {
            if (payurl != "") {
                window.location.href = payurl;
            } else {
                $("#btnpay").hide();
                $(".tstit").hide();
                $("#payType").html("微信");
            }
        }
    </script>
</head>
<body>
    <div class="out">
        <div class="ewm" id="qr_container"></div>
        <button class="bt grey" id="btnpay" onclick="goalipay()" disabled="disabled" type="button">下一步</button>
        <div class="tstit">如无法调起支付？</div>
        <div class="ts"><span>1</span>请截屏先保存二维码到手机</div>
        <div class="ts"><span>2</span>打开<label id="payType">支付宝</label>，扫一扫本地图片。</div>
    </div>
    <script>GetQrCode(payurl, 'alipay');</script>
</form>
</body>
</html>






