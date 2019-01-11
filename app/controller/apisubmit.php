<?php
namespace WY\app\controller;

use WY\app\libs\Controller;
if (!defined('WY_ROOT')) {
    exit;
}
class apisubmit extends api
{


/*$this->params:Array
(
    [version] => 1.0
    [customerid] => 10000
    [sdorderno] => 2018112849545798
    [total_fee] => 2.00
    [paytype] => weixin
    [bankcode] =>
    [notifyurl] => http://demo.7zcyl.com/notify.php
    [returnurl] => http://demo.7zcyl.com/return.php
    [remark] =>
    [sign] => 11fd5d8abfc846863e15437773f4c2e0
    [cardnum] =>
    [fromurl] =>
)
$this->userData:Array
(
    [id] => 10000
    [is_agent] => 0
    [ship_type] => 1
    [ship_cycle] => 0
    [username] => admin
    [userpass] => 7c4a8d09ca3762af61e59520943dc26494f8941b
    [is_state] => 1
    [paid] => 190719.93
    [unpaid] => 6713.57
    [addtime] => 1493862888
    [token] => 45418ea587ddee9da12c50fe2d938391d27f33af
    [apikey] => 7897062fc648ca140512b0c7bf66ff67009e1e86
    [is_checkout] => 1
    [is_paysubmit] => 1
    [is_verify_email] => 0
    [is_verify_phone] => 1
    [is_verify_siteurl] => 0
    [is_takecash] => 1
    [superid] => 0
    [salt] => 7afc4df9278f4ffe0c86ebdea682222ce4f61318
)*/

