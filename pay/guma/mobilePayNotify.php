<?php
require_once 'inc.php';
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/1/13
 * Time: 10:34
 */



//$dt = "1547435158";  //֧��ʱ��
//$mark = '12'; // ordermodel.BillNO;��ע
//$money = '��1.96';  //֧�����
//$no = '20181217200040011100580038909049';  //֧����������
//$type = 'alipay'; //֧����ʽ alipay
//$account = '2897483365@qq.com'; //֧�����˺�
//$sign = 'ce36a2121dab284e92fcb8a9bbd3a6d0'; //md5

/*// $arr0 = array (
  // 'key' => '123456',
  // 'money' => '2.11',
  // 't' => '2018-11-21 17:35:11',
  // 'b' => '12',
  // 'o' => '20181121200040011100060014908400',
  // 'zfb' => '2897483365@qq.com',�˺����ͣ�1-΢�ţ�2-֧����type
// );*/
//url = model3.GateUrl.Replace("Api/pay/set.html", "Api/Client/aliApi") + "?key=123456&money=" +
//    money.Replace("��", "") + "&t=" + DateTime.Now.AddSeconds(5).ToString("yyyy-MM-dd HH:mm:ss") + "&b=" + mark +
//    "&o=" + no + "&zfb=" + account;
$dt = $_REQUEST['dt'];  //֧��ʱ��
$mark = $_REQUEST['mark']; // ordermodel.BillNO;��ע
$money = $_REQUEST['money'];  //֧�����
$no = $_REQUEST['no'];  //֧����������
$type = $_REQUEST['type']; //֧����ʽ alipay
$account = $_REQUEST['account']; //֧�����˺�
$sign = $_REQUEST['sign']; //md5
//�˴���Ҫ�洢�տ����ַ��͹���������      ��ֹ�տ������ظ��������������ظ�����

$Payacp = new \WY\app\model\Payacp();
if($Payacp->mobileData($no)){
    echo 'SUCCESS';
    exit;
}
$mobile['no'] = $no;
$mobile['dt'] = $dt;
$mobile['mark'] = $mark;
$mobile['money'] = str_replace("��","",$money);
$mobile['type'] = $type;
$mobile['account'] = $account;
$mobile['sign'] = $sign;
$Payacp->MobileDataAdd($mobile);
if((substr($mark,0,1) == 'G' && substr($mark,-1,1) == 'G' && strlen($mark) <= 12) || strlen($mark) <= 10){
    $url = "ai.1899pay.com/Api/Client/aliApi";
    $get_data['key'] = '123456';
    $get_data['money'] = str_replace("��","",$money);
    $get_data['t'] = date("Y-m-d%20H:i:s");
    $get_data['b'] = $mark;
    $get_data['o'] = $no;
    $get_data['zfb'] = $account;
    $res = httpGet($url,$get_data);
    dump($res,1);
}









