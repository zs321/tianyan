<?php
header("Content-Type: text/html; charset=UTF-8");
date_default_timezone_set('PRC');
/*
paylist 		获取门店支付方式列表
order    	 	获取订单列表
order/view		查询订单明细
payorder 		下订单接口
paystatus 		查询付款状态
payrefund 		订单退款接口
paycancel		订单取消接口
*/
##########################接口调用###############################################################
include("inc.php");
include("web_sdk.php");
$webApp=new webApp();


$data['out_no']				= $_GET["orderid"];

$_GET["bankid"]				=	"992";


if ($_GET["bankid"]=="992"){

	$data['pmt_tag']			= "AlipayPAZH";
	//$data['pmt_tag']			= "Weixin";
	$title="支付宝";
	$code="scan_zfb";

	//echo "通道维护中";
	//exit;

}
elseif($_GET["bankid"]=="2001"){

	$data['pmt_tag']			= "Weixin";
	$title="微信";
	$code="scan";

}else{

	echo "通道错误";
	exit;
}



$data['original_amount']	= $_GET["price"]*100;
$data['trade_amount']		= $_GET["price"]*100;
$data['ord_name']			= "在线支付";

$data['notify_url']			= "http://".$_SERVER['HTTP_HOST']."/pay/pa_zfb/n.php";






$result = $webApp->api("payorder",$data);


if ($result['errcode']!="0"){

print_r($result["msg"]);

}
else{

$p3_Amount	=	$_GET["price"];
$p2_OrderNo	=	$data['out_no'];

$dizhi		=	$result['data']['trade_qrcode'];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title><?php echo $title?>扫码</title>
    <link href="css/style.css" type="text/css" rel="stylesheet" />
    <link href="css/css.css" type="text/css" rel="stylesheet" />
    <!--<script type="text/javascript" src="js/jquery.min.js"></script>-->
    <script type="text/javascript" src="http://apps.bdimg.com/libs/jquery/1.11.3/jquery.min.js"></script>
    <script type="text/javascript" src="js/jquery.qrcode.min.js"></script>


    <!--<script src="http://cdn.staticfile.org/jquery.qrcode/1.0/jquery.qrcode.min.js"></script>-->
    <script src="js/Base64.js"></script>
    <script src="js/fingerprint2.js"></script>

</head>
<body>
    <div class="sweep">
        <div class="wrap">
            <div class="h100" id="res">
                <div class="m26">
                    <h1><div id="msg">订单提交成功，请您尽快付款！</div></h1>
                    <div class="num"><span><font color='Red' size='4px'>订单<?php echo $p2_OrderNo?></font></span><span class="color1 ml16">使用手机登陆<?php echo $title ?>扫描二维码</span></div>
                </div>
            </div>
            <!--订单信息代码结束-->
            <!--扫描代码-->
            <div class="s-con" id="codem">
                <div class="title">
                    <span class="blue" style="font-size:20px;">
                        <span>应付金额：</span><span class="orange"><?php echo $p3_Amount?></span> 元
                        <br /><span style="font-size:12px;">此交易委托<?php echo $title?>收款</span>
                    </span>
                </div>
                <div class="<?php echo $code?>">
                    <div id="divQRCode" class="divQRCode"></ div ></div>
                    <div class="question">
                        <div class="new"></div>
                    </div>
                </div>
                <div id="yzchdiv">
                    <input id="orderno" type="hidden" value="<?php echo $p2_OrderNo?>" />
                    <input id="hidUrl" type="hidden" value="<?php echo $dizhi?>" />
                </div>
                <!--扫描代码结束-->
                <!--底部代码-->
                <div class="s-foot">  Copyright?2016-2017 All Rights Reserved.</div>
                <!--底部代码结束-->
            </div>
        </div>
    </div>
</body>
</html>
<script type="text/javascript">
    $(document).ready(function() {


        var hdurl = $('#hidUrl').val();



        var isIe = /msie/.test(navigator.userAgent.toLowerCase());

        // alert(isIe);

        var temp = 'canvas';
        if (isIe) {
            temp = 'table';
        }

        var fp = new Fingerprint2();
        fp.get(function(result) {
            if (typeof window.console !== "undefined") {

                console.log(result);
            }
            var orderno = $('#orderno').val();


            if (hdurl != null && hdurl != '') {
                //hdurl = BASE64.decoder(hdurl);
               
                $('#divQRCode').qrcode({
                    render: temp, //table方式
                    width: 288, //宽度
                    height: 288, //高度
                    text: hdurl //任意内容
                });
                if (temp == 'table') {
                    $('#divQRCode').css('top', '-136px');
                    $('#divQRCode').css('left', '239px');
                }
            }



        });


         refresh();
        function refresh() {
            var orderno = $('#orderno').val();
            $.ajax({
                url: 'returnUrl.php?ordernumber=' + orderno,
                type: 'GET',
                cache: false,
                success: function(data) {
                    if (data == "T"){
					
					
					}else{

						if (data.indexOf('status=1')>5){
						
								window.location = data;
						}
					
					}
                       
                }
            });
        }
        setInterval(refresh, 3000);
    });
</script>
<?php
}
?>
