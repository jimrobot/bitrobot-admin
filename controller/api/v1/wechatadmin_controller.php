<?php
include_once(dirname(__FILE__) . "/../../../config.php");
include_once(dirname(__FILE__) . "/../v1_base.php");

class wechatadmin_controller extends v1_base {
    public function preaction($action) {
        login::assertPerm(Permission::kWechatAdmin);
    }


    public function list_action() {
        $os = Wechatadmin::all();
        $data = $this->packArray($os);
        return array("op" => "wechatadmins", "data" => $data);
    }

    public function add_action() {
        $wechatuser = get_request_assert("wechatuser");
        $notify = get_request_assert("notify");

        $o = new Wechatadmin();
        $o->setWechatuser($wechatuser);
        $o->setNotify($notify);
        $o->save();
        return $this->op("modifywechatuser", $o->packInfo());

    }

    public function modify_action() {
        $weid = get_request_assert("weid");
        $notify = get_request_assert("notify");

        $o = Wechatadmin::createByWeid($weid);
        $o->setNotify($notify);
        $o->save();
        return $this->op("modifywechatuser", $o->packInfo());
    }

    public function remove_action() {
        $weid = get_request_assert("weid");
        $ret = Wechatadmin::remove($weid);
        return $this->checkRet($ret);
    }

}













