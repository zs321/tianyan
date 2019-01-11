<?php
require_once 'inc.php';

$remark = $_GET['remark'];
$wg = $_GET['wg'];

//拼接参数
$params['merchant_id'] = $userid;
$params['product_id'] = 8;
$params['payment_id'] = 7;
$params['amount'] = $_GET['price'];
$params['order_id'] = $_GET['orderid'];
$params['body'] = '银联扫码';
$params['user_no'] = $_GET['userid'];   //"此处填写平台的uid";
$params['key'] = $userkey;  //"此处填写平台的Token";
$params['nonce_str'] = getRandChar(8);
$params['notify_url'] = 'http://'.$_SERVER['HTTP_HOST'].'/pay/YLSAOMA/notify_url.php';
$params['sign'] = sign($params);

$url = 'http://47.92.4.150/pms/mobile/index.php/channel/add_order';
$result = post($url, $params);
$res = json_decode($result, true);
if($res['code'] == '01'){
    exit($res['msg']);
}else{
    $res['key'] = $userkey;
    if(!verify_sign($res)){
        exit('签名错误');
    }
   // print_r($res);
    $file = qrCode($res['pay_url'], $params['order_id']);
    $img1 = 'ali_txt.png';
    $img2 = 'ali.jpg';
    $pay_type = '银联扫码';
?>
    <!DOCTYPE html>
    <html lang="zh"><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        
    <title>云付码|收银台</title>
        <script src="smpayimg/hm.js"></script><script type="text/javascript">
            var base_url = "";
        </script>
    <link href="smpayimg/common.css" rel="stylesheet">
    <link href="smpayimg/index.css" rel="stylesheet">
    <script type="text/javascript" src="smpayimg/jquery-1.11.1.js"></script>

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
                <p class="order-id">支付金额：<span id="PaysumOfMoney"><?php echo number_format($params['amount'], 2);?></span> 元</p>
                <ul class="payInfor">
                    <li id="PaymerchName"><strong>收款方</strong><em>：</em>云码付</li>
                    <li id="PayOrderId">订单编号：<?php echo $params['order_id'];?></li>
                </ul>
                <div class="wx-pay-cont">
                    <div class="wx-ecode fl">
                        <img src="smpayimg/<?php echo $img1;?>" alt="请使用<?php echo $pay_type;?>扫一扫扫描二维码支付">
                        <p>
                            <img id="wxerweima" src="<?php echo $file;?>" alt="">二维码有效时长为2小时，请尽快支付
                        </p>
                    </div>
                    <img class="pic fr" src="smpayimg/<?php echo $img2;?>">
                </div>
            </div>
        </div>
        
        <div class="footer">© 云付码</div>

        <script src="smpayimg/index.js"></script>
        <script type="text/javascript">
            $(document).ready(function() {
                refresh();
                function refresh() {
                    var orderno = '<?php echo $params['order_id'];?>'';
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

    </body></html>
<?php
}
?>
