<?php
        require_once 'inc.php';

 $Token['merchantNo'] =  $userid;
  $Token['key'] =  $userkey;
  $Token['nonce'] =  md5(time());
  $Token['timestamp'] =  intval(time());
$Token['sign'] = md5("merchantNo=".$userid."&nonce=".$Token['nonce']."&timestamp=".$Token['timestamp']."&key=".$userkey);


 $accessToken=vpost("https://api.xjockj.com/open/v1/getAccessToken/merchant", $Token);

print_r( $accessToken);

     /*   $REMARK =  $_REQUEST['REMARK'];
        $ORDERID =  $_REQUEST['ORDERID'];
        $MONEY =  (INT)$_REQUEST["PRICE"];
        $BANKID =  GETBANKCODE($_REQUEST['BANKCODE']);

        $PAYDATA['ACCESSTOKEN'] =  $ACCESSTOKEN;
        $PAYDATA['OUTTRADENO'] = $ORDERID;
        $PAYDATA['MONEY'] = $MONEY;
        $PAYDATA['TYPE'] = "T0";
        $PAYDATA['BODY'] =  "BODY";
		$PAYDATA['DETAIL'] = "DETAIL";
        $PAYDATA['NOTIFYURL'] = "HTTP://".$_SERVER['HTTP_HOST']."/PAY/DALIPAY/NOTIFY.PHP";
        $PAYDATA['PRODUCTID'] =  1;
        $PAYDATA['SUCCESSURL'] = "";

       $PAYDATA = JSON_ENCODE($PAYDATA);
       PRINT_R( $PAYDATA);

       $URL = "HTTPS://API.XJOCKJ.COM/OPEN/V1/ORDER/ALIPAYWAPPAY";
       $HTTPSTR = VPOST($URL, $PAYDATA);

       $RS = JSON_DECODE($HTTPSTR,TRUE);
     PRINT_R($RS);*/
      
       



?>
