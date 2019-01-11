<?php

require_once("inc.php"); //导入配置文件
use WY\app\model\Handleorder;
header("Content-Type: text/html; charset=UTF-8");

$codepay_key = $codepay_config['key']; //这是您的密钥


ksort($_REQUEST); //排序post参数
reset($_REQUEST); //内部指针指向数组中的第一个元素

$sign = ''; //加密字符串初始化
 echo "success";
foreach ($_REQUEST AS $key => $val) {
    if ($val == '' || $key == 'sign') continue; //跳过这些不签名
    if ($sign) $sign .= '&'; //第一个字符串签名不加& 其他加&连接起来参数
    $sign .= "$key=$val"; //拼接为url参数形式
}
$pay_id = $_REQUEST['pay_id']; //需要充值的ID 或订单号 或用户名
$money = (float)$_REQUEST['money']; //实际付款金额
$price = (float)$_REQUEST['price']; //订单的原价
$param = $_REQUEST['param']; //自定义参数
$type = (int)$_REQUEST['type']; //支付方式
$pay_no = $_REQUEST['pay_no'];//流水号
$pay_time = (int)$data['pay_time']; //付款时间戳

  if ($money <= 0 || empty($pay_id) || empty($pay_no)) {
        die('缺少必要的一些参数');
    }

if (!$_REQUEST['pay_no'] || md5($sign . $codepay_key) != $_REQUEST['sign']) { //不合法的数据
 
    die('支付失败');
  
} else { 

  $handle=@new Handleorder($pay_id,$money);
  $handle->updateUncard();


}

?>



