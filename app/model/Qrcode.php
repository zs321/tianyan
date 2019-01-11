<?php
namespace WY\app\model;

use WY\app\libs\Model;
use WY\app\libs\Controller;
if (!defined('WY_ROOT')) {
    exit;
}
class qrcode extends Controller
{

    const FIXED = 1;  //固码
    const AUTO = 2;   //自动码
    const TRANSFER = 3; //转账码
    const COMPANY = 1; //企业
    const PERSONAL = 2; //个人
    private $params;


    function __construct(){
        $this->model = new Model();
    }

    function index($params){
        $this->params = $params;



        //$user = $this->model->select()->from('users')->where(array('fields' => 'left(salt,20)=?', 'values' => array($uuid)))->fetchRow();
//判断账号类型
//        找到账号对应的码的类型有哪些
        $account_type = $this->model->select()->from('atc')->where(array('fields' => 'account_type=?', 'values' => array($params["account_type"])))->fetchAll();
// self::PERSONAL
        if($this->params['account_type'] == 1){
            dump($account_type);
        }

        //如果只有一种直接调用对应的方法    如果有多种则判断优先级     调用方法时应考虑是否有条件限制


    }

    function getPersonal(){

        echo "123456";

    }


    //如果是企业 判断是否满足可以走固码的条件





    //找固码



    //找自动码
    //判断自动码是否存入固码



    //个人生成转账码





}