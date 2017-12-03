<?php
include_once(dirname(__FILE__) . "/../config.php");

class user_controller {
    public function preaction($action) {
        //login::assert();
    }

    public function user_action() {
        // login::assertPerm(Permission::kUser);
        $tpl = new tpl("header", "footer");
        $tpl->display("user/user");
    }

    public function group_action() {
        // login::assertPerm(Permission::kGroup);
        $tpl = new tpl("header", "footer");
        $tpl->set("groups", Group::all());
        $tpl->display("user/group");
    }
}













