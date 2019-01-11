<?php
namespace Admin\Controller;


use Think\Page;

class OrderController extends BaseController {
    /*获取账号列表*/
    public function getAccountList(){
        $type = I('get.type');
        $re = M('Account')->where('type='.$type)->select();
        $str = '';
        if ($re) {
            $str .= '<option value="">选择收款账号</option>';
            foreach ($re as $k => $v) {
                $str .= '<option value="'.$v['account'].'">'.$v['account'].'</option>';
            }
            $arr = array('state'=>'SUCCESS','data'=>$str);
        }
        $this->ajaxReturn($arr,'JSON');
    }
    /*更新成功订单，和释放成功订单的二维码（捡漏）*/
    public function freeSucc(){
        // 更新支付成功但未更新到状态的订单，并释放支付成功订单的二维码，把之前已经支付成功的订单但没释放二维码的也释放
        //微信金额备注账号相同情况下的时间最新一条交易记录
        $wx_record = M('')->query('select q.code_id,r.addtime from (select a.account,a.money,a.note,a.addtime from p_wxpay_record a where addtime = (select max(addtime) from p_wxpay_record where amn = a.amn) order by a.amn) as r join p_qrcode_wx q on r.account=q.account and r.money=q.money and r.note=q.note');
        // file_put_contents('wx_record.txt', json_encode($wx_record));
        if ($wx_record) {
            $code_id0 = array();
            foreach ($wx_record as $k => $v) {
                $transaction_id = date('YmdHis').mt_rand(1,999999);
                //下单时间小于最近支付时间的订单更新为已支付
                $re = M("Order")->where('code_id='.$v['code_id'].' and pay_type=1 and pay_status=0 and addtime<'.$v['addtime'])->save(array('pay_status'=>1,'pay_time'=>$v['addtime'],'transaction_id'=>$transaction_id));
                $code_id0[]=$v['code_id'];

            }
            //把之前支付成功的订单，并且二维码没被释放的,支付时间距离当前超过1分钟的，去释放
            $code_id0 = array_unique($code_id0);
            $cid = M('')->query('select o.code_id from (select a.order_id,a.code_id,a.pay_type,a.pay_status,a.addtime,a.pay_time from p_order a where order_id = (select max(order_id) from p_order where code_id = a.code_id) order by a.code_id) as o where o.code_id in ('.join(',',$code_id0).') and o.pay_type=1 and o.pay_status=1 and o.pay_time<'.(time()-60));
            // file_put_contents('payOrderre.txt', json_encode($cid));
            if ($cid) {
                $cids = array();
                foreach ($cid as $k => $v) {
                    $cids[] = $v['code_id'];
                }
                $cids = array_unique($cids);
                M('Qrcode_wx')->where('code_id in ('.join(',',$cids).') and tag=1')->save(array('tag'=>0));
                // file_put_contents('updatepayCodetagSql.txt', M('Qrcode_wx')->getLastSql());
            }
        }
        //支付宝金额备注账号相同情况下的时间最新一条交易记录
        $ali_record = M('')->query('select q.code_id,r.addtime from (select a.account,a.money,a.note,a.addtime from p_alipay_record a where addtime = (select max(addtime) from p_alipay_record where amn = a.amn) order by a.amn) as r join p_qrcode_ali q on r.account=q.account and r.money=q.money and r.note=q.note');
        if ($ali_record) {
            $code_id1 = array();
            foreach ($ali_record as $k => $v) {
                $transaction_id = date('YmdHis').mt_rand(1,999999);
                //下单时间小于最近支付时间的订单更新为已支付
                $re1 = M("Order")->where('code_id='.$v['code_id'].' and pay_type=2 and pay_status=0 and addtime<'.$v['addtime'])->save(array('pay_status'=>1,'pay_time'=>$v['addtime'],'transaction_id'=>$transaction_id));
                $code_id1[]=$v['code_id'];
            }
            $code_id1 = array_unique($code_id1);
            //把之前支付成功的订单，并且二维码没被释放的,支付时间距离当前超过1分钟的，去释放
            $cid1 = M('')->query('select o.code_id from (select a.order_id,a.code_id,a.pay_type,a.pay_status,a.addtime,a.pay_time from p_order a where order_id = (select max(order_id) from p_order where code_id = a.code_id) order by a.code_id) as o where o.code_id in ('.join(',',$code_id1).') and o.pay_type=2 and o.pay_status=1 and o.pay_time<'.(time()-60));
            if ($cid1) {
                $cids1 = array();
                foreach ($cid1 as $k => $v) {
                    $cids1[] = $v['code_id'];
                }
                $cids1 = array_unique($cids1);
                M('Qrcode_ali')->where('code_id in ('.join(',',$cids1).') and tag=1')->save(array('tag'=>0));
            }
            
        }
    }
    //全部订单
    public function index(){
        //将支付成功但未更改到订单状态的订单更新，并释放支付成功1分钟后的二维码
        // $this->freeSucc();
        $search = '1=1';
        $search1 = 'pay_status=1';
        //支付状态
        $paystatus = I("get.paystatus");
        if($paystatus!=''){
            $this->assign("paystatus",$paystatus);
            $search.= " And pay_status='".$paystatus."'";
        }
        //下发状态
        $noticestatus = I("get.noticestatus");
        if($noticestatus!='' ){
            $this->assign("noticestatus",$noticestatus);
            $search.= " And notice_status='".$noticestatus."'";
        }
        
        
                
        //时间
        $sday = str_replace("+"," ",I("get.sday",""));
        $eday = str_replace("+"," ",I("get.eday",""));

        if(!empty($sday)){
            $search.= " And addtime >= ".strtotime($sday);
            $search1.= " And addtime >= ".strtotime($sday);
            $this->assign("sday",date("Y-m-d H:i",strtotime($sday)));
        }else{
			$search.= " And addtime >= ".strtotime(date("Y-m-d"),time());
            $search1.= " And addtime >= ".strtotime(date("Y-m-d"),time());
            $this->assign("sday",date("Y-m-d 00:00"));
		}

        if(!empty($eday)){
            $search.= " And addtime <= ".strtotime($eday);
            $search1.= " And addtime <= ".strtotime($eday);
            $this->assign("eday",date("Y-m-d H:i",strtotime($eday)));
        }





        //订单编号
        $orderno = I("get.orderno","");

        if(!empty($orderno)){
            $search.= " And order_number Like '%".$orderno."%' or ali_sn like '%".$orderno."%' or note like '%".$orderno."%'";
            $search1.= " And order_number Like '%".$orderno."%' or ali_sn like '%".$orderno."%' or note like '%".$orderno."%'";
            $this->assign("orderno",$orderno);
        }
        //支付方式
        $paytype = I("get.paytype");
        $this->assign("paytype",$paytype);
        if(!empty($paytype)){
            $search.= " And pay_type=".$paytype;
            $search1.= " And pay_type=".$paytype;
            $re = M('Account')->where('type='.$paytype)->select();
            if ($re) {
                $this->assign("re",$re);
            }
        }

        
        //账号
        $account = trim(I('get.account'));
        $this->assign("account",$account);
        if(!empty($account)){
            $search.= " And account = '".$account."'";
            $search1.= " And account = '".$account."'";
        }

        $order = D("Order");
        if($search){
            $counts = $order->where($search)->count();
        }else{
            $counts = $order->count();
        }
        $page = new Page($counts,10);
        if($search){
            $list = $order->where($search)->order("order_id desc")->limit($page->firstRow.",".$page->listRows)->select();
        }else{
            $list = $order->order("order_id desc")->limit($page->firstRow.",".$page->listRows)->select();
        }
        
        //当日固码交易成功总金额  单量
        // print_r($search1);
        $money = $order->where($search1)->sum("order_money");
        $count = $order->where($search1)->count();
        $this->assign("lists",$list);
        $this->assign("money",$money);
        $this->assign("count",$count);

        $this->assign("page",str_replace("+"," ",$page->show()));
        $this->display();
    }
    //支付成功订单，已支付，已下发
    public function successOrder(){
        $search = '1=1';
        //支付状态
        $paystatus = I("get.paystatus","-2");
        if($paystatus=-2){
            $paystatus = 1;
        }
        //下发状态
        $noticestatus = I("get.noticestatus","-2");
        if($noticestatus=-2 ){
            $noticestatus = 1;
        }
        $this->assign("paystatus",$paystatus);
        $this->assign("noticestatus",$noticestatus);
        $search.= " And pay_status='".$paystatus."'";
        $search.= " And notice_status='".$noticestatus."'";
                
        //时间
        $sday = str_replace("+"," ",I("get.sday",""));
        $eday = str_replace("+"," ",I("get.eday",""));
        if(!empty($sday)||!empty($eday)){
            $search.= " And (addtime Between ".strtotime($sday)." AND ".strtotime($eday).")";
            $this->assign("sday",date('Y-m-d H:i',strtotime($sday)));
            $this->assign("eday",date('Y-m-d H:i',strtotime($eday)));
        }else{
			$search.= " And addtime >= ".strtotime(date("Y-m-d"),time());
            $search1.= " And addtime >= ".strtotime(date("Y-m-d"),time());
            $this->assign("sday",date("Y-m-d 00:00"));
		}
        //订单编号
        $orderno = I("get.orderno","");
        if(!empty($orderno)){
            $search.= " And order_number Like '%".$orderno."%'";
            $this->assign("orderno",$orderno);
        }
        //支付方式
        $paytype = I("get.paytype");
        $this->assign("paytype",$paytype);
        if(!empty($paytype)){
            $search.= " And pay_type=".$paytype;
        }
        
        
        $order = D("Order");
        if($search){
            $counts = $order->where($search)->count();
        }else{
            $counts = $order->count();
        }
        $page = new Page($counts,10);
        if($search){
            $list = $order->where($search)->order("order_id desc")->limit($page->firstRow.",".$page->listRows)->select();
        }else{
            $list = $order->order("order_id desc")->limit($page->firstRow.",".$page->listRows)->select();
        }
        //交易总金额
        $money = $order->where("pay_status=1")->sum("order_money");
        $this->assign("lists",$list);
        $this->assign("money",$money);
        $this->assign("page",$page->show());
        $this->display();
    }
    //待支付订单,未支付，未下发
    public function waitPay(){
        $search = '1=1';
        //支付状态
        $paystatus = I("get.paystatus","-2");
        if($paystatus=-2){
            $paystatus = 0;
        }
        //下发状态
        $noticestatus = I("get.noticestatus","-2");
        if($noticestatus=-2){
            $noticestatus = 0;
        }
        $this->assign("paystatus",$paystatus);
        $this->assign("noticestatus",$noticestatus);
        $search.= " And pay_status='".$paystatus."'";
        $search.= " And notice_status='".$noticestatus."'";
        //时间
        $sday = str_replace("+"," ",I("get.sday",""));
        $eday = str_replace("+"," ",I("get.eday",""));
        if(!empty($sday)||!empty($eday)){
            $search.= " And (addtime Between ".strtotime($sday)." AND ".strtotime($eday).")";
            $this->assign("sday",$sday);
            $this->assign("eday",$eday);
        }else{
			$search.= " And addtime >= ".strtotime(date("Y-m-d"),time());
            $search1.= " And addtime >= ".strtotime(date("Y-m-d"),time());
            $this->assign("sday",date("Y-m-d"));
		}
        //订单编号
        $orderno = I("get.orderno","");
        if(!empty($orderno)){
            $search.= " And order_number Like '%".$orderno."%'";
            $this->assign("orderno",$orderno);
        }
        //支付方式
        $paytype = I("get.paytype");
        $this->assign("paytype",$paytype);
        if(!empty($paytype)){
            $search.= " And pay_type=".$paytype;
        }
        $order = D("Order");
        if($search){
            $counts = $order->where($search)->count();
        }else{
            $counts = $order->count();
        }
        $page = new Page($counts,10);
        if($search){
            $list = $order->where($search)->order("order_id desc")->limit($page->firstRow.",".$page->listRows)->select();
        }else{
            $list = $order->order("order_id desc")->limit($page->firstRow.",".$page->listRows)->select();
        }
        //交易总金额
        if($search){
            $money = $order->where($search)->sum("order_money");
        }else{
            $money = $order->where("pay_status=1")->sum("order_money");
        }
        $this->assign("lists",$list);
        $this->assign("money",$money);
        $this->assign("page",$page->show());
        $this->display();
    }
    //支付失败订单（过期订单）,未支付，未下发，支付状态为-1过期
    public function expireOrder(){
        $search = '1=1';
        //支付状态
        $paystatus = I("get.paystatus","-2");
        if($paystatus=-2){
            $paystatus = -1;
        }
        //下发状态
        $noticestatus = I("get.noticestatus","-2");
        if($noticestatus=-2){
            $noticestatus = 0;
        }
        $this->assign("paystatus",$paystatus);
        $this->assign("noticestatus",$noticestatus);
        $search.= " And pay_status='".$paystatus."'";
        $search.= " And notice_status='".$noticestatus."'";
       
		
		//时间
        $sday = str_replace("+"," ",I("get.sday",""));
        $eday = str_replace("+"," ",I("get.eday",""));
        if(!empty($sday)||!empty($eday)){
            $search.= " And (addtime Between ".strtotime($sday)." AND ".strtotime($eday).")";
            $this->assign("sday",date('Y-m-d H:i',strtotime($sday)));
            $this->assign("eday",date('Y-m-d H:i',strtotime($eday)));
        }else{
			$search.= " And addtime >= ".strtotime(date("Y-m-d"),time());
            $search1.= " And addtime >= ".strtotime(date("Y-m-d"),time());
            $this->assign("sday",date("Y-m-d 00:00"));
		}
		
		
        //订单编号
        $orderno = I("get.orderno","");
        if(!empty($orderno)){
            $search.= " And order_number Like '%".$orderno."%'";
            $this->assign("orderno",$orderno);
        }
        //支付方式
        $paytype = I("get.paytype");
        $this->assign("paytype",$paytype);
        if(!empty($paytype)){
            $search.= " And pay_type=".$paytype;
        }
        $order = D("Order");
        if($search){
            $counts = $order->where($search)->count();
        }else{
            $counts = $order->count();
        }
        $page = new Page($counts,10);
        if($search){
            $list = $order->where($search)->order("order_id desc")->limit($page->firstRow.",".$page->listRows)->select();
        }else{
            $list = $order->order("order_id desc")->limit($page->firstRow.",".$page->listRows)->select();
        }
        //交易总金额
        if($search){
            $money = $order->where($search)->sum("order_money");
        }else{
            $money = $order->where("pay_status=1")->sum("order_money");
        }
        $this->assign("lists",$list);
        $this->assign("money",$money);
        $this->assign("page",$page->show());
        $this->display();
    }
    //掉单处理（下发失败订单）,已支付，未下发
    public function noticeFail(){
        $search = '1=1';
        //支付状态
        $paystatus = I("get.paystatus","-2");
        if($paystatus=-2){
            $paystatus = 1;
        }
        //下发状态
        $noticestatus = I("get.noticestatus","-2");
        
        if($noticestatus=-2){
            $noticestatus = 0;
        }
        $this->assign("paystatus",$paystatus);
        $this->assign("noticestatus",$noticestatus);
        $search.= " And pay_status='".$paystatus."'";
        $search.= " And notice_status='".$noticestatus."'";
        //时间
        $sday = str_replace("+"," ",I("get.sday",""));
        $eday = str_replace("+"," ",I("get.eday",""));
        if(!empty($sday)||!empty($eday)){
            $search.= " And (addtime Between ".strtotime($sday)." AND ".strtotime($eday).")";
            $this->assign("sday",$sday);
            $this->assign("eday",$eday);
        }else{
			$search.= " And addtime >= ".strtotime(date("Y-m-d"),time());
            $search1.= " And addtime >= ".strtotime(date("Y-m-d"),time());
            $this->assign("sday",date("Y-m-d"));
		}
        //订单编号
        $orderno = I("get.orderno","");
        if(!empty($orderno)){
            $search.= " And order_number Like '%".$orderno."%'";
            $this->assign("orderno",$orderno);
        }
        //支付方式
        $paytype = I("get.paytype");
        $this->assign("paytype",$paytype);
        if(!empty($paytype)){
            $search.= " And pay_type=".$paytype;
        }
        $order = D("Order");
        if($search){
            $counts = $order->where($search)->count();
        }else{
            $counts = $order->count();
        }
        $page = new Page($counts,10);
        if($search){
            $list = $order->where($search)->order("order_id desc")->limit($page->firstRow.",".$page->listRows)->select();
        }else{
            $list = $order->order("order_id desc")->limit($page->firstRow.",".$page->listRows)->select();
        }
        //交易总金额
        if($search){
            $money = $order->where($search)->sum("order_money");
        }else{
            $money = $order->where("pay_status=1")->sum("order_money");
        }
        $this->assign("lists",$list);
        $this->assign("money",$money);
        $this->assign("page",$page->show());
        $this->display();
    }
    /**
     * 订单详情
     */
    public function detail(){
        $order_id = I("get.d",0);
        if(!(1*$order_id)){
            $this->error("订单请求错误");
            exit;
        }
        $order = D("Order");
        $result = $order->where("order_id='%d'",$order_id)->find();
        if($result){
            $result["order_req_param"] = json_decode($result['order_req_param'],true);
            $this->assign("detail",$result);
        }else{
            $this->error("订单数据获取异常");
            exit;
        }
        $this->display();
    }

