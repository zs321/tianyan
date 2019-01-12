<?php
namespace WY\app\controller\admfor035;

use WY\app\libs\Controller;
if (!defined('WY_ROOT')) {
    exit;
}
class group extends CheckAdmin
{
    public function index()
    {
        //查询出用户与通道分组的关联

        $groupList = $this->model()->select("a.group_id,a.group_name,b.id,b.username")->left('users b')->on('a.group_id = b.group_id')->join()->from('channelgroup a')->fetchAll();
        $tongdao = $this->model()->select()->from('acc')->fetchAll();
        $data = array('title' => '通道分组列表', 'lists' => $groupList, 'tongdao' => $tongdao);


        $this->put('group.php', $data);
    }

    public function edit()
    {
        //修改通道  传入通道id查出通道的信息
        $id = isset($this->action[3]) ? intval($this->action[3]) : 0;

        $group = $this->model()->select()->from('channelgroup')->where(array('fields' => 'group_id=?', 'values' => array($id)))->fetchRow();

        $tongdao = $this->model()->select()->from('acc')->fetchAll();

        $arr = explode(",",$group["channelid"]);


        foreach ($tongdao as $key=>$row){
            $tongdao[$key]["ischecked"] = "0";
            foreach ($arr as $channel){
                if($row["id"] == $channel){
                    $tongdao[$key]["ischecked"] = "1";
                    //echo  $tongdao[$key]["ischecked"]."<br>";
                    //echo  $key."<br/>";
                }
            }
        }

        //$acl = $this->model()->select('a.gateway,b.name')->left('acw b')->on('a.acwid=b.id')->join()->from('acl a')->where(array('fields' => 'a.acpcode=?', 'values' => array($acc['acpcode'])))->fetchAll();
        $data = array('title' => '编辑通道', 'group' => $group, 'tongdao' => $tongdao,"data" => $id);
        $this->put('groupedit.php', $data);
    }

    public function save(){
        $data = isset($_POST) ? $_POST : false;
        $data["channelid"] = implode($data["channelid"],',');

        if($this->model()->from('channelgroup')->insertData($data)->insert()){
            echo json_encode(array('status' => 1, 'msg' => '分组添加成功', 'url' => $this->dir . 'group'));
            exit;
        }else{
            echo json_encode(array('status' => 0, 'msg' => '分组添加失败'));
            exit;
        }
    }


    public function editsave()
    {
        $id = isset($this->action[3]) ? intval($this->action[3]) : 0;

        $data = isset($_POST) ? $_POST : false;

        $data["channelid"] = implode($data["channelid"],',');

        if($this->model()->from('channelgroup')->updateSet($data)->where(array('fields' => 'group_id=?', 'values' => array($id)))->update()){
            echo json_encode(array('status' => 1, 'msg' => '设置保存成功'));
            exit;
        }else{
            echo json_encode(array('status' => 0, 'msg' => '设置保存失败'));
            exit;
        }
    }

    public function del()
    {
        $id = $this->req->get('id');
        if ($id) {
            if ($this->model()->from('channelgroup')->where(array('fields' => 'group_id=?', 'values' => array($id)))->delete()) {

                echo json_encode(array('status' => 1));
                exit;
            }
        }
        echo json_encode(array('status' => 0));
        exit;
    }


}