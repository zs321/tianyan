<?php
namespace Admin\Controller;


use Think\Controller;

class LoginController extends Controller {
    public function index(){
        layout(false);
        $this->display();
    }
    public function submit(){
        //用户名
        $username = I("username","");
        //密码
        $password = I("userpwd","");
        //验证码
        $vercode = I("vcode","");
        if(!$this->check_verify($vercode)){
            $this->error("亲，验证码输错了哦！",$this->site_url,9);
            exit;
        }
        $susers = D("SystemUsers");
        $result = $susers->where("user_name='%s' and user_passwd='%s'",$username,md5($username.$password))->find();
        if($result){
            session(C('MANAGE_SESSION'),array('username'=>$result['user_name'],'id'=>$result['user_id']));
            session('expire',3600);
            //跳转至管理中心
            $this->redirect("main/index");
        }else{
            $this->error("用户名/密码错误",$this->site_url,9);
        }
    }
    /*
     * 验证码
     */
    public function verify(){
        ob_clean();
        $verify = new \Think\Verify();
        $verify->useCurve = false;
        $verify->useNoise = false;
        $verify->length = 5;
        $verify->fontttf = "6.ttf";
        $verify->bg = array(255,255,255);
        $verify->entry();
    }
    /**
     * 验证码检验
     * @param $code 验证码
     * @return bool
     */
    private function check_verify($code){
        $verify = new \Think\Verify();
        return $verify->check($code);
    }
    /**
     * 注销
     */
    public function logout(){
        session(C('MANAGE_SESSION'),null);
        session('[destroy]');
        $this->redirect("login/index");
    }
}