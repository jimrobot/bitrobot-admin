<?php
include_once(dirname(__FILE__) . "/../config.php");

class setting_controller {
    public function preaction($action) {
        login::assert();
    }

    public function index_action() {
        login::assertPerm(Permission::kSetting);
        $tpl = new tpl("header", "footer");
        // $tpl->set("settings", setting::instance()->load_all());
        $tpl->display("setting/index");
    }
}













