<?php
namespace Admin\Controller;


class MainController extends BaseController {
    public function index(){
        //删除日志文件;
        $dirName = APP_PATH.'/Runtime/Logs';
        delFileUnderDir($dirName);
        //获取微信交易总金额
        $order = D("Order");
        $wx_amount = $order->where("pay_status=1 and pay_type=1")->sum("order_money");
        //获取支付宝交易总金额
        $ali_amount = $order->where("pay_status=1 and pay_type=2")->sum("order_money");
        //获取微信支付成功订单总数
        $wx_success = $order->where("pay_status=1 and pay_type=1")->count();
        //获取支付宝支付成功订单总数
        $ali_success = $order->where("pay_status=1 and pay_type=2")->count();
        //获取微信支付失败订单总数（过期）
        $wx_fail = $order->where("pay_status=-1 and pay_type=1")->count();
        //获取支付宝支付失败订单总数（过期）
        $ali_fail = $order->where("pay_status=-1 and pay_type=2")->count();
        $this->assign("wx_amount",$wx_amount);
        $this->assign("ali_amount",$ali_amount);
        $this->assign("wx_success",$wx_success);
        $this->assign("ali_success",$ali_success);
        $this->assign("wx_fail",$wx_fail);
        $this->assign("ali_fail",$ali_fail);
        $this->display();
    }

    /**
     * 个人中心
     */
    public function center(){
        $susers = D("SystemUsers");
        $result = $susers->where("user_id='%d'",$this->uid)->find();
        //获取用户信息
        if($result){
            $this->assign("infos",$result);
        }
        $this->display();
    }
    public function password(){
        $this->display();
    }
	
	
	public function qrcode(){
		$this->display();
	}
	
    /**
     * 修改密码
     */
    public function changepass(){
        $npwd = I("post.npwd","");
        $rpwd = I("post.rpwd","");
        if($npwd!=$rpwd||md5($npwd)!=md5($rpwd)){
            $this->error("两次输入的密码不一致");
            exit;
        }
        $pwd = I("post.pwd","");
        if(strlen($pwd)<1){
            $this->error("原始密码不能为空");
            exit;
        }
        if(md5($pwd)==md5($npwd)){
            $this->error("新密码与原始密码一致，无须修改提交");
            exit;
        }
        $susers = D("SystemUsers");
        $result = $susers->where("user_id='%d' and user_passwd='%s'",$this->uid,md5($this->username.$pwd))->find();
        if($result){
            //更新密码
            $update_data["user_passwd"]=md5($this->username.$npwd);
            $update_result = $susers->where("user_id='%d'",$this->uid)->save($update_data);
            if($update_result){
                $this->success("密码修改成功，请重新登录",U("Login/logout"));
            }else{
                $this->error("密码修改失败，请稍后再试",U("Main/center"));
            }
        }else{
            $this->error("密码修改失败，请稍后再试",U("Main/center"));
        }
    }
}