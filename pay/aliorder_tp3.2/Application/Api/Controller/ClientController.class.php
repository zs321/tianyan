<?php
namespace Api\Controller;
use Think\Controller;
class ClientController extends BaseController {

	/**
	*支付宝爬虫系统推送交易记录，账号状态，写入数据库，
	*account 账号
	*type 交易类型，1-微信，2-支付宝
	*money 金额
	*note 备注
	*status 支付状态 0-成功，2-失败
    *
    *客户端没有收到这边的回复就会一直发送数据过来
	*/
    public function aliApi(){    
        // echo qrdeCode('2.jpg');die;
        $arr0= I('get.');
		keep_array($arr0,"alipay.txt");
		
		/*$arr0 = array(
		  'key' => '123456',
		  'money' => '1.00',
		  't' => '2018-11-26 17:00:47',
		  'b' => '3',
		  'o' => '20181126200040011100210014870962',
		  'zfb' => 'z865423@sohu.com',
		);*/

		
		
		
        $ar = json_encode($arr0);
        $ar1 = trim($ar,'{');
        $ar2 = trim($ar1,'}');
        $ar3 = explode(',', $ar2);
		
        foreach ($ar3 as $k => $v) {
            $ar4[$k] = explode('":"', $v);
            $arr[trim($ar4[$k][0],'"')] = trim($ar4[$k][1],'"');
        }
		
        $data =array();
        $data['api_key'] = $arr[C('API_KEY')];
        //判断是否是本系统的支付客户端
        $is_this_system=M('Api_key')->find();
		
        if (($data['api_key'] && $is_this_system['api_key']==$data['api_key']) || !$data['api_key'] && !$is_this_system['api_key']) {
            //如果发送过来的参数只有2个(key,账号)，那么就是在线状态接口
            if (count($arr)==2) {
                foreach ($arr as $k => $v) {
                    if ($k!='key') {
                        $is_online = substr( $v, 0, 1 );//截图参数值第一位为在线状态，0-不在线，1-在线
                        $aliAccount = substr($v, 1);
                        if ($aliAccount) {
                            $is_system_account = M('Account')->field('account_id,is_online')->where('account="'.$aliAccount.'" and type=2')->find();
                            //判断是否是后台添加的账号，是就更新账号状态
                            if ($is_system_account) {
                                if ($is_system_account['is_online'] !=$is_online) {
									//注释掉意外参数  修改用户在线状态
                                    //$re = M('Account')->where('account_id='.$is_system_account['account_id'])->save(array('is_online'=>$is_online));
									$re = 1;
                                }
                            }
                        }
                    }
                }
            }else{
                // 否则发送过来的就是交易记录
                $data['ali_sn'] = $arr[C('ALI_SN')];
                $data['account'] = $arr[C('ZFB_ACCOUNT')];
                $data['money'] = $arr[C('MONEY')];
                $data['note'] = $arr[C('NOTE')];
                $data['addtime'] = strtotime(str_replace('.', '-', $arr[C('ADDTIME')]));
                $data['amn'] = $data['account'].$data['money'].$data['note'];//分组标识字段
                $is_system_account = M('Account')->field('account_id,is_online')->where('account="'.$data['account'].'" and type=2')->find();
				if ($is_system_account) {
                    if ($is_system_account['is_online'] ==0) {
                        M('Account')->where('account_id='.$is_system_account['account_id'])->save(array('is_online'=>1));
                    }
                    $aliTrade = M("Alipay_record");
                    $is_exist = $aliTrade->field('id')->where('account="'.$data['account'].'" and money='.$data['money'].' and note="'.$data['note'].'" and addtime='.$data['addtime'].' and ali_sn="'.$data['ali_sn'].'"')->find();
                  	
							 // print_r( $is_exist);
					//exit;
				   //交易记录是否存在
                    if ($is_exist) {
                        echo 'rs_ok1';exit;//交易记录已存在
                    }else{
						if($data['note'] == "\u6536\u6b3e"){
							$data['note'] = '收款';
						}
                        $re = $aliTrade->add($data);
                        if ($re) {
                            //写入数据库成功后，下发给接入网站
                            //$field = "o.order_id,o.order_number,o.order_money total_fee,o.note,o.pay_type,o.trade_type,o.notice_url,o.source_attach,o.order_remark,o.code_id,o.pay_status,o.notice_status,o.transaction_id";
							$field = "o.order_id,o.order_number,o.order_money total_fee,o.note,o.pay_type,o.trade_type,o.notice_url,o.source_attach,o.code_id,o.pay_status,o.notice_status,o.transaction_id";
                           
						  
							$param = M('Order')->alias('o')
                                                   // ->join('__QRCODE_ALI__ q ON o.code_id=q.code_id') 
													->join('p_qrcode_ali q ON o.code_id=q.code_id')
                                                    ->field($field)
                                                    ->where('q.account="'.$data['account'].'" and o.order_money='.$data['money'].' and o.note="'.$data['note'].'" and  o.addtime<'.$data['addtime'])
                                                    ->order('o.order_id desc')
                                                    ->find();
													
							//dump(M('Order')->getLastSql());
										
                            //$param = $order;
							
                            //判断是否被定时器提前更新
                            if ($param && $param['pay_status']==0) {
                                //如果没有被定时器更新，就更新订单支付状态，并且回调给接入网站
                                $transaction_id = date('YmdHis').mt_rand(1,999999);
								
                                $orderUpdateRe = M('Order')->where('order_id='.$param['order_id'])->save(array('pay_status'=>1,'pay_time'=>$data['addtime'],'transaction_id'=>$transaction_id,'ali_sn'=>$data['ali_sn']));
								$code_id = $param["code_id"];
								M("qrcode_ali")->where("code_id = ".$code_id)->save(array("tag"=>0));
                                // echo "=======12342";
								// print_r($orderUpdateRe);
								// echo "=======";

								//下发支付结果通知
                                if ($orderUpdateRe) {
                                    $param['pay_status']=1;
                                    $param['time_end']=$data['addtime'];
                                    $param['transaction_id']=$transaction_id;
                                    $this->notice($param);
                                }
                            }else if($param && $param['pay_status']==1 && $param['notice_status']==0){
                                //如果被定时器更新了就直接回调给接入网站
                                $param['time_end']=$data['addtime'];
                                $this->notice($param);
                            }
							
                            echo 'rs_ok2';exit;//交易记录添加成功
                        }
                    }
                }else{
                    echo 'rs_repeat1';exit;//不是本系统添加的账号，不用写进数据库
                }
            }
        }else{
            echo 'rs_repeat2';exit;//api_key错误，不是本系统的api_key
        }  
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
        $return_data["sign"] = createSign(	$return_data);
		
        $result = sendNotify($param["notice_url"],$return_data);
        if($result){
            $data["notice_status"]=1;
        }
        $data["return_data"]=$result;
        $data["notice_data"]=json_encode($return_data);
        $data["order_id"]=$param["order_id"];
        $data["modify_time"]=time();

        $update_result = M('Order')->save($data);
        if($update_result){
            return true;
        }else{
            return false;
        }
    }
}