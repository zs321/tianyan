﻿<?php
require_once 'inc.php';
function Post($url,$post_data){
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_HEADER, 0);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_POST, 1);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $post_data);
    $data = curl_exec($curl);
    curl_close($curl);
    return $data;
}


$submit['ali_account'] = "2897483365@qq.com";
$submit['attach'] = $_REQUEST['remark'];
$submit['body'] = '购买商品-在线支付';
$submit['net_gate_url'] = 'http://api6.1899pay.com:81';
$submit['notice_url'] = 'http://'.$_SERVER['HTTP_HOST'].'/pay/guma/notify.php';
$submit['out_trade_no'] = $_REQUEST['orderid'];
$submit['return_url'] = 'http://'.$_SERVER['HTTP_HOST'].'/pay/guma/return.php';
$submit['total_fee'] = number_format($_REQUEST['price'],2,".","");
$submit['trade_type'] = 'ALIPAYPC';   //从demo程序传过来的
$submit['sign'] = createSign($submit);

$url = "http://ai.1899pay.com/index.php/Api/Pay/set/";
$data = Post($url,$submit);



isset($data["pay_url"])?$payUrl = $data["pay_url"]:$payUrl = false;

isset($data["kouling"])?$kouling = $data["kouling"]: $kouling = false;

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
        var payurl = 'alipays://platformapi/startapp?appId=20000067&backBehavior=pop&url=http%3a%2f%2fapi.abcapi.cn%2fServices%2ftzzfb%2fWebForm2.aspx%3fparm%3d2088331522142414%2ck190112141270000%2c1.00';
        var uaa = navigator.userAgent;
        var isiOS = !!uaa.match(/\(i[^;]+;( U;)? CPU.+Mac OS X/); //ios终端
        $(function () {
            if (autoRedirectCount > 0) {
                timer = window.setInterval(function () {
                    if (autoRedirectCount == 0) {
                        window.clearInterval(timer);
                        $("#btnpay").html("下一步");
                        // $("#btnpay").attr("class", "bt");
                        // $("#btnpay").removeAttr("disabled");
                        if (isiOS) {
                            //  window.location.href = 'http://api.abcapi.cn/Services/tzzfb/WebForm3.aspx?parm=2088331522142414,k190112141270000,1.00';
                        } else {
                            //  goalipay();
                        }
                    } else {
                        $("#btnpay").html("下一步<span style=\"color:#01AAED\">(" + autoRedirectCount + ")</span>");
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
<form name="form2" method="post" action="WebForm1.aspx" id="form2">
    <div>
        <input type="hidden" name="__VIEWSTATE" id="__VIEWSTATE" value="/wEPDwUKMjAwMDE0MTgyNWRkLP+o0loWO6NQusU8yFBgmMVGPDywFuLirR7kNnNIXk8=" />
    </div>

    <div class="out">
        <div class="ewm" id="qr_container">
        </div>
        <button class="bt grey" id="btnpay" onclick="goalipay()" disabled="disabled" type="button">下一步</button>
        <div class="tstit">如无法调起支付？</div>
        <div class="ts"><span>1</span>请截屏先保存二维码到手机</div>
        <div class="ts"><span>2</span>打开<label id="payType">支付宝</label>，扫一扫本地图片。</div>
    </div>
    <!--http://www.99yida.com/pay/api_grskmpay/4.aspx?id=2088332374174727,201812191602350043,1.00-->
    <script>GetQrCode(<?php echo $payUrl; ?>, 'alipay');</script>
</form>
</body>
</html>






