<?php
namespace WY\app\model;

use WY\app\libs\Controller;
if (!defined('WY_ROOT')) {
    exit;
}
class Payacp extends Controller
{
    public function get($acpcode)
    {
        $banklist = $this->model('email,userid,userkey,nate_gate_url')->select()->from('acp')->where(array('fields' => 'code=?', 'values' => array($acpcode)))->fetchRow();
//        dump($banklist,'',1);
        return $banklist;
    }


    public function getNewAccount($acpcode)
    {
        $banklist = $this->model('email,userid,userkey,account_type')->select()->from('acp')->where(array('fields' => 'code=?', 'values' => array($acpcode)))->orderby("lasttime asc")->fetchRow();
//        dump($banklist,'',1);
        return $banklist;
    }

    public function MobileData($no)
    {
        $md_id = $this->model('no')->select()->from('mobile')->where(array('fields' => 'no=?', 'values' => array($no)))->fetchRow();
        return $md_id;
    }

    public function MobileDataAdd($data)
    {
        $md_id = $this->model()->from('mobile')->insertData($data)->insert();
        return $md_id;
    }







}
?>