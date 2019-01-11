<?php
/**
 * Created by PhpStorm.
 * User: Code
 * Date: 2017/6/27
 * Time: 14:57
 */

namespace Api\Controller;


use Think\Controller;

class PayController extends BaseController {


    public  $path = 'text.json'; // 接收文件目录

    public function getStatus(){

        $data= [
            "status" => true,
            "time" => time()
        ];

        $this->ajaxReturn($data);
    }






    /**
     * 支付请求参数
     * url:api/pay/set.html
     * param:
     * trade_type 交易类型 ALIPAYWAP 支付宝wap; WECHATNATIVE 微信扫描支付
     * sign 签名 MD5加密
     * body 商品描述
     * attach 附加数据
     * out_trade_no 商户订单号
     * total_fee 交易金额 单位分
     * notice_url 异步通知地址
     */
    /**
     * 支付请求回执参数
     * return_code 状态码 SUCCESS/FAIL
     * return_msg 返回信息
     * sign 签名
     * result_code 业务结果 SUCCESS/FAIL
     * err_code 错误代码
     * err_code_des 错误代码描述
     * trade_type 交易类型
     * code_url 二维码链接
     * attach 附加数据
     */
    /**
     * 支付请求
     */
    public function set(){
        $param = I("post.");
		
        
        $this->qrcode_request();

        if(!$param['trade_type'] || !$param['sign'] ||  !$param['body'] || !$param['out_trade_no'] || !$param['total_fee'] || !$param['notice_url'] || !$param['net_gate_url'])
        {
            // print_r($param);
            $this->ajaxReturn(array("return_code"=>"FAIL","return_msg"=>"参数错误"),"JSON");
            exit;
        }



        foreach ($param as $k=>$v){

            $param[$k]=trim($v);
        }

        // //验证签名
        if(!checkSign($param,$param["sign"])){
            $this->ajaxReturn(array("return_code"=>"FAIL","return_msg"=>"签名验证失败"),"JSON");
            exit;
        }
        if(!(1*$param["total_fee"])){
            $this->ajaxReturn(array("return_code"=>"FAIL","return_msg"=>"支付金额错误"),"JSON");
            exit;
        }
        if(strlen($param["out_trade_no"])<1){
            $this->ajaxReturn(array("return_code"=>"FAIL","return_msg"=>"商户订单号错误"),"JSON");
            exit;
        }

        //判读交易类型
        switch ($param["trade_type"]){
            case "ALIPAYWAP":
                $this->qrcodePay($param,2);
                break;
            case "ALIPAYPC":
                $this->qrcodePay($param,2);
                break;
            case "WECHATNATIVE":
                $this->qrcodePay($param,1);
                break;
            default:
                $this->ajaxReturn(array("return_code"=>"FAIL","return_msg"=>"交易类型错误"),"JSON");
                break;
        }

    }


    public function upstatus(){

        // 金额 备注 帐号
        if(!I("get.account") || !I("get.money") || I("get.bz") == "" ){

            exit("参数不完整");
        }

        $where = array(
            "account" => I("get.account"),
            "money" => I("get.money"),
            "note" => I("get.bz"),
            "tag" => 1
        );

        if(M('qrcode_ali')->where($where)->find()){

            $num = M('qrcode_ali')->where($where)->save(array("tag"=>0));

            if($num){
                exit("success");
            }else{

                exit("error");
            }

        }else{
            exit(M('qrcode_ali')->getLastSql());

        }

    }

