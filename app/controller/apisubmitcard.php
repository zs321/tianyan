<?php
namespace WY\app\controller;

use WY\app\libs\Controller;
use WY\app\libs\Http;

if (!defined('WY_ROOT')) exit;

class apisubmitcard extends api
{
    public function index()
    {
        extract($this->params);
        $cardvalue = $this->req->request('cardvalue');
        $cardnum = $this->req->request('cardnum');
        $cardpwd = $this->req->request('cardpwd');
        if ($cardvalue == '' || $cardnum == '' || $cardpwd == '') {
            echo $this->ret->put('200', true);
            exit;
        }
        $mysign = md5('version=' . $version . '&customerid=' . $customerid . '&total_fee=' . $total_fee . '&sdorderno=' . $sdorderno . '&notifyurl=' . $notifyurl . '&paytype=' . $paytype . '&cardvalue=' . $cardvalue . '&cardnum=' . $cardnum . '&cardpwd=' . $cardpwd . '&' . $this->userData['apikey']);
        if ($sign != $mysign) {
            echo $this->ret->put('201', true);
            exit;
        }
        if ($cardvalue * 100 < $total_fee * 100) {
            echo $this->ret->put('304', true);
            exit;
        }
        if (!$acw = $this->model()->select()->from('acw')->where(array('fields' => 'code=?', 'values' => array($paytype)))->fetchRow()) {
            echo $this->ret->put('500', true);
            exit;
        }
        if ($acw['length']) {
            $cardLength = json_decode($acw['length'], true);
            if (strlen($cardnum) != $cardLength[0]) {
                echo $this->ret->put('305', true);
                exit;
            }
            if (strlen($cardpwd) != $cardLength[1]) {
                echo $this->ret->put('306', true);
                exit;
            }
        }
        $acc = $this->model()->select('a.id,a.acpcode,a.gateway,a.is_state,b.is_state as is_state_acc,b.channelid')->from('acc a')->left('userprice b')->on('b.channelid=a.id')->join()->where(array('fields' => 'b.userid=? and a.acwid=?', 'values' => array($customerid, $acw['id'])))->fetchRow();
        if (!$acc) {
            echo $this->ret->put('103', true);
            exit;
        }
        if ($acc['is_state'] == '1') {
            echo $this->ret->put('100', true);
            exit;
        }
        if ($acc['is_state_acc'] == '1') {
            echo $this->ret->put('102', true);
            exit;
        }
        $channelid = $acc['channelid'];
        $acpcode = $acc['acpcode'];
        $gateway = $acc['gateway'];
        $orderid = $this->res->getOrderID();
        $addtime = time();
        $orderinfo = array('userid' => $customerid, 'paytype' => $paytype, 'bankcode' => $gateway, 'notifyurl' => $notifyurl, 'faceno' => $cardvalue, 'cardpwd' => $cardpwd, 'cardnum' => $cardnum, 'remark' => $remark, 'addtime' => $addtime,);
        if (!$orderinfoid = $this->model()->from('orderinfo')->insertData($orderinfo)->insert()) {
            echo $this->ret->put('209', true);
            exit;
        }
        $orderdata = array('userid' => $customerid, 'agentid' => $this->userData['superid'], 'orderid' => $orderid, 'sdorderno' => $sdorderno, 'total_fee' => $total_fee, 'channelid' => $channelid, 'addtime' => $addtime, 'lastime' => $addtime, 'is_paytype' => 0, 'orderinfoid' => $orderinfoid,);
        if (!$orid = $this->model()->from('orders')->insertData($orderdata)->insert()) {
            echo $this->ret->put('210', true);
            exit;
        }
        $ordernotify = array('orid' => $orid, 'addtime' => $addtime,);
        if (!$this->model()->from('ordernotify')->insertData($ordernotify)->insert()) {
            echo $this->ret->put('211', true);
            exit;
        }
        $submitUrl = 'http://' . $this->req->server('HTTP_HOST') . '/pay/' . $acpcode . '_card/send.php';
        $params = array('orderid' => $orderid, 'price' => $total_fee, 'cardnum' => $cardnum, 'cardpwd' => $cardpwd, 'cardvalue' => $cardvalue, 'gateway' => $paytype,);
        $http = new Http($submitUrl, $params);
        $http->toUrl();
        $content = $http->getResContent();
        $code = $http->getResCode();
        $errinfo = $http->getErrInfo();
        $data = array('code' => $code, 'content' => $this->res->subString($content, 0, 50), 'info' => $errinfo,);
        $this->model()->from('orderinfo')->updateSet(array('retmsg' => json_encode($data)))->where(array('fields' => 'id=?', 'values' => array($orderinfoid)))->update();
        if ($content == 'ok') {
            echo $this->ret->put('302', true);
            exit;
        }
        echo json_encode(array('status' => '400', 'msg' => $content));
    }
}

?>