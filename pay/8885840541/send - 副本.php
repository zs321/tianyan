<?php

    require_once 'inc.php';
    $orderid=$_GET['orderid'];
    $price=$_GET['price'];


	$pay_memberid = "10199";   //商户ID
	$pay_orderid = $orderid;    //订单号
	$pay_amount = $price;    //交易金额
	$pay_applydate = date("Y-m-d H:i:s");  //订单时间
	$pay_bankcode = "ALIPAY";   //银行编码
	$pay_notifyurl = 'http://'.$_SERVER['HTTP_HOST'].'/pay/10199_ftzfbsm/notifyUrl.php';;   //服务端返回地址
	$pay_callbackurl = 'http://'.$_SERVER['HTTP_HOST'].'/pay/10199_ftzfbsm/returnUrl.php';  //页面跳转返回地址
	
	$Md5key = "smt4QEQ8C2e9gQTSg47KgZlRQXShqu";   //密钥
	
	$tjurl = "http://paygo.weifenbang.com/Pay_Index.html";   //提交地址
	
	$requestarray = array(
            "pay_memberid" => $pay_memberid,
            "pay_orderid" => $pay_orderid,
            "pay_amount" => $pay_amount,
            "pay_applydate" => $pay_applydate,
            "pay_bankcode" => $pay_bankcode,
            "pay_notifyurl" => $pay_notifyurl,
            "pay_callbackurl" => $pay_callbackurl
        );
		
	    ksort($requestarray);
        reset($requestarray);
        $md5str = "";
        foreach ($requestarray as $key => $val) {
            $md5str = $md5str . $key . "=>" . $val . "&";
        }
		//echo($md5str . "key=" . $Md5key."<br>");
        $sign = strtoupper(md5($md5str . "key=" . $Md5key)); 
		$requestarray["pay_md5sign"] = $sign;
		$requestarray["tongdao"] = "AlipayWap";//银行编码
        $str = '<form id="Form1" name="Form1" method="post" action="' . $tjurl . '">';
        foreach ($requestarray as $key => $val) {
            $str = $str . '<input type="hidden" name="' . $key . '" value="' . $val . '">';
        }
		//$str = $str . '<input type="submit" value="ok" style="width:100px; height:50px;">';
        $str = $str . '</form>';
        $str = $str . '<script>';
        $str = $str . 'document.Form1.submit();';
        $str = $str . '</script>';
        exit($str);
?>