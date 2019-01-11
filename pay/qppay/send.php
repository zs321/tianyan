<?php
require_once 'inc.php';

$remark = $_GET['remark'];
$wg = $_GET['wg'];

//拼接参数
if($wg == 'alipaywap'){
	$str = '支付宝H5';
}elseif($wg == 'alipay'){
	$str = '支付宝扫码';
}elseif($wg == 'weixin'){
	$str = '微信扫码';
}else{
	$str = '';
}
$params['price'] = $_GET['price'];
$params['istype'] = $wg == 'weixin' ? 2 : 1;
$params['orderuid'] = $_GET['orderid'];
$params['goodsname'] = $str;
$params['orderid'] = $_GET['orderid'];
$params['uid'] = $userid;   //"此处填写平台的uid";
$params['token'] = $userkey;  //"此处填写平台的Token";
$params['return_url'] = 'http://'.$_SERVER['HTTP_HOST'].'/pay/qppay/return_url.php';
$params['notify_url'] = 'http://'.$_SERVER['HTTP_HOST'].'/pay/qppay/notify_url.php';
$params['key'] = md5($params['goodsname']. $params['istype'] . $params['notify_url'] . $params['orderid'] . $params['orderuid'] . $params['price'] . $params['return_url'] . $params['token'] . $params['uid']);

$url = 'https://qq.hbqiche.net/pay?format=json';
unset($params['token']);
$result = post($url, $params);
$res = json_decode($result, true);
if($res['code'] == 0){
    exit($res['msg']);
}else{
    if ($_GET['bankcode'] == 'alipaywap') {
        header('location:'.$res['data']['qrcode']);
    }
    $file = qrCode($res['data']['qrcode'], $params['orderid']);
    if($wg == 'weixin'){
        $img1 = 'wx_txt.png';
		$img2 = 'wx.jpg';
        $pay_type = '微信';
    }else{
        $img1 = 'ali_txt.png';
        $img2 = 'ali.jpg';
        $pay_type = '支付宝';
	}
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
                    <strong>请您及时付款,以便订单尽快处理！</strong>请您在提交订单后<span>5分钟</span>内完成支付，否则订单会自动取消。
                </p>
                <p class="order-id">支付金额：<span id="PaysumOfMoney"><?php echo number_format($params['price'], 2);?></span> 元</p>
                <ul class="payInfor">
                    <li id="PaymerchName"><strong>收款方</strong><em>：</em>云码付</li>
                    <li id="PayOrderId">订单编号：<?php echo $params['orderuid'];?></li>
                </ul>
                <div class="wx-pay-cont">
                    <div class="wx-ecode fl">
                        <img src="smpayimg/<?php echo $img1;?>" alt="请使用<?php echo $pay_type;?>扫一扫扫描二维码支付">
                        <p>
                            <img id="wxerweima" src="<?php echo $file;?>" alt="">二维码有效时长为5分钟，请尽快支付
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
                    var orderno = '<?php echo $params['orderid'];?>';
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

/**
 * http 协议post请求
 * @param $url 请求链接
 * @param array $param 参数
 * @param array $secret 密钥
 * @return mixed
 * @throws Exception
 */
function post($url, $param = array()){
    $httph = curl_init($url);
    curl_setopt($httph, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($httph, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($httph,CURLOPT_RETURNTRANSFER,1);
    curl_setopt($httph, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.0)');
    curl_setopt($httph, CURLOPT_POST, 1);//设置为POST方式
    curl_setopt($httph, CURLOPT_POSTFIELDS, $param);
    curl_setopt($httph, CURLOPT_RETURNTRANSFER,1);
    curl_setopt($httph, CURLOPT_HEADER,0);
    $rst = curl_exec($httph);
    curl_close($httph);
    if($rst){
        ob_clean();
        return $rst;
    }else{
        exit('服务器请深求失败');
    }
}

/**
 * 生成支付二维码
 * @param string $data
 * @param string $title
 */
function qrCode($data = '', $title = ''){
    require_once './phpqrcode/phpqrcode.php';
    //生成二维码图片
    $object = new \QRcode();
    $url=$data;
    $level=3;
    $size=10;
    $errorCorrectionLevel =intval($level) ;//容错级别
    $matrixPointSize = intval($size);//生成图片大小
    $file_path = './qrcode/'.$title.".png";
    $qrcode_path = 'http://'.$_SERVER['HTTP_HOST'].'/pay/qppay/qrcode/'.$title.".png";
    $object->png($url, $file_path, $errorCorrectionLevel, $matrixPointSize, 2);
    return $qrcode_path;
}
?>
