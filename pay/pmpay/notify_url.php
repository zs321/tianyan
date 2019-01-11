<?php
use WY\app\model\Handleorder;
require_once 'inc.php';
	$param="";
   $status=$_POST["returncode"];
   if($status!="11")
   {
	   
	   file_put_contents("success.txt","支付失败\r\n", FILE_APPEND);
	   exit("notify_fail");
   }
   
   $sign = $_POST["sign"];
   $ReturnArray = array( // 返回字段
           "memberid" => $_POST["memberid"], // 商户ID
            "orderid" =>  $_POST["orderid"], // 订单号
            "amount" =>  $_POST["amount"], // 交易金额
            "datetime" =>  $_POST["datetime"], // 交易时间
            "transaction_id" =>  $_POST["transaction_id"], // 支付流水号
            "returncode" => $_POST["returncode"],
    );
	
	ksort($ReturnArray,2);
	reset($ReturnArray);
	 

	foreach ($ReturnArray as $key => $val) {
		 
		$param .= $key . "=" . $val . "&";
	}
$Md5key = $userkey;//密钥
	 $n_sign = strtoupper(md5($param . "key=" . $Md5key));
 
    if($n_sign==$sign)
	{
		file_put_contents("success.txt", $param."支付成功\r\n", FILE_APPEND); 
		
		$handle = new Handleorder($_POST["orderid"], $_POST["amount"]);
    $handle->updateUncard();

		exit("ok");//成功之后必须打印“ok”给我们，此处请注意
	}
	else
	{
		file_put_contents("success.txt", "验签失败\r\n", FILE_APPEND); 
		exit("notify_fail");
	}

	
	
   
 

?>