<?php

require_once 'inc.php';

date_default_timezone_set('Asia/Shanghai');
        $bankpay=$_REQUEST['bankcode']; 
          if ($bankpay=="weixin"){
$url="http://weixin.donkeydiy.com/index.php/Api/pay/set.html";
          }else{
$url="http://pay.5aimz.com/index.php/Api/pay/set.html";

          }

        $data["trade_type"]=GetBankCode($bankpay);
        $data["body"]="shopzf";
        $data["attach"]= $_REQUEST['remark'];
        $data["total_fee"]=$_REQUEST['price'];
        $data["return_url"]='http://'.$_SERVER['HTTP_HOST'].'/pay/shopzf/return_url.php';
        $data["notice_url"]='http://'.$_SERVER['HTTP_HOST'].'/pay/shopzf/notify.php';
        $data["out_trade_no"]= $_REQUEST['orderid'];
        $data["sign"] = createSign($data);
        $result = SkillCurl($url,$data);
     
      if($result["return_code"]=="SUCCESS" && $result["trade_type"]=="ALIPAYPC" || $result["trade_type"]=="ALIPAYWAP"){
        $pay_url=$result["pay_url"];

		header('Access-Control-Allow-Origin:*');
	   header ("location:$pay_url");
	   
	   

 
        }
       if($result["return_code"]=="SUCCESS" && $result["trade_type"]=="WECHATNATIVE"){
        $code = qrCode($result["code_url"],"WECHATNATIVE");
       // echo "<img src={$code}>";
        echo <<<EOT
        <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" >
<head>
<title>交易结果页面</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
   <script type="text/javascript" src="http://apps.bdimg.com/libs/jquery/1.11.3/jquery.min.js"></script>
    <script type="text/javascript" src="js/jquery.qrcode.min.js"></script>

    <!--<script src="http://cdn.staticfile.org/jquery.qrcode/1.0/jquery.qrcode.min.js"></script>-->
    <script src="js/Base64.js"></script>
    <script src="js/fingerprint2.js"></script>
 <!--此Js可控制页面自适应手机浏览器 **不可删**-->
    <script type="text/javascript">
        var phoneWidth = parseInt(window.screen.width);
        var phoneScale = phoneWidth/640;
        var ua = navigator.userAgent;
        if (/Android (\d+\.\d+)/.test(ua)){
            var version = parseFloat(RegExp.$1);
            // andriod 2.3
            if(version>2.3){
                document.write('<meta name="viewport" content="width=640, minimum-scale = '+phoneScale+', maximum-scale = '+phoneScale+', target-densitydpi=device-dpi">');
                // andriod 2.3以上
            }else{
                document.write('<meta name="viewport" content="width=640, target-densitydpi=device-dpi">');
            }
            // 其他系统
        } else {
            document.write('<meta name="viewport" content="width=640, user-scalable=no, target-densitydpi=device-dpi">');
        }
    </script>

    <!--此Js使手机浏览器的active为可用状态-->
    <script>
        document.addEventListener("touchstart", function(){}, true);

    </script>
     

    <style type="text/css">
<!--
.STYLE4 {font-size: xx-large}
.STYLE5 {font-size: large}
-->
    </style>
</head>
<body id="weixin" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">

<div style="background-color: white; padding-top:50px; padding-bottom:10px;" align="center">
  <span class="STYLE4">微信支付</span>
  <div class="STYLE5" id="header">商户订单号：{$_REQUEST['orderid']} </div>

</div>
 <input id="orderno" type="hidden" value="{$_REQUEST['orderid']}" />
<div style="margin-top:10px;" align="center">
<img src="{$code}">
</div>
</body>
</html>
      <script type="text/javascript">  
   $(document).ready(function() {
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
        setInterval(refresh, 1000);
    });
</script>
EOT;
        }

     
?>