    /**
     * 微信、支付宝二维码支付
     * @param $param
     * @param $type 支付类型，1-微信，2-支付宝
     */
    public function qrcodePay($param = "",$type = ""){
		
		//keep_array($param);
		$codeParam = [];
		$codeParam["account"] = $param["ali_account"];
		$codeParam["net_gate_url"] = $param["net_gate_url"];
		$codeParam["out_trade_no"] = $param["out_trade_no"];
		$codeParam["total_fee"] = $param["total_fee"];
		$codeParam["trade_type"] = $param["trade_type"];
		
		$cParamId = M("param")->add($codeParam);
		
        //获取订单金额支付二维码备注信息和二维码地址、二维码id
        $arr = array();
        $arr['ali_account'] = $param['ali_account'];
        $arr['order_number'] = $param['out_trade_no'];
		$arr['net_gate_url'] = $param["net_gate_url"];
        $arr['money'] = $param['total_fee'];
        $arr['type'] = $type;
        $local = '';   //判断二位码是否从本地数据库取出   是为1  不是为2   其他错误为3
        $data_image = '';

        $order = M('Order')->field('order_id,order_money,note,pay_status,qrdecode,code_id')->where("order_number='".$param['out_trade_no']."'")->find();
        //订单存在，已支付
        if ($order['pay_status']==1) {
            $this->ajaxReturn(array("return_code"=>"FAIL","return_status"=>-2,"return_msg"=>"该订单已支付或者订单号重复！"),"JSON");
            exit;
        }

        $valRe = M('Vali_con')->find();//二维码有效期
        $min = $valRe['validity']/60;

        $sql = "select * from `p_order` where pay_status = 0 and FROM_UNIXTIME(addtime,'%Y:%m:%d %H:%i:%s') <= DATE_SUB(NOW(),INTERVAL $min MINUTE) ";

        //查询出当前 时间 大于 订单6分中的所有的数据
        $order_list = M("order")->query($sql);

        foreach($order_list as $row){

            M("qrcode_ali")->where("code_id = '%d'",$row["code_id"])->save(array("tag"=>0));
            M("order")->where("order_id = '%d' ",$row["order_id"])->save(array("pay_status" => -1));
        }

        //订单存在，未支付但是已经有二维码  订单存在   
        if ($order['qrdecode'] && $order['pay_status']==0) {
            $qrcode = $order;
            $code_id = $order["code_id"];
			
        }else{
            //订单存在，未支付没有二维码/支付已过期，或者订单不存在
            $qrcode = $this->getQrcode($arr,$cParamId);

            if($qrcode == "error"){
                $local = 3;
            }
			
            elseif($qrcode) {
                $local = 1;
                //订单已存在，更新订单二维码信息
                if ($order) {
                    $data["order_id"] = $order["order_id"];
                    $data["note"] = $qrcode["note"];
                    $data["qrdecode"] = $qrcode["qrdecode"];
                    $data["code_id"] = $qrcode["code_id"];
                    $data["account"] = $qrcode["account"];
                    $data["pay_status"] = 0;
                    $insert_result = $this->updateOrder($data);
                    $order_id = $order["order_id"];
                }else{
                    //订单不存在存在，新增订单
                    $param["note"] = $qrcode["note"];
                    $param["qrdecode"] = $qrcode["qrdecode"];
                    $param["code_id"] = $qrcode["code_id"];
                    $param["account"] = $qrcode["account"];
                    $insert_result = $this->writeOrder($param);
                    $order_id = $insert_result;
                    if(!$insert_result){
                        $this->ajaxReturn(array("return_code"=>"FAIL","return_status"=>-3,"return_msg"=>"系统异常,支付订单无法生成"),"JSON");
                        exit;
                    }
                }
                $code_id = $qrcode["code_id"];
				
				M("param")->where(" id = %d",$cParamId)->save(array("code_id"=>$qrcode["code_id"]));
            }else{
				
                $con['account'] = $param['ali_account'];
                $con['money'] = $param['total_fee'];

                //判断二维码有没有达到最大数5张    1 达到  0未达到
                $row = M("qrcode_ali")->where($con)->getField('case when count(1) >= 5 then 1 else 0 end as c');
				
				if(!$row){
                    //自己配置note 调手机 入库

                    $r = M("qrcode_ali")->where($con)->order("note desc")->getField("note");
                    $r !== NULL ? $note = ((int)$r+1) : $note = 0;
					//dump($note);
					    // die();
					$note = $this->getBatchNo();
                    $pay_money = $param['total_fee'];  //实际付款金额
                    $local = 1;
					
                    $url = sprintf("%s/getpay?money=%s&mark=%s&type=%s",$param['net_gate_url'],$pay_money,$note,"alipay");

					M("param")->where(" id = %d",$cParamId)->save(array("geturl"=>$url));
                    $data_image = getCurl($url);
					
					//不满足条件手机响应失败   //  其他原因
					if($data_image["msg"] == "获取成功"){
						
						if($data_image['account'] == $param['ali_account']){
							//写入进二维码库
							$qrcode_data = array(
								'account'=> $data_image['account'],
								'money' => $data_image['money'],
								'qmoney' => $data_image['money'],  //必须传不然下一次会出错
								'note'=>$note,
								'qrcode'=>'http://mobile.qq.com/qrcode?url='.$data_image['payurl'],
								'qrdecode'=>$data_image['payurl'],
								'addtime'=>time(),
								'tag'=>1
							);
							
							$qrcode['code_id'] = M("qrcode_ali")->add($qrcode_data);
							
							if($qrcode['code_id']){
								
								M("param")->where(" id = %d",$cParamId)->save(array("code_id"=>$qrcode["code_id"]));
								//写订单
								$param["note"] = $data_image["mark"];
								$param["qrdecode"] = $data_image["payurl"];
								$param["code_id"] = $qrcode["code_id"];
								$param["account"] = $data_image["account"];
								
								/* 回传的数据 */
								$qrcode['qrdecode'] = $data_image['payurl'];
								$qrcode['note'] = $note;
								$qrcode["money"] = floatval($data_image['money']);
								$qrcode['qmoney'] = floatval($data_image['money']);
								$insert_result = $this->writeOrder($param);
								$order_id = $insert_result;
								if(!$insert_result){
									$this->ajaxReturn(array("return_code"=>"FAIL","return_status"=>-3,"return_msg"=>"系统异常,支付订单无法生成"),"JSON");
									exit;
								}
							}
							
						}else{
							 $local = 3;
						}					
						
					}else{
						$local = 4;
					}
				}else{
					// 调手机 不入库
					$pay_money = $param['total_fee'];  //实际付款金额
					$local = 2;
					$url = sprintf("%s/getpay?money=%s&mark=%s&type=%s",$param['net_gate_url'],$pay_money,$param['out_trade_no'],"alipay");
					$data_image = getCurl($url);
					
					if($data_image["msg"] == "获取成功"){
						M("param")->where(" id = %d",$cParamId)->save(array("code_id"=>json_encode($data_image),"geturl"=>$url));
					}else{
						$local = 4;
					}
					
					
				}
        }

    }
	
    //订单写入成功，并且把二维码返回给接入网站
    if ($type == 1) {
        $return_data = array("return_code"=>"SUCCESS","return_msg"=>"","trade_type"=>$param["trade_type"],"out_trade_no"=>$param['out_trade_no'],"attach"=>$param["attach"],"code_url"=>"");
        $return_data["code_url"]=$qrcode['qrdecode'];
    }else if ($type == 2){
        $return_data = array("return_code"=>"SUCCESS","return_msg"=>"","trade_type"=>$param["trade_type"],"out_trade_no"=>$param['out_trade_no'],"attach"=>$param["attach"],"pay_url"=>"");
        $return_data["pay_url"]=$qrcode['qrdecode'];
    }

	M("param")->where(" id = %d",$cParamId)->save(array("bendi"=>$local));
	
    $return_data["result_code"] = "SUCCESS";
    $return_data["err_code"] = "";
    $return_data["err_code_des"] = "";
    $return_data["sign"] = createSign($return_data);  //算MD5
    if($local == "1") {
		
        $return_data['note'] = $qrcode['note'];  //本地二维码备注

        if($qrcode['qmoney']) $return_data["qmoney"] = $qrcode["money"]; //二维码金额

    }

    if($local == "2") $return_data["return_msg"] = $data_image;   //不能参与到MD5的算法里面

    if($local == "3"){
        $return_data["return_code"] = "FAIL";
        $return_data["return_msg"] = "帐号状态离线或该帐号不存在<b>".$param['ali_account']."</b>";
    }

	if($local == "4"){
        $return_data["return_code"] = "FAIL";
        $return_data["return_msg"] = "通道终端异常,手机获取异常";
    }
	
	
    $return_data['bendi'] = $local;


    if ($valRe['validity']) {
        $url1 = C('YUMING').'/index.php/Api/pay/yibu1';
        $param0 = array(
            'validity'=>$valRe['validity'],//有效期
            'type'=>$type, //账号类型
            'code_id'=>$qrcode['code_id'],
            'order_id'=>$order_id,
            'addtime'=>time(), //二维码发送时间
        );
        doRequest($url1, $param0);
    }

    $url2 = C('YUMING').'/index.php/Api/pay/yibu2';
    $param00 = array(
        'code_id'=>$qrcode['code_id'],
        'type'=>$type, //账号类型
        'account'=>$qrcode["account"],
        'money'=>$qrcode["money"],
        'note'=>$qrcode["note"],
        'addtime'=>time(), //二维码发送时间
        'order_id'=>$order_id
    );

    doRequest($url2, $param00);

    $this->ajaxReturn($return_data,"JSON");
    exit;
}

