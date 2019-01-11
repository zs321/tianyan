<?php

header("Content-type: text/html; charset=utf-8"); 
require('class/config.php');
require('class/RequestHandler.class.php');
require('class/ClientResponseHandler.class.php');
require('class/Utils.class.php');
require('class/Aes.php');
class Demo{
    private $cfg        = null;
    private $reqHandler = null;
    private $resHandler = null;
    private $Aes        = null;

    public function __construct(){
        $this->Request();
    }

    private function Request(){
        $this->cfg        = new Config();
        $this->reqHandler = new RequestHandler();
        $this->resHandler = new ClientResponseHandler();
        $this->Aes        = new AES();
    }

    public function index(){
        $method = isset($_REQUEST['method'])?$_REQUEST['method']:'kjBindCard';
        switch($method){
            /*====================快捷支付接口开始=====================*/
            case 'kjBindCard'://快捷支付绑卡
                $this->kjBindCard();
            break;
            case 'kjPayOrder'://快捷支付下单
                $this->kjPayOrder();
            break;
            case 'kjPaySubmit'://快捷支付提交
                $this->kjPaySubmit();
            break;
            case 'kjNotifyUrl'://快捷回调通知
                $this->kjNotifyUrl();
            break;
            case 'kjOrderQry'://快捷订单查询
                $this->kjOrderQry();
            break;
            /*====================快捷支付接口结束=====================*/
        }
    }

    //快捷--支付绑卡
    public function kjBindCard(){
        $this->resHandler->setParameter('sp_id',$this->cfg->C('SP_ID'));//系统机构号
        $this->resHandler->setParameter('mch_id',$this->cfg->C('MCH_ID'));//系统商户号
        $this->resHandler->setParameter('acc_name',$_GET['uname']);//持卡人姓名
        $this->resHandler->setParameter('acc_no',$_GET['card']);//卡号
        $this->resHandler->setParameter('mobile',$_GET['phone']);//预留手机号
        $this->resHandler->setParameter('id_no',$_GET['id']);//证件号码
        $this->resHandler->setParameter('expire_date','');//信用卡有效期 借记卡可不填
        $this->resHandler->setParameter('cvv','');//cvv 借记卡可不填
        $this->resHandler->setParameter('nonce_str',time());//随机字符串
        $this->resHandler->setKey($this->cfg->C('SP_KEY'));//秘钥
        $this->resHandler->setParameter('sign',$this->resHandler->szSign());//签名
        Utils::dataRecodes(__LINE__.' '.__FUNCTION__.' 参数发送',$this->resHandler->getAllParameters());

        $this->reqHandler->doPost($this->cfg->C('BINDCARD_URL'), $this->resHandler->getAllParameters(),'Array');
        Utils::dataRecodes(__LINE__.' '.__FUNCTION__.' 结果接收',$this->reqHandler->getAllParameters());
        if ($this->reqHandler->getParameter('status') == 'SUCCESS') {
			
			 //echo json_encode($this->reqHandler->getAllParameters());exit;
			$this->kjPayOrder($_GET['amt'],$_GET['oid'],$_GET['card']);
           
        }else{
			
				$ret = $this->reqHandler->getAllParameters();
				if($ret['message'] == '卡片已绑定')
				{
					$this->kjPayOrder($_GET['amt'],$_GET['oid'],$_GET['card']);
				}
			
			// echo json_encode($this->reqHandler->getAllParameters());exit;
            //echo json_encode($this->reqHandler->getParameter('message'),JSON_UNESCAPED_UNICODE);exit;
        }
    }

    //快捷--支付下单
    public function kjPayOrder($amt,$oid,$card){
		
		//echo $amt;
		
		$url = "http://".$_SERVER['HTTP_HOST']."/pay/kjpay_kjpay/notifyUrl.php?amt=".$amt;
		
        $out_trade_no = mt_rand(time(),time()+rand());
        $this->resHandler->setSession('kj_out_trade_no',$oid);//设置缓存

        $this->resHandler->setParameter('sp_id',$this->cfg->C('SP_ID'));//系统机构号
        $this->resHandler->setParameter('mch_id',$this->cfg->C('MCH_ID'));//系统商户号
        $this->resHandler->setParameter('out_trade_no',$oid);//订单号
        $this->resHandler->setParameter('total_fee',$amt*100);//总金额
        $this->resHandler->setParameter('body','快捷支付');//商品名称
        $this->resHandler->setParameter('notify_url',$url);//通知地址
        $this->resHandler->setParameter('acc_no',$card);//卡号
        $this->resHandler->setParameter('nonce_str',time());//随机字符串
        $this->resHandler->setKey($this->cfg->C('SP_KEY'));//秘钥
        $this->resHandler->setParameter('sign',$this->resHandler->szSign());//签名
        Utils::dataRecodes(__LINE__.' '.__FUNCTION__.' 参数发送',$this->resHandler->getAllParameters());

        $this->reqHandler->doPost($this->cfg->C('PAYORDER_URL'), $this->resHandler->getAllParameters(),'Array');
        Utils::dataRecodes(__LINE__.' '.__FUNCTION__.' 结果接收',$this->reqHandler->getAllParameters());
        if ($this->reqHandler->getParameter('status') == 'SUCCESS') {
           
                //result_code:SUCCESS 表示成功 , FAIL 表示失败
				
				
				echo "<form action='http://pay.云付码.com/kj/OpenDemo.php?method=kjPaySubmit' method='get'>
						<input type='hidden' name='oid' value='{$oid}'>
						<input type='hidden' name='method' value='kjPaySubmit'>
						验证码:<input type='text' name='sid' value=''>
						<input type='submit' value='提交'></form>";
				
				
				
				exit();
				
				
                //echo json_encode($this->reqHandler->getAllParameters());exit;
           
		   
        }else{
			
			
			 echo json_encode($this->reqHandler->getAllParameters());exit;
            echo json_encode($this->reqHandler->getParameter('message'));exit;
        }
    }

