<?php
include_once(dirname(__FILE__) . "/../../../config.php");
include_once(dirname(__FILE__) . "/../v1_base.php");

class wechatuser_controller extends v1_base {
    public function preaction($action) {
        login::assertPerm(Permission::kWechatUser);
    }


    public function list_action() {
        $os = Wechatuser::all();
        $data = $this->packArray($os);
        return array("op" => "wechatusers", "data" => $data);
    }

    public function sync_action() {
        $os = Wechatuser::sync();
        $data = $this->packArray($os);
        return array("op" => "wechatusers", "data" => $data);
    }

    public function modify_action() {
        $id = get_request_assert("id");
        $comments = get_request_assert("comments");

        $o = Wechatuser::create($id);
        $o->setComments($comments);
        $o->save();
        return $this->op("modifywechatuser", $o->packInfo());
    }

}