	private function getQrcode($param=array(),$cParamId = ""){

        if ($param['type']==1) {
            $qrcode = M('Qrcode_wx');
        }else if ($param['type']==2) {
            $qrcode = M('Qrcode_ali');
        }
        //验证帐号是否离线
        $where = array(
            "account" => $param['ali_account'],
            "is_online" => 1
        );

        $count = M("account")->where($where)->find();

        if(!$count){
            //return 'error';
            return "error";
        }
		
		$con['account'] = $param['ali_account'];
		$con['money'] = $param['money'];

		$row = M("qrcode_ali")->where($con)->getField('case when count(1) >= 2 then 1 else 0 end as c');
		$sql = M("qrcode_ali")->getLastSql();
		
		if(!$row){
				
			$r = M("qrcode_ali")->where($con)->order("note desc")->getField("note");
			$r !== NULL ? $note = ((int)$r+1) : $note = 0;
		
			$note = $this->getBatchNo();
			$pay_money = $param['money'];  //实际付款金额
			$local = 1;
			
			$url = sprintf("%s/getpay?money=%s&mark=%s&type=%s",$param['net_gate_url'],$pay_money,$note,"alipay");
			
			M("param")->where(" id = %d",$cParamId)->save(array("geturl"=>$url));
			$data_image = getCurl($url);
			
			//不满足条件手机响应失败   //  其他原因
			if($data_image["msg"] == "获取成功" && $data_image['account'] == $param['ali_account']){
				
				//写入进二维码库
				$qrcode_data = array(
					'account'=> $data_image['account'],
					'money' => $data_image['money'],
					'qmoney' => $data_image['money'],  //必须传不然下一次会出错
					'note'=>$note,
					'qrcode'=>'http://mobile.qq.com/qrcode?url='.$data_image['payurl'],
					'qrdecode'=>$data_image['payurl'],
					'addtime'=>time(),
					'tag'=>1
				);
				
				$code_id = M("qrcode_ali")->add($qrcode_data);
				$qrcode_data["code_id"] = $code_id;
				
				$qrcode->where('code_id=' . $code_id)->save(array('tag' => 1,'redatetime' => date("Y-m-d H:i:s",time())));
				
				return $qrcode_data;
			}
		}else{
			
			$res = $qrcode->where('money=' . $param['money'] . ' and account="' .$param['ali_account'] . '" and tag=0')->order("redatetime")->find();
			
			if ($res) {
				//更新二维码标签为已使用
				$qrcode->where('code_id=' . $res['code_id'])->save(array('tag' => 1,"redatetime"=>date("Y-m-d H:i:s",time())));
				return $res;
			}
		}
		
        
    }


//异步任务，判断二维码过期
public function yibu1(){
    ignore_user_abort();//关闭浏览器仍然执行
    set_time_limit(0);//让程序一直执行下去
    $interval=1;//每隔一定时间运行
    $data = I('post.');
    $t = time()+20;
    do{
        if (time()>$t) {
            $url1 = C('YUMING').'/index.php/Api/pay/yibu1';
            doRequest($url1, $data);
            break;
        }
        //有效期为0或者没有设置时默认长期有效
        if ($data['addtime']<time()-$data['validity']) {
            $orderUpdateRe = M('Order')->where('order_id='.$data['order_id'].' and pay_status=0')->save(array('pay_status'=>-1,'overtime'=>time()));
            if ($orderUpdateRe) {
                if ($data['type']==1) {
                    $qrcode = M('Qrcode_wx');
                }else if($data['type']==2){
                    $qrcode = M('Qrcode_ali');
                }
                $qrcode->where('code_id='.$data['code_id'])->save(array('tag'=>0));
            }
            break;
        }
        sleep($interval);//等待时间，进行下一次操作。
    }while(true);
}


//异步任务，支付结果
public function yibu2(){
    ignore_user_abort();//关闭浏览器仍然执行
    set_time_limit(0);//让程序一直执行下去
    $interval=1;//每隔一定时间运行
    $data = I('post.');
    $t = time()+20;
    do{
        if (time()>$t) {
            $url2 = C('YUMING').'/index.php/Api/pay/yibu2';
            doRequest($url2, $data);
            break;
        }
        if ($data['type']==1) {
            $qrcode = M('Qrcode_wx');
        }else if($data['type']==2){
            $qrcode = M('Qrcode_ali');
        }
        //看看此订单支付成功1分钟后有没有
        $param = M('Order')->field('pay_status,pay_time,code_id')->where('order_id='.$data['order_id'])->find();
        //有就释放
        if ($param['pay_status']==1 && $param['pay_time']<(time()-60)) {
            $freeRe = $qrcode->where('code_id='.$data['code_id'])->save(array('tag'=>0));
            break;
        }else if($param['pay_status']==-1){
            break;
        }

        sleep($interval);//等待时间，进行下一次操作。
    }while(true);
}
/**
 * 支付宝、微信异步通知
 */
    public function notice($param){
        //订单处理
        $return_data = array("return_code"=>"SUCCESS","return_msg"=>"","result_code"=>"SUCCESS",
            "err_code"=>"","err_code_des"=>"","trade_type"=>"","out_trade_no"=>"","transaction_id"=>"",
            "attach"=>"","time_end"=>"","total_fee"=>"");
        $return_data["trade_type"]=$param["trade_type"];
        $return_data["out_trade_no"]=$param["order_number"];
        $return_data["transaction_id"]=$param["transaction_id"];
        $return_data["attach"]=$param["source_attach"];
        $return_data["time_end"]=date('YmdHis',$param['time_end']);
        $return_data["total_fee"]=$param['total_fee'];
        $return_data["sign"] = createSign($return_data);
        // file_put_contents('returndata.txt', json_encode($return_data));
        $result = sendNotify($param["notice_url"],$return_data);
        // file_put_contents('result.txt', $result);
        if($result){
            $data["notice_status"]=1;
        }
        $data["return_data"]=$result;
        $data["notice_data"]=json_encode($return_data);
        $data["order_id"]=$param["order_id"];
        $data["modify_time"]=time();

        $update_result = M('Order')->save($data);
        // file_put_contents('update_result.txt', $update_result);
        if($update_result){
            return true;
        }else{
            return false;
        }
    }
    /**
     * 订单写入
     * @param array $param
     * @return bool|string
     */
    private function writeOrder($param=array()){
        $order = D("Order");
        $data["order_number"]=$param["out_trade_no"];
        $data["order_money"] = $param["total_fee"];
        $data["note"] = $param["note"];
        $data["account"] = $param["account"];
        $data["qrdecode"] = $param["qrdecode"];
        $data["code_id"] = $param["code_id"];
        $data["pay_type"] = orderPayType($param["trade_type"]);
        $data["pay_status"] = 0;
        $data["notice_status"] = 0;
        $data["addtime"] = time();
        $data["code_time"] = time();
        $data["notice_url"] = $param["notice_url"];
        $data["source_attach"] = $param["attach"];
        $data["order_remark"] = $param["body"];
        $data["trade_type"] = $param["trade_type"];
        $result = $order->add($data);
        if($result){
            return $order->getLastInsID();
        }else{
            return false;
        }
    }
    /**
     * 订单更新
     * @param array $param
     * @return bool|string
     */
    private function updateOrder($param=array()){
        $order = D("Order");
        $param["code_time"] = time();
        $result = $order->where('order_id='.$param['order_id'])->save($param);
        if($result){
            return true;
        }else{
            return false;
        }
    }


