<?php
require_once 'inc.php';
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/1/13
 * Time: 10:34
 */



//$dt = "1547435158";  //支付时间
//$mark = '12'; // ordermodel.BillNO;备注
//$money = '￥1.96';  //支付金额
//$no = '20181217200040011100580038909049';  //支付宝订单号
//$type = 'alipay'; //支付方式 alipay
//$account = '2897483365@qq.com'; //支付宝账号
//$sign = 'ce36a2121dab284e92fcb8a9bbd3a6d0'; //md5

/*// $arr0 = array (
  // 'key' => '123456',
  // 'money' => '2.11',
  // 't' => '2018-11-21 17:35:11',
  // 'b' => '12',
  // 'o' => '20181121200040011100060014908400',
  // 'zfb' => '2897483365@qq.com',账号类型，1-微信，2-支付宝type
// );*/
//url = model3.GateUrl.Replace("Api/pay/set.html", "Api/Client/aliApi") + "?key=123456&money=" +
//    money.Replace("￥", "") + "&t=" + DateTime.Now.AddSeconds(5).ToString("yyyy-MM-dd HH:mm:ss") + "&b=" + mark +
//    "&o=" + no + "&zfb=" + account;
$dt = $_REQUEST['dt'];  //支付时间
$mark = $_REQUEST['mark']; // ordermodel.BillNO;备注
$money = $_REQUEST['money'];  //支付金额
$no = $_REQUEST['no'];  //支付宝订单号
$type = $_REQUEST['type']; //支付方式 alipay
$account = $_REQUEST['account']; //支付宝账号
$sign = $_REQUEST['sign']; //md5
//此处需要存储收款助手发送过来的数据      防止收款助手重复发送数据引起重复请求

$Payacp = new \WY\app\model\Payacp();
if($Payacp->mobileData($no)){
    echo 'SUCCESS';
    exit;
}
$mobile['no'] = $no;
$mobile['dt'] = $dt;
$mobile['mark'] = $mark;
$mobile['money'] = str_replace("￥","",$money);
$mobile['type'] = $type;
$mobile['account'] = $account;
$mobile['sign'] = $sign;
$Payacp->MobileDataAdd($mobile);
if((substr($mark,0,1) == 'G' && substr($mark,-1,1) == 'G' && strlen($mark) <= 12) || strlen($mark) <= 10){
    $url = "ai.1899pay.com/Api/Client/aliApi";
    $get_data['key'] = '123456';
    $get_data['money'] = str_replace("￥","",$money);
    $get_data['t'] = date("Y-m-d%20H:i:s");
    $get_data['b'] = $mark;
    $get_data['o'] = $no;
    $get_data['zfb'] = $account;
    $res = httpGet($url,$get_data);
    dump($res,1);
}









