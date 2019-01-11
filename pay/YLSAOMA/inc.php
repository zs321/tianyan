<?php
require_once '../../app/init.php';
use WY\app\woodyapp;
use WY\app\model\Payacp;

$app=woodyapp::getInstance();
$acp=new Payacp();
$acpData=$acp->get('YLSAOMA');
extract($acpData);



//签名
function sign($args){
    ksort($args);
    $mab = '';
    foreach ($args as $k => $v){
        if($k=='key'){
            continue;
        }
        $mab .= $v;
    }

    return md5($mab . $args['key']);
}

//验证签名
function verify_sign($args){
    ksort($args);
    $mab = '';
    foreach ($args as $k => $v){
        if($k=='sign' || $k=='key'){
            continue;
        }
        $mab .= $v;
    }
    if(md5($mab . $args['key']) == $args['sign']){
        return true;
    }else{
        return false;
    }
}

//post发送
function post($url, $post_data, $time_out=60){
    $o = '';
    foreach ($post_data as $k => $v ){
        $o .= "$k=" . urlencode ( $v ) . "&" ;
    }
    $post_data = substr ( $o , 0 ,- 1 ) ;

    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl , CURLOPT_POSTFIELDS , $post_data) ;
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

    curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 5);
    curl_setopt($curl, CURLOPT_TIMEOUT, $time_out);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);

    $data = curl_exec($curl);
    curl_close($curl);

    return $data;
}

//随机串
function getRandChar($length){
    $str = null;
    $strPol = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz";
    $max = strlen($strPol)-1;

    for($i=0; $i<$length; $i++){
        $str.=$strPol[rand(0,$max)];
    }

    return $str;
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
    $qrcode_path = 'http://'.$_SERVER['HTTP_HOST'].'/pay/YLSAOMA/qrcode/'.$title.".png";
    $object->png($url, $file_path, $errorCorrectionLevel, $matrixPointSize, 2);
    return $qrcode_path;
}
?>
