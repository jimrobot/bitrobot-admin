<?php
include_once(dirname(__FILE__) . "/../config.php");

class admin_controller {
    public function preaction($action) {
        // login::assert();
    }

    public function index_action() {
        $tpl = new tpl();
        $tpl->display('login/login');
    }

    public function user_action() {
        $tpl = new tpl("header", "footer");
        $tpl->display("admin/user");
    }

    public function files_action() {
        $tpl = new tpl("header", "footer");
        $tpl->display("admin/files");
    }

    public function setting_action() {
        $tpl = new tpl("header", "footer");
        $tpl->display("admin/setting");

    }

}













