<?php
require('class/config.php');
require('class/RequestHandler.class.php');
require('class/ClientResponseHandler.class.php');
require('class/Utils.class.php');
require('class/Aes.php');
header("Content-type: text/html; charset=utf-8");                 
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
        $method = isset($_REQUEST['method'])?$_REQUEST['method']:'dfDistill';
        switch($method){
            /*======================代付接口开始=======================*/
            case 'dfDistill'://提现请求
                $this->dfDistill();
            break;
            case 'dfQry'://提现结果查询
                $this->dfQry();
            break;
            case 'dfBalance'://账户余额查询
                $this->dfBalance();
            break;
            /*======================代付接口结束=======================*/
        }
    }

    //代付--提现请求
    public function dfDistill(){
        $this->resHandler->setParameter('sp_id',$this->cfg->C('SP_ID'));//系统机构号
        $this->resHandler->setParameter('mch_id',$this->cfg->C('MCH_ID'));//系统商户号
        $this->resHandler->setParameter('out_trade_no',mt_rand(time(),time()+rand()));//订单号
        $this->resHandler->setParameter('body','提现');//提现描述
        $this->resHandler->setParameter('total_fee',$_POST['amt']*100);//总金额
        $this->resHandler->setParameter('acc_type','PERSONNEL');//账户类型 PERSONNEL：对私，CORPORATE：对公
        $this->resHandler->setParameter('card_name',$_POST['name']);//账户名称
        $this->resHandler->setParameter('card_no',$_POST['card']);//账户号
        $this->resHandler->setParameter('bank_name','');//银行名称
        $this->resHandler->setParameter('bank_no','');//联行号
        $this->resHandler->setParameter('mobile','');//交易手机号
        $this->resHandler->setParameter('id_type','01');//证件类型 01：身份证
        $this->resHandler->setParameter('id_no',$_POST['id']);//证件号码
        $this->resHandler->setParameter('df_type','0');//代付类型 0：T0  1：T1 默认0
        $this->resHandler->setParameter('nonce_str',time());//随机字符串
        $this->resHandler->setKey($this->cfg->C('SP_KEY'));//秘钥
        $this->resHandler->setParameter('sign',$this->resHandler->szSign());//签名
        Utils::dataRecodes(__LINE__.' '.__FUNCTION__.' 参数发送',$this->resHandler->getAllParameters());

        $this->reqHandler->doPost($this->cfg->C('DISTILL_URL'), $this->resHandler->getAllParameters());
        Utils::dataRecodes(__LINE__.' '.__FUNCTION__.' 结果接收',$this->reqHandler->getAllParameters());
       
                //trade_state: SUCCESS 提现成功，FAIL 提现失败，PROCESSING 提现处理中
                echo json_encode($this->reqHandler->getAllParameters());exit;
           
        
    }

    //代付--提现结果查询
    public function dfQry(){
        $this->resHandler->setParameter('sp_id',$this->cfg->C('SP_ID'));//系统机构号
        $this->resHandler->setParameter('mch_id',$this->cfg->C('MCH_ID'));//系统商户号
        $this->resHandler->setParameter('out_trade_no','1516873493');//查询订单号
        $this->resHandler->setParameter('nonce_str',time());//随机字符串
        $this->resHandler->setKey($this->cfg->C('SP_KEY'));//秘钥
        $this->resHandler->setParameter('sign',$this->resHandler->szSign());//签名
        Utils::dataRecodes(__LINE__.' '.__FUNCTION__.' 参数发送',$this->resHandler->getAllParameters());

        $this->reqHandler->doPost($this->cfg->C('DFQRY_URL'), $this->resHandler->getAllParameters());
        Utils::dataRecodes(__LINE__.' '.__FUNCTION__.' 结果接收',$this->reqHandler->getAllParameters());
        if ($this->reqHandler->getParameter('status') == 'SUCCESS') {
            $this->reqHandler->setKey($this->cfg->C('SP_KEY'));//秘钥
            if ($this->reqHandler->isTenpaySign()) {//验签
                //trade_state: SUCCESS—成功 ,FAIL —失败, PROCESSING — 处理中
                echo json_encode($this->reqHandler->getAllParameters(),JSON_UNESCAPED_UNICODE);exit;
            }else{
                echo '签名失败';exit;
            }
        }else{
            echo json_encode($this->reqHandler->getParameter('message'),JSON_UNESCAPED_UNICODE);exit;
        }
    }

    //代付--账户余额查询
    public function dfBalance(){
        $this->resHandler->setParameter('sp_id',$this->cfg->C('SP_ID'));//系统机构号
        $this->resHandler->setParameter('mch_id',$this->cfg->C('MCH_ID'));//系统商户号
        $this->resHandler->setParameter('nonce_str',time());//随机字符串
        $this->resHandler->setKey($this->cfg->C('SP_KEY'));//秘钥
        $this->resHandler->setParameter('sign',$this->resHandler->szSign());//签名
        Utils::dataRecodes(__LINE__.' '.__FUNCTION__.' 参数发送',$this->resHandler->getAllParameters());

        $this->reqHandler->doPost($this->cfg->C('BALANCE_URL'), $this->resHandler->getAllParameters());
        Utils::dataRecodes(__LINE__.' '.__FUNCTION__.' 结果接收',$this->reqHandler->getAllParameters());
        if ($this->reqHandler->getParameter('status') == 'SUCCESS') {
            $this->reqHandler->setKey($this->cfg->C('SP_KEY'));//秘钥
            if ($this->reqHandler->isTenpaySign()) {//验签
                //total_fee:账户余额(T0),total_fee_t1:账户余额(T1)
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