    /**
     * 重新发送
     */
    public function send(){
        $order_id = I("get.d",0);
        if(!(1*$order_id)){
            $this->error("订单请求错误");
            exit;
        }
        $order = M("Order");
        $result = $order->where("order_id='%d'",$order_id)->find();
        if($result){
            $return_data = array("return_code"=>"SUCCESS","return_msg"=>"","result_code"=>"SUCCESS",
                "err_code"=>"","err_code_des"=>"","trade_type"=>"","out_trade_no"=>"","transaction_id"=>"",
                "attach"=>"","time_end"=>"","total_fee"=>"");
            $return_data["trade_type"]=$result["trade_type"];
            $return_data["out_trade_no"]=$result["order_number"];
            $return_data["transaction_id"]=$result["transaction_id"];
            $return_data["attach"]=$result["source_attach"];
            $return_data["time_end"]=date('YmdHis',$result['pay_time']);
            $return_data["total_fee"]=$result['order_money'];
            $return_data["sign"] = createSign($return_data);
            $re = sendNotify($result["notice_url"],$return_data);
            if($re == 'SUCCESS'){
                $data["notice_status"]=1;
                $data["return_data"]=$result;
                $data["notice_data"]=json_encode($return_data);
                $data["order_id"]=$result["order_id"];
                $data["modify_time"]=time();

                $update_result = $order->save($data);
                if($update_result){
                    $this->success("下发数据成功");
                    exit;
                }else{
                    $this->error("下发数据失败");
                    exit;
                }
            }else{
                $this->error("下发数据失败");
                exit;
            }
            
        }else{
            $this->error("订单数据获取异常");
            exit;
        }
    }
}