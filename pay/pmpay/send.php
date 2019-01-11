<?php

require_once 'inc.php';

$remark = $_GET['remark'];
$wg = $_GET['wg'];

if($wg == 'alipay' || $wg =='alipaywap'){
	$pay_code="911";
}else{
	$pay_code="0";
	
	echo "通道代码有错".$wg;
}

error_reporting(0);
header("Content-type: text/html; charset=utf-8");
$pay_memberid = $userid;  //商户ID
$pay_orderid = $_GET['orderid'];   //订单号
$pay_amount = $_GET['price'];    //注意后面的交易金额可进行交易，其他一概不行。1,100,150,200,250,300,350,400,450,500,550,600,650,700,750,800,850,900,950,1000,1500,2000,2500,3000，3500,4000,4500,5000,10000,20000,30000,40000,50000
$pay_applydate = date("Y-m-d H:i:s");  //订单时间
$pay_notifyurl = 'http://'.$_SERVER['HTTP_HOST'].'/pay/pmpay/notify_url.php';  //服务端返回地址
$pay_callbackurl = 'http://'.$_SERVER['HTTP_HOST'].'/pay/pmpay/return_url.php'; //页面跳转返回地址（PC端二维码支付后跳转，移动端暂时没有作用）

$pay_bankcode = $pay_code;   //银行编码  911固定:

if($pay_amount % 50!=0||$pay_amount==50){
	
	exit("请提交金额为50的倍数的金额，如(100、150、200、250....,800,850,900,950,1000)等等,单笔最高为3000元!");
}



$native = array(
    "pay_memberid" => $pay_memberid,
    "pay_orderid" => $pay_orderid,
    "pay_amount" => $pay_amount,
    "pay_applydate" => $pay_applydate,
    "pay_bankcode" => $pay_bankcode,
    "pay_notifyurl" => $pay_notifyurl,
    "pay_callbackurl" => $pay_callbackurl,
);
ksort($native);
$md5str = "";
foreach ($native as $key => $val) {
    $md5str = $md5str . $key . "=" . $val . "&";
}
$Md5key = $userkey;//密钥
$tjurl = 'http://www.xundingtec.com/Pay_Index.html';
//echo($md5str . "key=" . $Md5key);
$sign = strtoupper(md5($md5str . "key=" . $Md5key));
$native["pay_md5sign"] = $sign; //签名值
$native['pay_attach'] = $_GET['remark']; //附加信息
$native['pay_productname'] ='充值';
?>

<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>支付</title>
    <!-- Bootstrap -->
    <link rel="stylesheet" href="https://cdn.bootcss.com/bootstrap/3.3.7/css/bootstrap.min.css"
          integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <!--[if lt IE 9]>
    <script src="https://cdn.bootcss.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://cdn.bootcss.com/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body onLoad="document.pay.submit()">

            <form name="pay"  method="post" action="<?php echo $tjurl; ?>">
                <?php
                foreach ($native as $key => $val) {
                    echo '<input type="hidden" name="' . $key . '" value="' . $val . '">';
                }
                ?>
              
            </form>

</body>
</html>
