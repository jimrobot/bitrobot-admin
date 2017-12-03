<?php
include_once(dirname(__FILE__) . "/../config.php");

class business_controller {
    public function preaction($action) {
        login::assert();
    }

    public function pricecustomer_action() {
        login::assertPerm(Permission::kCustomerPrice);
        $tpl = new tpl("header", "footer");
        $tpl->display("business/pricecustomer");
    }

    public function wechatuser_action() {
        login::assertPerm(Permission::kWechatUser);
        $tpl = new tpl("header", "footer");
        $tpl->display("business/wechatuser");
    }

    public function wechatcustomer_action() {
        login::assertPerm(Permission::kWechatNotify);
        $tpl = new tpl("header", "footer");
        $tpl->display("business/wechatcustomer");
    }

    public function wechatadmin_action() {
        login::assertPerm(Permission::kWechatAdmin);
        $tpl = new tpl("header", "footer");
        $tpl->display("business/wechatadmin");
    }

}













