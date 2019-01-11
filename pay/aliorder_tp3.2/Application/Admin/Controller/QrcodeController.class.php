<?php
/**
 * Created by PhpStorm.
 * User: Code
 * Date: 2017/7/18
 * Time: 13:56
 */

namespace Admin\Controller;


use Think\Page;

class QrcodeController extends BaseController {
    /*微信二维码列表*/
    public function index(){
        $re = M('Account')->where('type=1')->select();
        $this->assign("re",$re);
        $search= '1=1';
        //所属账号
        $account = I('get.account');
        if($account!='' ){
            $this->assign("account",$account);
            $search.= " And account='".$account."'";
        }
        //开始金额
        $start_m = I("get.start_m");
        if($start_m!='' ){
            $this->assign("start_m",$start_m);
            $search.= " And money>='".$start_m."'";
        }
        //结束金额
        $end_m = I("get.end_m");
        if($end_m!='' ){
            $this->assign("end_m",$end_m);
            $search.= " And money<='".$end_m."'";
        }
        //开始备注
        $start_n = I("get.start_n");
        if($start_n!='' ){
            $this->assign("start_n",$start_n);
            $search.= " And note>='".$start_n."'";
        }
        //结束备注
        $end_n = I("get.end_n");
        if($end_n!='' ){
            $this->assign("end_n",$end_n);
            $search.= " And note<='".$end_n."'";
        }
        //状态
        $tag = I("get.tag",'-1');
        if($tag!='-1' ){
            $search.= " And tag='".$tag."'";
        }
        $this->assign("tag",$tag);
        $qrcode = D("Qrcode_wx");
        $counts = $qrcode->where($search)->count();

        $page = new Page($counts,10);
        $list = $qrcode->where($search)->order("account asc,money asc,note asc")->limit($page->firstRow.",".$page->listRows)->select();
        $this->assign("codePath",C('YUMING').'/Public/Uploads/Qrcode/');
        $this->assign("lists",$list);
        $this->assign("page",$page->show());
        $this->display();
    }
    /*支付宝二维码列表*/
    public function aliCode(){
        $re = M('Account')->where('type=2')->select();
        $this->assign("re",$re);
        $search= '1=1';
        //所属账号
        $account = I('get.account');
        if($account!='' ){
            $this->assign("account",$account);
            $search.= " And account='".$account."'";
        }
        //开始金额
        $start_m = I("get.start_m");
        if($start_m!='' ){
            $this->assign("start_m",$start_m);
            $search.= " And money>='".$start_m."'";
        }
        //结束金额
        $end_m = I("get.end_m");
        if($end_m!='' ){
            $this->assign("end_m",$end_m);
            $search.= " And money<='".$end_m."'";
        }
        //开始备注
        $start_n = I("get.start_n");
		
        if($start_n!='' ){
            $this->assign("start_n",$start_n);
            $search.= " And note like '%".$start_n."%'";
        }
        //结束备注
        $end_n = I("get.end_n");
        if($end_n!='' ){
            $this->assign("end_n",$end_n);
            $search.= " And note like '%".$end_n."%'";
        }
        //状态
        $tag = I("get.tag",'-1');
        if($tag!='-1' ){
            $search.= " And tag='".$tag."'";
        }
		
        $this->assign("tag",$tag);
        $qrcode = D("Qrcode_ali");
        $counts = $qrcode->where($search)->count();

        $page = new Page($counts,10);
        $list = $qrcode->where($search)->order("account asc,money asc,note asc")->limit($page->firstRow.",".$page->listRows)->select();

        $this->assign("lists",$list);
        $this->assign("codePath",C('YUMING').'/Public/Uploads/Qrcode/');
        $this->assign("page",$page->show());
        $this->display();
    }
    /*添加二维码页面*/
    public function add(){
        $type = I('get.type');
        if (empty($type)) {
            $type = 0;
            $re = M('Account')->where('type=1')->select();
        }else{
            $re = M('Account')->where('type='.$type)->select();
        }
        $this->assign("re",$re);
        $this->assign("type",$type);
        $this->display();
    }
    /*获取账号列表*/
    public function getAccountList(){
        $type = I('get.type');
        $re = M('Account')->where('type='.$type)->select();
        $str = '';
        if ($re) {
            foreach ($re as $k => $v) {
                $str .= '<option value="'.$v['account'].'">'.$v['account'].'</option>';
            }
            $arr = array('state'=>'SUCCESS','data'=>$str);
        }
        $this->ajaxReturn($arr,'JSON');
    }
    /*二维码数据保存*/
    public function dataHandle(){
        $data = I('post.');
        if ($data['qrcode_type'] == 1) {
            $qrcode = M('Qrcode_wx');
        }else if ($data['qrcode_type'] == 2) {
            $qrcode = M('Qrcode_ali');
        }
        $is_exist = $qrcode->where('money="'.$data['money'].'" and note="'.$data['note'].'" and account="'.$data['account'].'"')->find();
        if ($is_exist) {
            $res = array(
                'code'=>1,
                'msg'=>'该账号下该金额备注的二维码已经存在！'
            );
            $this->ajaxReturn($res,'JSON');
        }
        $config = array(
                'mimes'         =>  array(), //允许上传的文件MiMe类型
                'maxSize'       =>  0, //上传的文件大小限制 (0-不做限制)
                'exts'          =>  array('jpg', 'gif', 'png', 'jpeg'), //允许上传的文件后缀
                'autoSub'       =>  true, //自动子目录保存文件
                 'subName'       =>  array('date', 'Y-m-d'), //子目录创建方式，[0]-函数名，[1]-参数，多个参数使用数组
				// 'subName'       =>  $data['account'], //子目录创建方式，[0]-函数名，[1]-参数，多个参数使用数组
                'rootPath'      =>  C('QRCODE_UPLOAD'), //保存根路径
                'savePath'      =>  '',//保存路径
              
            );
        $upload = new \Think\Upload($config);// 实例化上传类
         
        
        $info   =   $upload->upload();
        
        if(!$info) {
            $res = array(
                    'code'=>1,
                    'msg'=>$upload->getError()
                );
             
        }else{// 上传成功
            foreach ($info as $va){
                $data['qrcode'] = $va['savepath'].$va['savename'];
                $savename = $va['savename'];
            }
            // 1、生成缩略图
            $originalImage = C('QRCODE_UPLOAD').$data['qrcode'];
            // 保存的路径
            $date = date('Y-m-d');
			// $date = $data['account'];
            $thumbPath = C('QRCODE_THUMB').$date;
            if(!is_dir($thumbPath)){
                mkdir(iconv("UTF-8", "GBK", $thumbPath),0777,true); 
            }
            // 绝对路径加上文件名
            $save = $thumbPath.'/'.$savename;
            thumbImg($originalImage,$save);
            //2、解析二维码图片的地址（相对路径）
            $qrdecodePath = C('QRDECODE').$date.'/'.$savename;
            $qrdecode = qrDecode($qrdecodePath);

            $data['qrdecode'] = $qrdecode;
            $data['addtime'] = time();
            $re = $qrcode->add($data);
            if ($re) {
                // @unlink(C('QRCODE_UPLOAD').$data['qrcode']);
                $res = array(
                    'code'=>0,
                    'msg'=>'上传成功！'
                );
            }else{
                $res = array(
                    'code'=>1,
                    'msg'=>'上传失败！'
                );
            }
        }
        $this->ajaxReturn($res,'JSON');
    }
    /*二维码删除*/
    public function del(){
        $code_id = I("post.code_id");
        $type = I("post.type");
        if($type==1){
            $qrcode = D("Qrcode_wx");
        }else if($type==2){
            $qrcode = D("Qrcode_ali");
        }
        if($qrcode->where("code_id='%d'",$code_id)->delete()){
            @unlink(C('QRCODE_UPLOAD').I('post.qrcode'));
            @unlink(C('QRCODE_THUMB').I('post.qrcode'));
            @unlink(C('QRCODE_CREATE').I('post.qrcode'));
            $this->ajaxReturn('success');
        }else{
            $this->ajaxReturn('fail');
        }
    }
    /*二维码手动重解码*/
    public function qrdecode(){
        $code_id = I("post.code_id");
        $type = (int)I('post.type');
        $qrdecode = I("post.qrdecode");
        if($type==1){
            $qrcode = D("Qrcode_wx");
        }else if($type==2){
            $qrcode = D("Qrcode_ali");
        }
        $re = $qrcode->where("code_id='%d'",$code_id)->save(array('qrdecode'=>$qrdecode));
         
        if($re){
            $this->ajaxReturn('success');
        }else{
            $this->ajaxReturn('fail');
        }
        
    }
    /*解码失败的二维码列表*/
    public function decodeFailList()
    {
        $type=(int)I('get.type');
        if ($type == 1) {
            $qrcode = D("Qrcode_wx");
        }else if ($type == 2) {
            $qrcode = D("Qrcode_ali");
        }
        $search = 'qrdecode is null or qrdecode = "" or qrdecode = " "';
        $counts = $qrcode->where($search)->count();

        $page = new Page($counts,10);
        $list = $qrcode->where($search)->order("account asc,money asc,note asc")->limit($page->firstRow.",".$page->listRows)->select();
        // var_dump($type);
        // var_dump($qrcode->getLastSql());die;
        $this->assign("codePath",C('YUMING').'/Public/Uploads/Qrcode/');
        $this->assign("type",$type);
        $this->assign("lists",$list);
        $this->assign("page",$page->show());
        $this->display();
    }
    /*二维码查看*/
    public function show(){
        layout(false);
        $code_id = I("get.code_id");
        $type = I("get.type");
        if($type==1){
            $qrcode = D("Qrcode_wx");
        }else if($type==2){
            $qrcode = D("Qrcode_ali");
        }
        $re = $qrcode->field('qrcode,qrdecode,addtime')->where("code_id='%d'",$code_id)->find();
        
		
		
		
		$file_qrcode = C('QRCODE_CREATE').$re['qrcode'];
		
        if (file_exists($file_qrcode)) {
			
			$img = stripos($re["qrcode"],'HTTPS')?$re['qrcode']:C('QRCODE').$re['qrcode'];
           
        }else{
            $date = date('Y-m-d',$re['addtime']);
            $createPath = C('QRCODE_CREATE').$date;
            if(!is_dir($createPath)){
                mkdir(iconv("UTF-8", "GBK", $createPath),0777,true); 
            }
            $img = stripos($re["qrcode"],'HTTPS')?$re['qrcode']:C('QRCODE').$re['qrcode'];//显示用的需要带域名的地址
            $file_qrcode = C('QRCODE_CREATE').$re['qrcode'];//生成二维码的图片保存地址需要是相对路径
            qrCode($re['qrdecode'],$file_qrcode);
        }
        
        $this->assign('qrcode',$img);
        $this->display();
    }
    /*有效期设置*/
    public function valiSet(){
        $re = M('Vali_con')->find();
        if ($re) {
            $this->assign('re',$re);
        }
        $this->display();
    }
    /*有效期数据保存*/
    public function valiHandle(){
        $data = I('post.');
        $id = I('get.id');
        if ($id) {
            $re = M('Vali_con')->where('id='.$id)->save($data);
        }else{
            $re = M('Vali_con')->add($data);
        }
        if ($re) {
            $this->setFree();
            $this->success("操作成功",U("Qrcode/valiSet"));
        }else{
            $this->error("操作失败");
        }
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
}