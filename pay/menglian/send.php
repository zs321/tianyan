<?php
require_once 'inc.php';
$customerid=$userid;
$orderid=$_REQUEST['orderid'];
$wg=$_REQUEST['wg'];
//$yh=rand(98000,98999)/100000;
//$total_fee=$_REQUEST['price']*100;

/*if ($_REQUEST['price']>99 && $_REQUEST['price']<1001 ){
	
$total_fee=$_REQUEST['price']*100 - rand(5,10)*10;
	
} elseif( $_REQUEST['price']>1000 && $_REQUEST['price']<5001){
	
	$total_fee=$_REQUEST['price']*100 - rand(10,8)*10;
}*/

if($_REQUEST['price']<100){
	$yh=0;
}elseif($_REQUEST['price']>=100 && $_REQUEST['price']<=1000){
	$yh=rand(110,500);
}elseif($_REQUEST['price']>=1000 && $_REQUEST['price']<=3000){
	$yh=rand(210,999);
}
$total_fee=$_REQUEST['price']*100-$yh;


if($wg=='alipaywap'){
	$paytype=clearxss($_REQUEST['bankcode']);
	
		$url = 'http://www.hbgdjc.com/api/v1/orders';
		$apikey = 'e618d6dd4d2539de1e77d98f3fcdfadd7016ff89';
		$memberId = '11039';
		$data = [
			'userId' => $memberId,
			'sdorderNo' => $orderid,
			'totalFee'=>$total_fee,
			'gatType' => 'alipay',
			'notifyUrl' => 'http://'.$_SERVER['HTTP_HOST'].'/pay/menglian/notify.php',
			'remarks' => '在线支付',
	
		];
		
		$data['sign'] = getSign($data,$apikey);
	
	
		$response = json_decode(toSendUrl($data,$url));
		if($response->code=='success'){
			header("location:".$response->data->url);
		}else{
			echo $response->msg;	
		}
}
	
	function getSign($data,$apiKey){
        foreach ($data as $key => $value) {
            if ($value == '' or $key == 'sign' or $key == 'SIGN') {
                unset($data[$key]);
            }
        }
        ksort($data);
        $post_url = '';
        foreach ($data as $key=>$value){
            $post_url .= $key.'='.$value.'&';
        }
        $stringSignTemp = $post_url.'key='.$apiKey;

        $sign = md5($stringSignTemp);

        return $sign;
    }

    function toSendUrl($data,$url ,$method = 'post',$timeOut = 30){
            $ch = curl_init ();
            curl_setopt ( $ch, CURLOPT_TIMEOUT, $timeOut);
            curl_setopt ( $ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.110 JDB/1.0' );
            curl_setopt ( $ch, CURLOPT_ENCODING, 'UTF-8' );
            curl_setopt ( $ch, CURLOPT_MAXREDIRS, 3 );
            curl_setopt ( $ch, CURLOPT_HEADER, false);
            curl_setopt ( $ch, CURLOPT_FOLLOWLOCATION, true );
            curl_setopt ( $ch, CURLOPT_NOSIGNAL, true );
            curl_setopt ( $ch, CURLOPT_MAXREDIRS, 3 );
            curl_setopt ( $ch, CURLOPT_MAXREDIRS, 3 );
            curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
            curl_setopt ( $ch, CURLOPT_SSL_VERIFYHOST, false );
            curl_setopt ( $ch, CURLOPT_SSL_VERIFYPEER, false);
            if($method == 'post'){
                curl_setopt( $ch, CURLOPT_POST, 1 );
                curl_setopt ( $ch, CURLOPT_URL, $url );
                curl_setopt ( $ch, CURLOPT_POSTFIELDS, http_build_query($data));
            }else{
                curl_setopt ( $ch, CURLOPT_URL, $data);
            }
            $res = curl_exec ( $ch );
            curl_close ( $ch );
            return $res;
        }
	
	
	
	
function clearxss($tempurl)
{
	if($tempurl)
	{
		$tempurl=str_replace("<","",$tempurl);
		$tempurl=str_replace(">","",$tempurl);
		$tempurl=str_replace("\"","",$tempurl);
		$tempurl=str_replace("\'","",$tempurl);
		$tempurl=str_replace(";","",$tempurl);
		$tempurl=str_replace("(","",$tempurl);
		$tempurl=str_replace(")","",$tempurl);
		$tempurl=str_replace(" ","",$tempurl);
	}
	return 	$tempurl;
}


?>
