<?php
/**
 * Created by PhpStorm.
 * User: Code
 * Date: 2017/7/18
 * Time: 14:44
 */

namespace Admin\Controller;


use Think\Page;

class PayconfigController extends BaseController {

    public function index(){
        $pay = D("Payconfig");
        $counts = $pay->count();

        $page = new Page($counts,10);
        $list = $pay->order("config_id desc")->limit($page->firstRow.",".$page->listRows)->select();

        $this->assign("lists",$list);
        $this->assign("page",$page->show());
        //获取网关
        $gateway = D("Gateway");
        $wechat = $gateway->where("way_type=0")->select();
        if($wechat){
            $this->assign("wechat",$wechat);
        }
        $alipay = $gateway->where("way_type=1")->select();
        if($alipay){
            $this->assign("alipay",$alipay);
        }
        $this->assign("ajaxUrl",U("payconfig/ajaxset"));
        $this->display();
    }

    public function ajaxset(){
        $config_id = I("post.config",0);
        $way_id = I("post.way",0);
        $data = array("status"=>1,"msg"=>"通道网关设置成功");
        if(!(1*$config_id)||!(1*$way_id)){
            $data["status"]=0;
            $data["msg"]="通道网关设置请求参数错误";
            $this->ajaxReturn($data,'JSON');
            exit;
        }
        //更新数据
        $pay = D("Payconfig");
        if($pay->where("config_id='%d'",$config_id)->save(array("config_gateway"=>$way_id))){
            $this->ajaxReturn($data,'JSON');
            exit;
        }else{
            $data["status"]=0;
            $data["msg"]="通道网关设置失败";
            $this->ajaxReturn($data,'JSON');
            exit;
        }
    }
}