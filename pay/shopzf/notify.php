<?php
/*
 * 异步回调通知页面 (需商户在下单请求中传递Notify_url)
 * 2017-08-06
 * https://www.ispay.cn
 */
require_once 'inc.php';
use WY\app\model\Handleorder;
date_default_timezone_set('PRC');
        $data["attach"]=$_REQUEST['attach']; 
        $data["err_code"]=$_REQUEST['err_code']; 
        $data["err_code_des"]=$_REQUEST['err_code_des']; 
        $data["out_trade_no"]=$_REQUEST['out_trade_no']; 
        $data["result_code"]=$_REQUEST['result_code']; 
        $data["return_code"]=$_REQUEST['return_code'];
        $data["return_msg"]=$_REQUEST['return_msg'];
        $data["total_fee"]=$_REQUEST['total_fee'];
        $data["time_end"]=$_REQUEST['time_end'];
        $data["trade_type"]=$_REQUEST['trade_type'];
        $data["transaction_id"]=$_REQUEST['transaction_id'];
        $mysign = createSign($data);

        $sign=$_REQUEST['sign']; 
 //file_put_contents("Logs/out_trade_no.log",$data["out_trade_no"].'-'.$sign);

     if ($data["return_code"]=="SUCCESS" && $mysign == $sign){
         $handle=@new Handleorder($data["out_trade_no"],$data["total_fee"]);
         $handle->updateUncard();
	  // file_put_contents("Logs/notify.log",$mysign.'-'.$sign);
	     echo "SUCCESS";
		 exit;
     }
	
		
?>


