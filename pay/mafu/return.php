<?php

require_once("inc.php"); //导入配置文件



$codepay_key = $codepay_config['key']; //这是您的密钥

$isPost = true; //默认为POST传入

if (empty($_POST)) { //如果GET访问
    $_POST = $_GET;  //POST访问 为服务器或软件异步通知  不需要返回HTML
    $isPost = false; //标记为GET访问  需要返回HTML给用户
}
ksort($_POST); //排序post参数
reset($_POST); //内部指针指向数组中的第一个元素

$sign = ''; //加密字符串初始化

foreach ($_POST AS $key => $val) {
    if ($val == '' || $key == 'sign') continue; //跳过这些不签名
    if ($sign) $sign .= '&'; //第一个字符串签名不加& 其他加&连接起来参数
    $sign .= "$key=$val"; //拼接为url参数形式
}
$pay_id = $_POST['pay_id']; //需要充值的ID 或订单号 或用户名
$money = (float)$_POST['money']; //实际付款金额
$price = (float)$_POST['price']; //订单的原价
$param = $_POST['param']; //自定义参数
$type = (int)$_POST['type']; //支付方式
$pay_no = $_POST['pay_no'];//流水号
$pay_time = (int)$data['pay_time']; //付款时间戳

  if ($money <= 0 || empty($pay_id) || $pay_time <= 0 || empty($pay_no)) {
        die('缺少必要的一些参数');
    }
	
if (!$_POST['pay_no'] || md5($sign . $codepay_key) != $_POST['sign']) { //不合法的数据
    if ($isPost) exit('fail');  //返回失败 继续补单
    die('支付失败');
  
} else { 

  $handle=@new Handleorder($pay_id,$money);
  $handle->updateUncard();

}
$return_url = $_SERVER["SERVER_PORT"] == '80' ? '/' : '//'.$_SERVER['SERVER_NAME'];
?>





?>
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta http-equiv="Content-Language" content="zh-cn">
    <meta name="apple-mobile-web-app-capable" content="no"/>
    <meta name="apple-touch-fullscreen" content="yes"/>
    <meta name="format-detection" content="telephone=no,email=no"/>
    <meta name="apple-mobile-web-app-status-bar-style" content="white">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <title>支付详情</title>
    <link href="css/wechat_pay.css" rel="stylesheet" media="screen">
    <link rel="stylesheet" type="text/css" media="screen" href="css/font-awesome.min.css">
    <style>
        .text-success {
            color: #468847;
            font-size: 2.33333333em;
        }

        .text-fail {
            color: #ff0c13;
            font-size: 2.33333333em;
        }

        .text-center {
            text-align: center;
        }

        .text-left {
            text-align: left;
        }

        .error {

            display: block;
            padding: 9.5px;
            margin: 0 0 10px;
            font-size: 13px;
            line-height: 1.42857143;
            color: #333;
            word-break: break-all;
            word-wrap: break-word;
            background-color: #f5f5f5;
            border: 1px solid #ccc;
            border-radius: 4px;

        }
    </style>
</head>

<body>
<div class="body">
    <h1 class="mod-title">
        <span class="ico_log ico-<?php echo (int)$_GET['type'] ?>"></span>
    </h1>

    <div class="mod-ct">
        <div class="order">
        </div>
        <div class="amount" id="money">￥<?php echo (float)$_GET["money"]; ?></div>
        <h1 class="text-center text-<?php echo($result != '充值成功' ? 'fail' : 'success'); ?>"><strong><i
                    class="fa fa-check fa-lg"></i> <?php echo $result; ?></strong></h1>
        <?php echo($error_msg ? "以下错误信息关闭调试模式可隐藏：<div class='error text-left'>{$error_msg}</div>" : ''); ?>
        <div class="detail detail-open" id="orderDetail" style="display: block;">
            <dl class="detail-ct" id="desc">
                <dt>金额</dt>
                <dd><?php echo (float)$_GET["money"] ?></dd>
                <dt>商户订单：</dt>
                <dd><?php echo htmlentities($_GET["pay_id"]) ?></dd>
                <dt>流水号：</dt>
                <dd><?php echo htmlentities($_GET["pay_no"]) ?></dd>
                <dt>付款时间：</dt>
                <dd><?php echo date("Y-m-d H:i:s", (int)$_GET["pay_time"]) ?></dd>
                <dt>状态</dt>
                <dd><?php echo $result; ?></dd>
            </dl>


        </div>

        <div class="tip-text">
        </div>


    </div>
    <div class="foot">
        <div class="inner">
            <p>如未到账请联系我们</p>
        </div>
    </div>

</div>
<div class="copyRight">
    <p>支付合作：<a href="http://codepay.fateqq.com/" target="_blank">码支付</a></p>
</div>
<script>
    setTimeout(function () {
        //这里可以写一些后续的业务
        window.location.href = '<?php echo $return_url?>';
    }, 3000);//3秒后跳转
</script>
</body>
</html>



