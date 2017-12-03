<?php
include_once(dirname(__FILE__) . "/../../../config.php");
include_once(dirname(__FILE__) . "/../v1_base.php");

class login_controller extends v1_base {
    public function salt_action() {
        $salt = login::instance()->salt();
        return array("op" => "salt", "data" => $salt);
    }

    public function login_action() {
        $username = get_request_assert("username");
        $cipher = get_request_assert("cipher");
        $ret = login::instance()->do_login($username, $cipher);
        return array("op" => "login", "data" => $ret);
    }
}













