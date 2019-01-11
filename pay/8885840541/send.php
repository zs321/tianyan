<?php

    require_once 'inc.php';
    $orderid=$_GET['orderid'];
    $price=$_GET['price'];


	$data['mchtNo']='888584054110506';
    $data['orderNo']=$orderid;
    $data['amount']=$price*100;
    $data['notifyUrl']='http://'.$_SERVER['HTTP_HOST'].'/pay/8885840541/notifyurl.php';;
    $data['payWay']='qq';
    $data['payType']='scanpay';
    $data['body']='pay';
    $data['redirectUrl']='http://'.$_SERVER['HTTP_HOST'].'/pay/8885840541/returnurl.php';;
    $data['settleType']='2';
    ksort($data);
    reset($data);
    $md5str = "";
    foreach ($data as $key => $val) {
        $md5str = strtolower($md5str . $key . "=" . $val . "&");
    } 
    $key='1318A8EB7DFAD92C9AF5CAE71360F870';
    $md5str .= "key=" . $key;
    $data['sign']=strtoupper(md5($md5str)); 
    $url='http://159h1552q8.imwork.net/NewPay/qhsl-gsyf/pay';
    $header[]='Content-Type: application/json; charset=utf-8';
    $header[]='Content-Length:' . strlen(json_encode($data));
    $res=curl_get_https($url, $data,$header); 
	$res=json_decode($res);
	//var_dump($res);
	//$res->respCode='00000';
	//$res->data->qrCodeUrl='https://qpay.qq.com/qr/5fa90408';
	if($res->respCode=='00000'){
		$url=$res->data->qrCodeUrl;
	}else{
		echo $res->respMsg;die;
	}
	include "phpqrcode.php";
	$qr_eclevel = 'H';//容错级别  
	$picsize = 11;//生成图片大小
	$margin = 1;
	QRcode::png($url, './images/QQ.png', $qr_eclevel, $picsize, $margin);//生成二维码图
	
    
    function curl_get_https($url, $data=array(), $header=array(), $timeout=30){ 
        $ch = curl_init(); 
        $data = json_encode( $data );
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // 跳过证书检查 
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2); // 从证书中检查SSL加密算法是否存在 
        curl_setopt($ch, CURLOPT_URL, $url); 
        curl_setopt($curl, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header); 
        curl_setopt($ch, CURLOPT_POST, true); 
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data); 
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
        curl_setopt($ch, CURLOPT_TIMEOUT, $timeout); 
    
        $response = curl_exec($ch); 
    
        if($error=curl_error($ch)){ 
            die($error); 
        } 
    
        curl_close($ch); 
    
        return $response; 
    }
?>




<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<!-- saved from url=(0135)https://www.fengfaka.com/pay/weixin_bank/send_weixin.php?price=1.00&orderid=865BDC3FEFD5E9F3&payorderid=20180324092049794349&pid=WEIXIN -->
<html xmlns="http://www.w3.org/1999/xhtml"><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>扫码支付</title>

<link rel="stylesheet" type="text/css" href="./index_files/comm.css">

<link rel="stylesheet" type="text/css" href="./index_files/weixinpay.css">

<style type="text/css"></style>
</head>
<body>
<input name="hidShopID" type="hidden" id="hidShopID" value="151208110318084743">
<input name="hidIsBuyPay" type="hidden" id="hidIsBuyPay" value="0">
<div class="wx_header">
<div class="wx_logo">
<span class="mab10 STYLE1"><span class="STYLE3">本交易委托本网站代收款|应付金额：<span class="STYLE2">￥</span></span><span class="STYLE2"><?php echo $_POST['amount']; ?>元</span></span>
</div>
</div>
<div class="weixin">
<div class="weixin2">
<b class="wx_box_corner left pngFix"></b><b class="wx_box_corner right pngFix"></b>
<div class="wx_box pngFix">
<div class="wx_box_area">
<div class="pay_box qr_default">
<div class="area_bd">
<span class="wx_img_wrapper" id="qr_box">
<img id="ImgPic" alt="模式二扫码支付" src="./images/QQ.png" border="0" height="310" width="310">
<img style="left: 50%; opacity: 0; display: none; margin-left: -101px;" class="guide pngFix" src="./index_files/qqwebpay_guide.png" alt="" id="guide">
</span>
<div class="msg_default_box1">
<i class="icon60_qr pngFix"></i>
<p>
请使用手机
<br>
扫一扫完成支付
</p>
</div>
<div style="margin:10px auto; width:260px;  height:70px; background-color:#900">
<p>&nbsp;
</p>
<p class="STYLE1">
<a href="<?php echo $data['redirectUrl'].'?orderid='.$orderid; ?>">
<span style="color:#FFF">支付完成 点击跳转</span>
</a>
</p>
<p>&nbsp;
</p>
</div>
<div class="msg_box">
<i class="icon_wx pngFix"></i>
<p>
<strong>扫描成功</strong>请在手机确认支付
</p>
</div>
</div>
</div>
</div>
<div class="wx_hd">
<div class="wx_hd_img icon_wx"></div>
</div>
<div class="wx_money">
<span>￥</span><?php echo $price; ?> </div>
<div class="wx_pay">
<p>
</p>
</div>
<div class="wx_kf">
<div class="wx_kf_img icon_wx"></div>
<div class="wx_kf_wz">
<p>
</p>
</div>
</div>
</div>
</div>
</div>
<div class="g-copyrightCon">
</div>


</body></html>