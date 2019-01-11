<?php
namespace Admin\Controller;


use Think\Controller;

class BaseController extends Controller {
    public $uid;
    public $username;
    public $loginname;
    public function _initialize(){
        $this->checkLogin();
        
    }

    /**
     * 检测登录情况
     */
    public function checkLogin(){
        $session = session();

        if(!$session || !$session[C('MANAGE_SESSION')] || !$session[C('MANAGE_SESSION')]['username'] || !$session[C('MANAGE_SESSION')]['id']){
            $this->error("登录已失效，请重新登录",U("login/index"),10);
        }
        $this->uid = $session[C('MANAGE_SESSION')]['id'];
        $this->username = $session[C('MANAGE_SESSION')]['username'];
        $this->loginname = $session[C('MANAGE_SESSION')]['username'];

        $this->assign('username',$session[C('MANAGE_SESSION')]['username']);
        $this->assign('loginname',$session[C('MANAGE_SESSION')]['username']);
    }
}