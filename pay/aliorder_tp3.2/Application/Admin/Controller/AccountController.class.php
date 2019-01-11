<?php
namespace Admin\Controller;

use Think\Page;

class AccountController extends BaseController {
    /**
     * 账号列表
     */
    public function accountList(){
        $search = '1=1';
        //账号
        $account = trim(I("get.account",""));
        if($account){
            $this->assign("account",$account);
            $search.= " and account Like '%".$account."%'";
        }
        //支付方式
        $type = I("get.type");
        if(!empty($type)){
            $this->assign("type",$type);
            $search.= " and type=".$type;
        }
        $account = M("Account");
        
        $counts = $account->where($search)->count();
        $page = new Page($counts,10);
        $list = $account->where($search)->order("account_id desc")->limit($page->firstRow.",".$page->listRows)->select();
        $this->assign("lists",$list);
        $this->assign("page",$page->show());
        $this->display();
    }
    /*添加账号*/
    public function addEdit(){
        $account_id = I("get.account_id",0);
        if($account_id){
            $account = M("Account");
            $re = $account->where("account_id='%d'",$account_id)->find();
            $this->assign("re",$re);
            $act = '编辑';
        }else{
            $act = '新增';
        }
        $amount_con = M("Amount_con");  
        $amount = $amount_con->select();
        $this->assign("act",$act);
        $this->assign("amount",$amount);
        $this->display();
    }
    /*数据保存*/
    public function dataHandle(){
        $account_id = I("get.account_id",0);
        $data = I("post.");
        if ($data['amount']) {
            $amount_arr = explode('——', $data['amount']);
            $data['min'] = $amount_arr[0];
            $data['max'] = $amount_arr[1];
        }      
        $account = M("Account");
        if ($account_id) {
            if ($data['oldAccount']!== $data['account']) {
                $account_is_exist=$account->where('account="'.$data['account'].'" and type='.$data['type'])->find();
                if ($account_is_exist) {
                    $this->error("该类型的账号名称已经存在");
                }
            }
            $update_result = $account->where("account_id='%d'",$account_id)->save($data);
        }else{
            $account_is_exist=$account->where('account="'.$data['account'].'" and type='.$data['type'])->find();
                if ($account_is_exist) {
                    $this->error("该类型的账号名称已经存在");
                }
            $data['addtime'] = time();
            $update_result = $account->add($data);
        }
        
        if($update_result){
            $this->success("操作成功",U('Account/accountList'));
        }else{
            $this->error("操作失败");
        }
        exit;

    }
    /**
     * 启用/禁用
     */
    public function disable(){
        $id = I("get.d",0);
        if(!(1*$id)){
            $this->error("账号不存在");
            exit;
        }
        $status = I("get.s",0);
        $account = M("Account");
        $check_result = $account->where("account_id='%d'",$id)->find();
        if(!$check_result){
            $this->error("账号不存在");
            exit;
        }
        $update_data["status"]=$status;
        $update_result = $account->where("account_id='%d'",$id)->save($update_data);
        if($update_result){
            $this->success("账号启用/禁用成功");
        }else{
            $this->error("账号启用/禁用失败");
        }
        exit;
    }
    /*删除账号*/
    public function del(){
        $account_id = I("get.account_id",0);
        if(!(1*$account_id)){
            $this->error("账号不存在");
            exit;
        }
        $account = M("Account");
        $result = $account->where("account_id='%d'",$account_id)->delete();
        if($result){
            $this->success("删除成功");
        }else{
            $this->error("删除失败");
        }
    }
    /**
     * 金额范围列表
     */
    public function amountList(){
        $amount_con = M("Amount_con");    
        $counts = $amount_con>count();
        $page = new Page($counts,10);
        $list = $amount_con->limit($page->firstRow.",".$page->listRows)->select();
        $this->assign("lists",$list);
        $this->assign("page",$page->show());
        $this->display();
    }
    /*添加账号*/
    public function addEditAmount(){
        $id = I("get.id",0);
        if($id){
            $amount_con = M("Amount_con");    
            $re = $amount_con->where("id='%d'",$id)->find();
            $this->assign("re",$re);
            $act = '编辑';
        }else{
            $act = '新增';
        }
        $this->assign("act",$act);
        $this->display();
    }
    /*数据保存*/
    public function saveAmount(){
        $id = I("get.id",0);
        $data = I("post.");
        $amount_con = M("Amount_con");   
        if ($id) {
            $res = $amount_con->where("id='%d'",$id)->save($data);
        }else{
            $data['addtime'] = time();
            $res = $amount_con->add($data);
        }
        
        if($res){
            $this->success("操作成功",U('Account/amountList'));
        }else{
            $this->error("操作失败");
        }
        exit;

    }
    /*删除金额范围*/
    public function delAmount(){
        $id = I("get.id",0);
        if(!(1*$id)){
            $this->error("账号不存在");
            exit;
        }
        $amount_con = M("Amount_con");  
        $result = $amount_con->where("id='%d'",$id)->delete();
        if($result){
            $this->success("删除成功");
        }else{
            $this->error("删除失败");
        }
    }
    /*系统密钥设置*/
    public function system(){
        $re = M('Api_key')->find();
        if ($re) {
            $this->assign('re',$re);
        }
        $this->display();
    }
    /*系统密钥数据保存*/
    public function systemHandle(){
        $data = I('post.');
        $id = I('get.id');
        if ($id) {
            $re = M('Api_key')->where('id='.$id)->save($data);
        }else{
            $re = M('Api_key')->add($data);
        }
        if ($re) {
            $this->success("操作成功",U("account/system"));
        }else{
            $this->error("操作失败");
        }

    }
    /*设置在线状态*/
    public function setOnline(){
        $id = I("get.d",0);
        if(!(1*$id)){
            $this->error("账号不存在");
            exit;
        }
        $is_online = I("get.s",0);
        $account = M("Account");
        $check_result = $account->where("account_id='%d'",$id)->find();
        if(!$check_result){
            $this->error("账号不存在");
            exit;
        }
        $update_data["is_online"]=$is_online;
        $update_result = $account->where("account_id='%d'",$id)->save($update_data);
        if($update_result){
            $this->success("在线状态更新成功");
        }else{
            $this->error("在线状态更新失败");
        }
        exit;
    }
    
    
    
