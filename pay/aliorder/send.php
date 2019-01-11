<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/12/10
 * Desc: 找码
 * Time: 15:10
 */
/*
$_REQUEST:Array
(
    [wg] => gumajierugetway001
    [orderid] => 2018120715075384368
    [price] => 2.00
    [bankcode] => gumagetway
    [remark] =>
    [userid] => 10000
)
$param = array (
'ali_account' => '17172607760@163.com', //接入商账号
'attach' => '', //附加数据
'body' => '购买商品-在线支付',   //写死
'net_gate_url' => 'http://api6.1899pay.com:81',   //如果没有固码 请求手机的地址组成   不知道啥规则  先写死
'notice_url' => 'http://api.1899pay.com//Services/txqpc/callback.aspx',   //异步返回地址
'out_trade_no' => 'OD181122170449000',   //平台生成订单号   有没有特定生成规则
'return_url' => 'http://api.1899pay.com//Services/txqpc/jump.aspx',   //不知道有啥用  先写死
'sign' => '7402EF6CC2D4B297AE038BE929D9B5ED',   //用于对接固码平台验证
'total_fee' => '0.02', //金额
'trade_type' => 'ALIPAYPC', //支付终端

);
Array
(
    [id] => 69
    [code] => guma
    [name] => 固码
    [email] => 2897483365@qq.com
    [userid] =>
    [userkey] => 企业
    [lasttime] =>
)
*/
namespace WY\pay\aliorder;
use WY\app\libs\Controller;
use WY\app\model\Payacp;
use WY\app\model\qrcode;
class send extends Controller
{
    private $params;

    //得到参数列表
    function __construct(){
        parent::__construct();
        $Payacp = new Payacp();
        $acpData=$Payacp->getNewAccount('aliorder');
        $this->params['ali_account'] = $acpData['email'];
        $this->params['attach'] = $this->req->request('remark');
        $this->params['body'] = '购买商品-在线支付';
        $this->params['net_gate_url'] = $acpData['nate_gate_url'];   //分配给手机的域名  一台手机对应一个
        $this->params['notice_url'] = 'http://'.$_SERVER['HTTP_HOST'].'/pay/guma/notify.php';
        $this->params['out_trade_no'] = $this->req->request('orderid');
        $this->params['return_url'] = 'http://'.$_SERVER['HTTP_HOST'].'/pay/guma/return.php';
        $this->params['total_fee'] = $this->req->request('price');
        $this->params['trade_type'] = $this->req->request('trade_type');   //从demo程序传过来的
        $this->params['account_type'] = $acpData["account_type"];

    }

    function index(){
        //dump($this->params);
        //传账号、金额找出码
        $Qrcode = new Qrcode();
        $qr_code = $Qrcode->index($this->params);

    }



    function set(){

    }














}