    /**
     * 获取付款二维码信息
     * @param array $param money-金额，order_number-订单号，type-交易类型
     * @return array
     */
    




    /**
     * 查询请求
     */
    public function query(){
        $param = I("post.",array());
        foreach ($param as $k=>$v){
            $param[$k]=trim($v);
        }
        //验证签名
        if(!checkSign($param,$param["sign"])){
            $this->ajaxReturn(array("return_code"=>"FAIL","return_msg"=>"签名验证失败"),"JSON");
            exit;
        }

        if(strlen($param["out_trade_no"])<1){
            $this->ajaxReturn(array("return_code"=>"FAIL","return_msg"=>"查询订单号错误"),"JSON");
            exit;
        }
        //验证订单
        $order = M("Order");
        $order_detail = $order->where("order_number='%s'",$param["out_trade_no"])->find();
        if(!$order_detail){
            $this->ajaxReturn(array("return_code"=>"FAIL","return_msg"=>"查询订单号错误"),"JSON");
            exit;
        }
        if ($order_detail['pay_status'] == 1) {
            $return_data["return_code"] = "SUCCESS";
            $return_data["return_msg"] = "支付成功";
        }else{
            $return_data["return_code"] = "FAIL";
            $return_data["return_msg"] = "支付失败或者待支付";
        }

        $return_data["sign"] = createSign($return_data);
        $this->ajaxReturn($return_data,"JSON");
        exit;
    }
    /*释放成功订单的二维码（捡漏）*/
    public function freeSucc(){
        // 更新支付成功但未更新到状态的订单，并释放支付成功订单的二维码，把之前已经支付成功的订单但没释放二维码的也释放
        //微信金额备注账号相同情况下的时间最新一条交易记录
        $wx_record = M('')->query('select q.code_id,r.addtime from (select a.account,a.money,a.note,a.addtime from p_wxpay_record a where addtime = (select max(addtime) from p_wxpay_record where amn = a.amn) order by a.amn) as r join p_qrcode_wx q on r.account=q.account and r.money=q.money and r.note=q.note');
        // file_put_contents('wx_record.txt', json_encode($wx_record));
        if ($wx_record) {
            $code_id0 = array();
            foreach ($wx_record as $k => $v) {
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
	
	public function qrcode_request()
    {
        $last_op_time = date('Y-m-d H:i:s');
        if (!file_exists($this->path)) {
            $json_strings = '{"qrcode_request":1,"qrcode_success":0,"last_op_time":"'.$last_op_time.'"}';
            $result = file_put_contents($this->path,$json_strings);
            return;
        }
        $json_string = file_get_contents($this->path);// 从文件中读取数据到PHP变量
        $data = json_decode($json_string,true);// 把JSON字符串转成PHP数组
        $data["qrcode_request"]=$data["qrcode_request"] +1;
        $data["qrcode_success"]=$data["qrcode_success"];
        $data["last_op_time"]= $last_op_time;
        $json_strings = json_encode($data);
        file_put_contents("text.json",$json_strings);//写入
        return;
    }

    function qrcode_success(){
        $is_success = I('is_success');

        if($is_success == 1){

            $last_op_time = date('Y-m-d H:i:s');

            $json_string = file_get_contents($this->path);// 从文件中读取数据到PHP变量
            if(!$json_string)  echo 'FAIL';

            $data = json_decode($json_string,true);// 把JSON字符串转成PHP数组

            $data["qrcode_success"] = $data["qrcode_success"]+1;
            $data["last_op_time"] = $last_op_time;
            $json_strings = json_encode($data);

            $res = file_put_contents($this->path,$json_strings);//写入
            $json = '{"success":"SUCCESS"}';
            $error = '{"FAIL":"FAIL"}';
            if($res){
                echo "flightHandler($json)";
            }else{
                echo "flightHandler($error)";
            }
        }else{
            echo "flightHandler($error)";
        }

    }

    function qrcode_chance(){
		
        $json_string = file_get_contents($this->path);
        if(!$json_string) {
            $arr['code'] = 201;
            $arr['json_string'] = "暂无数据";
        }else{
            $arr['code'] = 200;
            $json_string = json_decode($json_string);
            $arr['json_string'] = $json_string;
        }
        $arr = json_encode($arr);
        echo "flightHandler($arr)";
    }
	
	
    /*释放所有过期订单二维码（捡漏）*/
    public function setFree(){
        //释放过期订单的二维码
        $valRe = M('Vali_con')->find();//二维码有效期
        //有效期为0或者没有设置时默认长期有效
        if ($valRe && $valRe['validity']) {
            $order = M('Order')->where('pay_status=0 and code_time<'.(time()-$valRe['validity']))->select();
            // var_dump($order);die;
            if ($order) {
                $order_id = array();
                $code_id2 = array();
                foreach ($order as $k => $v) {
                    $order_id[$v['pay_type']][] = $v['order_id'];
                    $code_id2[$v['pay_type']][] = $v['code_id'];
                }
                if ($order_id[1]) {
                    $order_id[1] = array_unique($order_id[1]);
                    $orderUpdateRe = M('Order')->where('order_id in ('.join(',',$order_id[1]).')')->save(array('pay_status'=>-1,'overtime'=>time()));
                    if ($orderUpdateRe) {
                        M('Qrcode_wx')->where('code_id in ('.join(',',$code_id2[1]).')')->save(array('tag'=>0));
                        // file_put_contents('Qrcode_wx.txt', M('Qrcode_wx')->getLastSql());
                    }
                }
                if ($order_id[2]) {
                    $order_id[2] = array_unique($order_id[2]);
                    $orderUpdateRe = M('Order')->where('order_id in ('.join(',',$order_id[2]).')')->save(array('pay_status'=>-1,'overtime'=>time()));
                    if ($orderUpdateRe) {
                        M('Qrcode_ali')->where('code_id in ('.join(',',$code_id2[2]).')')->save(array('tag'=>0));
                        // file_put_contents('Qrcode_ali.txt', M('Qrcode_ali')->getLastSql());
                    }
                }
            }
        }
    }
	
	
	// function getBatchNo(){

		// date_default_timezone_set("PRC");

		// return "G".substr(implode(NULL, array_map('ord', str_split(substr(uniqid(), 7, 13), 1))), 0, 8)."G";
	// }
	
	
	
	
	function getBatchNo(){
		
		date_default_timezone_set('PRC');

		$mtimestamp = sprintf("%.4f", microtime(true)); // 带毫秒的时间戳

		$str = number_format($mtimestamp * 10000, 0, '', '');

		return $this->dec62($str);

	}


	/**
	 * 10进制转为62进制
	 *
	 * @param integer $n 10进制数值
	 * @return string 62进制
	 */
	function dec62($n) {
		$base = 62;
		$index = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$ret = '';
		for($t = floor(log10($n) / log10($base)); $t >= 0; $t --) {
			$a = floor($n / pow($base, $t));
			$ret .= substr($index, $a, 1);
			$n -= $a * pow($base, $t);
		}
		
		return 'G'.$ret.'G';
	}
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
}