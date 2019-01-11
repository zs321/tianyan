<?php
require_once 'inc.php';
require_once 'yeepayCommon.php';
//require_once 'yeepayCommon.php';
use WY\app\model\Handleorder;


			$partner		=	$p1_MerId;  //商户号
            $key			=	$merchantKey;		//MD5密钥，安全检验码
			
            $orderstatus = $_GET["orderstatus"]; // 支付状态
            $ordernumber = $_GET["ordernumber"]; // 订单号
            $paymoney = $_GET["paymoney"]; //付款金额
            $sign = $_GET["sign"];	//字符加密串
            $attach = "NETAPI";	//订单描述
           $signSource = sprintf("partner=%s&ordernumber=%s&orderstatus=%s&paymoney=%s%s", $partner, $ordernumber, $orderstatus, $paymoney, $key); //连接字符串加密处理
           
		   if ($sign == md5($signSource))//签名正确
            {


				//if ($orderstatus==1){

				
				$handle=@new Handleorder($ordernumber,$paymoney);
				$handle->updateUncard();
				
				//}

			 echo "ok";
            }
			
			else {
			//验证失败
			echo "签名验证失败";
			echo $sign;
			echo "  ";
			echo md5($signSource);
			echo $signSource;
			}
?>
