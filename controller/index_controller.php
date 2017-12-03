<?php
include_once(dirname(__FILE__) . "/../config.php");

class index_controller {
    public function preaction($action) {
        if ($action != "login_action" && $action != "logout_action") {
            login::assert();
        }
    }

    public function index_action() {
        $tpl = new tpl("header", "footer");
        $tpl->display("index/index");
    }

    public function login_action() {
        $tpl = new tpl();
        $tpl->display("login/login");
    }

    public function logout_action() {
        login::instance()->bye();
        return $this->login_action();
    }
}













