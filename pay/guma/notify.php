<?php
require_once 'inc.php';
use WY\app\model\Handleorder;
function keep_array($content,$path = "aa.txt"){
    $fp = fopen($path,'a+');
    fwrite($fp,var_export($content,true));
    fclose($fp);
}
//ÑéÇ©keyÃ»¼Ó
//$_POST = array (
//   'attach' => '',
//   'err_code' => '',
//   'err_code_des' => '',
//   'out_trade_no' => '2019011411042043297',
//   'result_code' => 'SUCCESS',
//   'return_code' => 'SUCCESS',
//   'return_msg' => '',
//   'sign' => '1EACCA9A5E43F3D414374A680D202FE0',
//   'time_end' => '20190114110541',
//   'total_fee' => '1.96',
//   'trade_type' => 'ALIPAYPC',
//   'transaction_id' => '20190114110539459004',
// );
$return_data = $_POST;
extract($return_data);
$mySign = createSign($return_data);
file_put_contents("notify.log",$return_data['sign'].'-'.$sign);
if($return_data['sign'] == $mySign){
    if($return_data['return_code'] == "SUCCESS"){
        echo 'success';
        $handle=@new Handleorder($return_data['out_trade_no'],$return_data['total_fee']);
        $handle->updateUncard();
        file_put_contents("ok.log","success");
    } else {
        echo 'fail';
    }
}else {
    echo 'signerr';
}





















?>
