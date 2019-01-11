<?php
/**
 * Created by PhpStorm.
 * User: Code
 * Date: 2017/6/27
 * Time: 17:41
 */

namespace Api\Controller;


use Think\Controller;

class TestController extends Controller {
    public function index(){
        $this->display();
    }
    public function pc(){
        header("Content-type: text/html; charset=utf-8");
        $data["trade_type"]="ALIPAYPC";
        $data["body"]="购买商品2件-PC支付";
        $data["attach"]="123456789";
        $data["total_fee"]="0.5";
        $data["return_url"]=U("test/home",array(),true,true);
        $data["notice_url"]=U("test/notice",array(),true,true);
        $data["out_trade_no"]=date("YmdHis",time());
        $data["sign"] = createSign($data);
        $url = U("pay/set",array(),true,true);
        $this->assign("url",$url);
        $this->assign("param",$data);
        $this->display("form");
    }
    public function wechatnative(){
        $data["trade_type"]="WECHATNATIVE";
        $data["body"]="购买商品2件-微信扫码";
        $data["attach"]="123456789";
        $data["total_fee"]="0.01";
        $data["return_url"]="";
        $data["notice_url"]=U("test/notice",array(),true,true);
        $data["out_trade_no"]=date("YmdHis",time());
        $data["sign"] = createSign($data);
        $url = U("pay/set",array(),true,true);
        $this->assign("url",$url);
        $this->assign("param",$data);
        $this->display("form");
    }

    public function notice(){
        file_put_contents('notice'.time().rand(1,99).'.txt',json_encode($_REQUEST));
        echo "SUCCESS";
        exit;
    }

    public function home(){
        var_dump($_REQUEST);
    }

    public function qpc(){
        $data["out_trade_no"]="20171024171253";
        $data["sign"] = createSign($data);
        $url = U("pay/query",array(),true,true);
        $this->assign("url",$url);
        $this->assign("param",$data);
        $this->display("form");
    }
    public function qwechatnative(){
        $data["out_trade_no"]="20171024170100";
        $data["sign"] = createSign($data);
        $url = U("pay/query",array(),true,true);
        $this->assign("url",$url);
        $this->assign("param",$data);
        $this->display("form");
    }
}