        /*设置在线状态*/
    public function numMoney(){
         $search = 'pay_status=1';
         $search1 = 'pay_status=1';
          //时间
        $sday = str_replace("+"," ",I("get.sday",""));
        $eday = str_replace("+"," ",I("get.eday",""));
        

        if(!empty($sday)){
            $search.= " And a.addtime >= ".strtotime($sday);
            $search1.= " And a.addtime >= ".strtotime($sday);
            $this->assign("sday",date("Y-m-d H:i:s",strtotime($sday)));
        }else{
            $search.= " And a.addtime >= ".strtotime(date("Y-m-d"),time());
            $search1.= " And a.addtime >= ".strtotime(date("Y-m-d"),time());
            $this->assign("sday",date("Y-m-d 00:00:00"));
        }

        if(!empty($eday)){
            $search.= " And a.addtime <= ".strtotime($eday);
            $search1.= " And a.addtime <= ".strtotime($eday);
            $this->assign("eday",date("Y-m-d H:i:s",strtotime($eday)));
        }

       //账号
        $account = trim(I('get.account'));
        $this->assign("account",$account);
        if(!empty($account)){
            $search.= " And a.account = '".$account."'";
            $search1.= " And a.account = '".$account."'";
        }
    
        $order =  M('order');
     
        

        //得到有数据帐号当日单量 金额列表
        $lists  =$order->alias("a")
            ->join("p_account b on a.account = b.account","left")
             ->where($search)
             ->field("count(order_id) as num_count,b.note,a.account,SUM(order_money) as money")
             ->group("a.account")
             ->order('money DESC')
             ->select();
             
        // dump($order->getLastSql());die();
        $page = new Page(count($lists),10);
        
        
        $lists  =$order->alias("a")
            ->join("p_account b on a.account = b.account","left")
             ->where($search)
             ->field("count(order_id) as num_count,b.note,a.account,SUM(order_money) as money")
             ->group("a.account")
             ->order('money DESC')
             ->limit($page->firstRow.",".$page->listRows)
             ->select();

        $money = $order->alias("a")->where($search1)->sum("order_money");
        // echo $order->getLastSql();
        $count = $order->alias("a")->where($search1)->count();
        $this->assign("money",$money);
        $this->assign("count",$count);
        $this->assign("lists",$lists);
        

         $this->assign("page",str_replace("+"," ",$page->show()));
         $this->display();
    }
    
    
    
    
    
    
    
    
}