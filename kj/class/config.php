<?php
// define('API_URL','http://testapi.yuemanbank.com:89/');//测试环境地址
define('API_REAL_URL','http://testapi.yuemanbank.com:90/');//测试环境地址
class Config{
    private $cfg = array(
        'BINDCARD_URL'  =>'http://testapi.yuemanbank.com:90/kakaloan/api/quickpay/bindCard',//快捷支付绑卡
        'PAYORDER_URL'  =>'http://testapi.yuemanbank.com:90/kakaloan/api/quickpay/order',//快捷支付下单
        'PAYSUBMIT_URL' =>'http://testapi.yuemanbank.com:90/kakaloan/api/quickpay/submit',//快捷支付提交
        'ORDERQRY_URL'  =>'http://testapi.yuemanbank.com:90/kakaloan/api/quickpay/ordertype',//快捷订单查询

        'AES_KEY'		=>'8A571A1B84B096EE388F8810628215D9',//AES加密秘钥

        'SP_ID'			=>'1010',//系统机构号
        'MCH_ID'        =>'101000000000000',//系统商户号
        'SP_KEY'		=>'B18D359B4BFB8E490E9EE8880907C96A',//系统秘钥

        'MCHT_NO_ALIS'	=>'1000000000004',//支付宝主扫商户号
        'KEY_ALI'		=>'AA903757E315443B839B3F3F5E3082A5',//支付宝主扫秘钥

        'MCHT_NO_WXS'	=>'1000000000004',//微信主扫商户号
        'KEY_WXS'		=>'AA903757E315443B839B3F3F5E3082A5',//微信主扫秘钥

        'MCHT_NO_WXJ'	=>'1000000000001',//微信公众号商户号
        'KEY_WXJ'		=>'44BB9E262CCF5956134E18262B17845C',//微信公众号秘钥

        'ORDER_TYPE'	=>array('2'=>'支付中','3'=>'支付成功','4'=>'支付失败'),
       );
    
    public function C($cfgName){
        return $this->cfg[$cfgName];
    }
}
?>