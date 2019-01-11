<?php
namespace WY\app\controller;

use WY\app\libs\Controller;
use WY\app\model\Retmsg;
if (!defined('WY_ROOT')) {
    exit;
}
class api extends Controller
{
    function __construct()
    {
        parent::__construct();
        //判断是否跳转
        if ($this->config['is_checkout_jump'] && $this->config['api_jump_url'] && $this->config['api_jump_url'] != $this->req->server('HTTP_HOST') && isset($_REQUEST)) {
            $urlstr = '';
            foreach ($_REQUEST as $key => $val) {
                $urlstr .= $urlstr ? '&' : '';
                $urlstr .= $key . '=' . $val;
            }
            header('location:http://' . $this->config['api_jump_url'] . '/apisubmit?' . $urlstr . '&fromurl=' . $this->req->server('HTTP_REFERER'));
            exit;
        }
        $this->ret = new Retmsg();
        $version = '1.0';
        $customerid = $this->req->request('customerid');  //商户id
        $sdorderno = $this->req->request('sdorderno');    //商户订单号
        $total_fee = $this->req->request('total_fee');    //金额
        $paytype = $this->req->request('paytype');        //支付类型
        $notifyurl = $this->req->request('notifyurl');    //服务器异步通知页面路径
        $bankcode = $this->req->request('bankcode');     //银行
        $returnurl = $this->req->request('returnurl');   //页面跳转同步通知页面路径
        $remark = $this->req->request('remark');  //附加
        $sign = $this->req->request('sign');   //MD5签名
        $cardnum = $this->req->request('cardnum');   //用于判断返回json数据还是直接返回
        $fromurl = $this->req->request('fromurl');   //参数没在demo找到
        if (!isset($_REQUEST) || !$_REQUEST) {
            echo $this->ret->put('208', $cardnum ? true : false);
            exit;
        }
        if ($version == '' || $customerid == '' || $total_fee == '' || $sdorderno == '' || $paytype == '' || $notifyurl == '' || $sign == '') {
            echo $this->ret->put('200', $cardnum ? true : false);
            exit;
        }
        if (strlen($sdorderno) > 50) {
            echo $this->ret->put('203', $cardnum ? true : false);
            exit;
        }
        if ($total_fee > 1000000) {
            echo $this->ret->put('207', $cardnum ? true : false);
            exit;
        }
        if ($remark && strlen($remark) > 50) {
            echo $this->ret->put('204', $cardnum ? true : false);
            exit;
        }
        if ($this->model()->select()->from('orders')->where(array('fields' => 'userid=? and sdorderno=?', 'values' => array($customerid, $sdorderno)))->count()) {
            echo $this->ret->put('205', $cardnum ? true : false);
            exit;
        }
        $this->userData = $this->model()->select()->from('users')->where(array('fields' => 'id=?', 'values' => array($customerid)))->fetchRow();
        if (!$this->userData) {
            echo $this->ret->put('001', $cardnum ? true : false);
            exit;
        }
        if ($this->userData['is_state'] == '0') {
            echo $this->ret->put('002', $cardnum ? true : false);
            exit;
        }
        if ($this->userData['is_state'] == '2') {
            echo $this->ret->put('003', $cardnum ? true : false);
            exit;
        }
        if ($this->userData['is_paysubmit'] == '0') {
            echo $this->ret->put('104', $cardnum ? true : false);
            exit;
        }

        //判断接入网站是否合法
        if ($this->userData['is_verify_siteurl']) {
            $userInfo = $this->model()->select('siteurl')->from('userinfo')->where(array('fields' => 'userid=?', 'values' => array($customerid)))->fetchRow();
            if ($userInfo) {
                $fromUrl = $this->req->server('HTTP_REFERER');
                if (strpos($fromUrl, $userInfo['siteurl']) === false) {
                    echo $this->ret->put('206', $cardnum ? true : false);
                    exit;
                }
            }
        }
        if ($paytype == 'bank') {
            if ($bankcode == '') {
                echo $this->ret->put('501', $cardnum ? true : false);
                exit;
            }
            if (!($acb = $this->model()->select()->from('acb')->where(array('fields' => 'code=?', 'values' => array($bankcode)))->fetchRow())) {
                echo $this->ret->put('503', $cardnum ? true : false);
                exit;
            }
            if ($acb['is_state'] == '1') {
                echo $this->ret->put('502', $cardnum ? true : false);
                exit;
            }
        }
        $this->params = array('version' => $version, 'customerid' => $customerid, 'sdorderno' => $sdorderno, 'total_fee' => number_format($total_fee, 2, '.', ''), 'paytype' => $paytype, 'bankcode' => $bankcode, 'notifyurl' => $notifyurl, 'returnurl' => $returnurl, 'remark' => $remark, 'sign' => $sign, 'cardnum' => $cardnum, 'fromurl' => $fromurl);

    }
}
?>
