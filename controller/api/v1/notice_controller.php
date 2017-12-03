<?php
include_once(dirname(__FILE__) . "/../../../config.php");
include_once(dirname(__FILE__) . "/../v1_base.php");

class notice_controller extends v1_base {
    public function preaction($action) {
        login::assertPerm(Permission::kWechatNotify);
    }


    public function list_action() {
        $notices = Notice::instance()->packInfo();
        return array("op" => "notices", "data" => $notices);
    }

    public function update_action() {
        $cid = get_request_assert("cid");
        $wid = get_request_assert("wid");
        $notice = get_request_assert("notice");

        Notice::instance()->update($cid, $wid, $notice);
        return $this->list_action();
    }

    public function remove_action() {
        $cid = get_request_assert("cid");
        $wid = get_request_assert("wid");
        Notice::instance()->remove($cid, $wid);
        return $this->list_action();
    }
}