    //快捷--支付提交
    public function kjPaySubmit(){
		
		//var_dump($_GET);exit();
		
		
        $this->resHandler->setParameter('sp_id',$this->cfg->C('SP_ID'));//系统机构号
        $this->resHandler->setParameter('mch_id',$this->cfg->C('MCH_ID'));//系统商户号
        $this->resHandler->setParameter('out_trade_no',$_GET['oid']);//订单号 获取缓存
        $this->resHandler->setParameter('password',$_GET['sid']);//银行发送的动态口令
        $this->resHandler->setParameter('nonce_str',time());//随机字符串
        $this->resHandler->setKey($this->cfg->C('SP_KEY'));//秘钥
        $this->resHandler->setParameter('sign',$this->resHandler->szSign());//签名
        Utils::dataRecodes(__LINE__.' '.__FUNCTION__.' 参数发送',$this->resHandler->getAllParameters());

        $this->reqHandler->doPost($this->cfg->C('PAYSUBMIT_URL'), $this->resHandler->getAllParameters(),'Array');
        Utils::dataRecodes(__LINE__.' '.__FUNCTION__.' 结果接收',$this->reqHandler->getAllParameters());
        if ($this->reqHandler->getParameter('status') == 'SUCCESS') {
            echo "支付成功";
        }else{
            echo json_encode($this->reqHandler->getParameter('message'),JSON_UNESCAPED_UNICODE);exit;
        }
    }

    //快捷支付回调接口
    public function kjNotifyUrl(){
        $str = json_encode($_POST);
        Utils::dataRecodes(__LINE__.' '.__FUNCTION__.' 接口回调收到通知参数',$str);
        $this->reqHandler->setJson($str);
        $this->reqHandler->setKey($this->cfg->C('KEY_ALI'));
        if ($this->reqHandler->isTenpaySign()) {
            //do something...
            echo 'SUCCESS';exit;
        }else{
            echo 'FAIL';exit;
        }
    }

    //快捷订单查询
    public function kjOrderQry(){
        $this->resHandler->setParameter('sp_id',$this->cfg->C('SP_ID'));//系统机构号
        $this->resHandler->setParameter('mch_id',$this->cfg->C('MCH_ID'));//系统商户号
        $this->resHandler->setParameter('out_trade_no','123123123');//订单号 获取缓存
        $this->resHandler->setParameter('nonce_str',time());//随机字符串
        $this->resHandler->setKey($this->cfg->C('SP_KEY'));//秘钥
        $this->resHandler->setParameter('sign',$this->resHandler->szSign());//签名
        Utils::dataRecodes(__LINE__.' '.__FUNCTION__.' 参数发送',$this->resHandler->getAllParameters());

        $this->reqHandler->doPost($this->cfg->C('ORDERQRY_URL'), $this->resHandler->getAllParameters(),'Array');
        Utils::dataRecodes(__LINE__.' '.__FUNCTION__.' 结果接收',$this->reqHandler->getAllParameters());
        if ($this->reqHandler->getParameter('status') == 'SUCCESS') {
            $this->reqHandler->setKey($this->cfg->C('SP_KEY'));//秘钥
            if ($this->reqHandler->isTenpaySign()) {//验签
                echo json_encode($this->reqHandler->getAllParameters(),JSON_UNESCAPED_UNICODE);exit;
            }else{
                echo '签名失败';exit;
            }
        }else{
            echo json_encode($this->reqHandler->getParameter('message'),JSON_UNESCAPED_UNICODE);exit;
        }
    }
}
$demo = new Demo();
$demo->index();