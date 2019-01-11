<?php
require_once 'inc.php';

$sdorderno=$_REQUEST['orderid'];
$total_fee=$_REQUEST['price']*100;


error_reporting(0);
header("Content-type: text/html; charset=utf-8");
require_once 'AESHelper.php';
require_once 'PostHelper.php'; 
require_once 'config.php'; 
 
$timestamp= time(); //Unix时间戳

$out_no = $sdorderno;    //开发者流水号

$pmt_tag="WeixinOL"; //付款方式编号WeixinOL（h5支付）    
$ord_name="pay"; //订单名称
$original_amount=$total_fee; //原始交易金额（以分为单位，没有小数点）
$trade_amount=$total_fee; //实际交易金额（以分为单位，没有小数点）

$notify_url='http://'.$_SERVER['HTTP_HOST'].'/pay/pingan/notify.php';//异步通知地址

$trade_type="MWEB";

function getIp(){
    if (getenv("HTTP_CLIENT_IP") && strcasecmp(getenv("HTTP_CLIENT_IP"), "unknown"))
    $ip = getenv("HTTP_CLIENT_IP");
    else if (getenv("HTTP_X_FORWARDED_FOR") && strcasecmp(getenv("HTTP_X_FORWARDED_FOR"), "unknown"))
    $ip = getenv("HTTP_X_FORWARDED_FOR");
    else if (getenv("REMOTE_ADDR") && strcasecmp(getenv("REMOTE_ADDR"), "unknown"))
    $ip = getenv("REMOTE_ADDR");
    else if (isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], "unknown"))
    $ip = $_SERVER['REMOTE_ADDR'];
    else
    $ip = "unknown";
    return($ip);
}

$spbill_create_ip=getIp();
 
$info = array( 
    "h5_info" =>  array(
    
       "type"=>"WAP",
       "wap_url"=>"http://www.sdjzsx.com",
       "wap_name"=>"pay"
    )
  
);
$scene_info=json_encode($info);
 
 
 
$native = array( 
    "out_no" => $out_no,
    "pmt_tag" => $pmt_tag, 
    "ord_name" => $ord_name,
    "original_amount" => $original_amount, 
    "trade_amount" => $trade_amount, 
    "notify_url"=>$notify_url,
    "trade_type"=>$trade_type,
    "spbill_create_ip"=>$spbill_create_ip,
    "scene_info"=>$scene_info 
);


//转json
$AESstr=json_encode($native);
 

//AES加密
$data=encrypt_pass($AESstr,$open_key); 
  

$signstr= "data=".$data."&open_id=".$open_id."&open_key=".$open_key."&timestamp=".$timestamp;

$signstr=sha1($signstr,false);
$sign=md5($signstr,false);
 
$postparam=array(
    "open_id" => $open_id,
    "timestamp" => $timestamp,
    "data" => $data,
    "sign" => $sign 
); 

//post提交
$returndata=post_curls($tjurl,$postparam);

 

function replace_unicode_escape_sequence($match) {   return mb_convert_encoding(pack('H*', $match[1]), 'UTF-8', 'UCS-2BE'); }  



//判断是否成功
if(strpos($returndata,'"errcode":0')===false){
     
     $str = preg_replace_callback('/\\\\u([0-9a-f]{4})/i', 'replace_unicode_escape_sequence', $returndata);
     exit($str);
}
 
//返回参数
$back=json_decode($returndata, true);



//获取data参数
$backdata=$back["data"];

//解密
$backdata=decrypt_pass($backdata,$open_key);
 

$backdata=json_decode($backdata, true); 
$trade_result=$backdata["trade_result"];
$trade_result=json_decode($trade_result, true);

$mweb_url=$trade_result["mweb_url"]."&redirect_url=".urlencode('http://'.$_SERVER['HTTP_HOST'].'/pay/pingan/return.php');
/*1.需对redirect_url进行urlencode处理
2.由于设置redirect_url后,回跳指定页面的操作可能发生在：1,微信支付中间页调起微信收银台后超过5秒 2,用户点击“取消支付“或支付完成后点“完成”按钮。因此无法保证页面回跳时，支付流程已结束，所以商户设置的redirect_url地址不能自动执行查单操作，应让用户去点击按钮触发查单操作。
*/
 
echo " <script   language = 'javascript'  
type = 'text/javascript' > ";  
echo " window.location.href = '$mweb_url' ";  
echo " </script > "; 

 
?>