    /*  $this->params = Array
        (
            'version' => 1.0,
            'customerid' => 10000,
            'sdorderno' => 2018112849545798,
            'total_fee' => 2.00,
            'paytype' => 'weixin',
            'bankcode' =>'',
            'notifyurl' => 'http://demo.7zcyl.com/notify.php',
            'returnurl' => 'http://demo.7zcyl.com/return.php',
            'remark' =>'',
            'sign' => '11fd5d8abfc846863e15437773f4c2e0',
            'cardnum' =>'',
            'fromurl' =>''
        );*/
    public function index()
    {

        extract($this->params);

        //验证对接网站是否合法
        if ($this->userData['is_verify_siteurl']) {
            $fromurl = $fromurl ? $fromurl : $this->req->server('HTTP_REFERER');
            $userinfo = $this->model()->select('siteurl')->from('userinfo')->where(array('fields' => 'userid=?', 'values' => array($this->userData['id'])))->fetchRow();


            if ($fromurl == '' || !strpos($fromurl, $userinfo['siteurl'])) {
                echo $this->ret->put('206', $cardnum ? true : false);
                exit;
            }
        }

        $signStr = 'version=' . $version . '&customerid=' . $customerid . '&total_fee=' . $total_fee . '&sdorderno=' . $sdorderno . '&notifyurl=' . $notifyurl . '&returnurl=' . $returnurl . '&' . $this->userData['apikey'];
        $mysign = md5($signStr);

        //验证签名是否正确
        if ($sign != $mysign) {
            echo $this->ret->put('201', $cardnum ? true : false);
            exit;
        }

        //验证支付方式是否合法
        switch ($paytype) {
            case 'bank':
			case 'qq':
            case 'alipay':
            case 'tenpay':
            case 'weixin':
            case 'qqrcode':
            case 'tenpaywap':
            case 'alipaywap':
            case 'wxh5':
            case 'qqwallet':
            case 'gzhpay':
			case 'kjpay':
			case 'qyzfb':
		    case 'gateway';
			case 'bankpay';
            case 'alipaycode';
            case 'gumagetway';
                $this->submit();
                break;
            default:
                echo $this->ret->put('106', $cardnum ? true : false);
                exit;
        }
    }
    protected function submit()
    {

        extract($this->params);
        $bankcode = $paytype == 'bank' ? $bankcode : $paytype;

        //验证订单是否已存在
        if ($this->model()->select()->from('orders')->where(array('fields' => 'userid=? and sdorderno=?', 'values' => array($this->userData['id'], $sdorderno)))->count()) {
            echo $this->ret->put('205', $cardnum ? true : false);
            exit;
        }


        //查找通用网关
        $acw = $this->model()->select('id')->from('acw')->where(array('fields' => 'code=?', 'values' => array($paytype)))->fetchRow();
//        dump($acw,1,1);
        if (!$acw) {
            echo $this->ret->put('500', $cardnum ? true : false);
            exit;
        }
		
        /*$acc=$this->model()->select()->from('acc')->where(array('fields'=>'acwid=? and is_state=?','values'=>array($acw['id'],0)))->fetchAll();if(!$acc){echo $this->ret->put('103',$cardnum ? true : false);exit;}$userprice=$this->model()->select()->from('userprice')->where(array('fields'=>' userid=?','values'=>array($customerid)))->fetchAll();if(!$userprice){echo $this->ret->put('101',$cardnum ? true : false);exit;}$is_state=$channelid=$acpcode=$gateway=$is_state_acc='';foreach($userprice as $key=>$val){foreach($acc as $key2=>$val2){if($val['channelid']==$val2['id']){$is_state=$val['is_state'];$channelid=$val['channelid'];$acpcode=$val2['acpcode'];$gateway=$val2['gateway'];$is_state_acc=$val2['is_state'];break;}}}if($acpcode=='' || $gateway==''){echo $this->ret->put('103',$cardnum ? true : false);exit;}if($is_state=='1'){echo $this->ret->put('100',$cardnum ? true : false);exit;}if($is_state_acc=='1'){echo $this->ret->put('102',$cardnum ? true : false);exit;}*/
        
		
		
		
		
		
		//根据通用网关和商户查找对应接入商、网关、通道、通道状态、通道分成比率、通道分成比率状态
        //acc和userprice表
		$acc = $this->model()->select('a.id,a.acpcode,a.gateway,a.is_state,b.is_state as is_state_acc,b.channelid')->from('acc a')->left('userprice b')->on('b.channelid=a.id')->join()->where(array('fields' => 'b.userid=? and a.acwid=?', 'values' => array($customerid, $acw['id'])))->fetchRow();

        //分组 轮询通道


//        dump($acc,1,1);
//        var_dump($this->model()->select('a.id,a.acpcode,a.gateway,a.is_state,b.is_state as is_state_acc,b.channelid')->from('acc a')->left('userprice b')->on('b.channelid=a.id')->join()->where(array('fields' => 'b.userid=? and a.acwid=?', 'values' => array($customerid, $acw['id'])))->toDubugSql());
//die();
//        $acc_sql = $this->model()->select('a.id,a.acpcode,a.gateway,a.is_state,b.is_state as is_state_acc,b.channelid')->from('acc a')->left('userprice b')->on('b.channelid=a.id')->join()->where(array('fields' => 'b.userid=? and a.acwid=?', 'values' => array($customerid, $acw['id'])))->toDubugSql();
//        dump($acc_sql);





        if (!$acc) {
            echo $this->ret->put('103', $cardnum ? true : false);
            exit;
        }

        //判断通道是否处于暂停状态
        if ($acc['is_state'] == '1') {
			
            echo $this->ret->put('100', $cardnum ? true : false);
            exit;
        }
        //判断通道分成比率是否处于暂停状态
        if ($acc['is_state_acc'] == '1') {
            echo $this->ret->put('102', $cardnum ? true : false);
            exit;
        }
        $channelid = $acc['channelid'];
        $acpcode = $acc['acpcode'];
        $gateway = $acc['gateway'];
        $orderid = $this->res->getOrderID();
        $addtime = time();
        $orderinfo = array('userid' => $customerid, 'paytype' => $paytype, 'bankcode' => $bankcode, 'notifyurl' => $notifyurl, 'returnurl' => $returnurl, 'remark' => $remark, 'addtime' => $addtime);
        if (!($orderinfoid = $this->model()->from('orderinfo')->insertData($orderinfo)->insert())) {
            echo $this->ret->put('209', $cardnum ? true : false);
            exit;
        }
		if($total_fee<1){
			echo '请求金额必须大于1元';exit; 
		}
		if(($total_fee>3000 && $paytype=='alipay') || ($total_fee>3000 && $paytype=='alipaywap')) {
			echo '支付宝单笔支付限额3000元!';exit; 
		}
		if($total_fee>3000 && $paytype=='weixin'){
			echo '微信单笔支付限额3000元!';exit; 
		}
		
		if($total_fee>200000 && $paytype=='bank'){
			echo '网上银行单笔支付限额200000元!';exit; 
		}
		
        $orderdata = array('userid' => $customerid, 'agentid' => $this->userData['superid'], 'orderid' => $orderid, 'sdorderno' => $sdorderno, 'total_fee' => $total_fee, 'channelid' => $channelid, 'addtime' => $addtime, 'lastime' => $addtime, 'is_paytype' => 0, 'orderinfoid' => $orderinfoid);
        if (!($orid = $this->model()->from('orders')->insertData($orderdata)->insert())) {
            echo $this->ret->put('210', $cardnum ? true : false);
            exit;
        }
        $ordernotify = array('orid' => $orid, 'addtime' => $addtime);
        if (!$this->model()->from('ordernotify')->insertData($ordernotify)->insert()) {
            echo $this->ret->put('211', $cardnum ? true : false);
            exit;
        }
       // $url = 'http://' . $this->req->server('HTTP_HOST') . '/pay/' . $acpcode . '_' . $gateway . '/send.php';
	    $url = 'http://' . $this->req->server('HTTP_HOST') . '/pay/' . $acpcode . '/send.php';

        $url .= '?wg='.$gateway .'&orderid=' . $orderid . '&price=' . $total_fee . '&bankcode=' . $bankcode . '&remark=' . $remark.'&userid='.$this->userData['id'];

        $this->res->redirect($url);

    }
}
?>
