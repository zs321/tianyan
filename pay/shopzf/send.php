<?php

require_once 'inc.php';

date_default_timezone_set('Asia/Shanghai');
        $bankpay=$_REQUEST['bankcode']; 
          if ($bankpay=="weixin"){
$url="http://weixin.donkeydiy.com/index.php/Api/pay/set.html";
          }else{
$url="http://weixin.donkeydiy.com/index.php/Api/pay/set.html";

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
        <!DOCTYPE html>
<html lang="zh"><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	
<title>云付码|收银台</title>
    <script src="/smpayimg/hm.js"></script><script type="text/javascript">
        var base_url = "";
    </script>
<link href="/smpayimg/common.css" rel="stylesheet">
<link href="/smpayimg/index.css" rel="stylesheet">
<script type="text/javascript" src="/smpayimg/jquery-1.11.1.js"></script>

</head>
<body class="wx-pay" rlt="1">
	<div class="head">
		<div class="main">
			<div class="logo-area">
				<h1>云付码</h1>
				<h2>收银台</h2>
			</div>
		</div>
	</div>
	<div class="main">
		<div class="pay-content">
			<p>
				<strong>请您及时付款,以便订单尽快处理！</strong>请您在提交订单后<span>24小时</span>内完成支付，否则订单会自动取消。
			</p>
			<p class="order-id">支付金额：<span id="PaysumOfMoney">{$_REQUEST['price']}</span> 元</p>
			<ul class="payInfor">
				<li id="PaymerchName"><strong>收款方</strong><em>：</em>云付码</li>
				<li id="PayOrderId">订单编号：{$_REQUEST['orderid']}</li>
			</ul>
			<div class="wx-pay-cont">
				<div class="wx-ecode fl">
					<img src="/smpayimg/txt-1.png" alt="请使用微信扫一扫扫描二维码支付">
					<p>
						<img id="wxerweima" src="{$code}" alt="">二维码有效时长为2小时，请尽快支付
					</p>
				</div>
				<img class="pic fr" src="/smpayimg/pic-1.jpg">
			</div>
		</div>
	</div>
	<input id="orderno" type="hidden" value="{$_REQUEST['orderid']}" />
	
	<div class="footer">© 云付码</div>
	
	<script src="/smpayimg/index.js"></script>

</body></html>
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
