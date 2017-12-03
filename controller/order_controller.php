<?php
include_once(dirname(__FILE__) . "/../config.php");

class order_controller {
    public function preaction($action) {
        if ($action != "pprint_action" && $action != "mprint_action" && $action != "print_action") {
            login::assert();
        }
    }

    public function product_action() {
        login::assertPerm(Permission::kProductOrder);
        $tpl = new tpl("header", "footer");
        $tpl->display("order/product");
    }

    public function material_action() {
        login::assertPerm(Permission::kMaterialOrder);
        $tpl = new tpl("header", "footer");
        $tpl->display("order/material");
    }

    public function newproduct_action() {
        login::assertPerm(Permission::kProductOrderModify);
        $tpl = new tpl("header", "footer");
        $tpl->set("serial", "");
        $tpl->display("order/newproduct");
    }

    public function newmaterial_action() {
        login::assertPerm(Permission::kMaterialOrderModify);
        $tpl = new tpl("header", "footer");
        $tpl->set("serial", "");
        $tpl->display("order/newmaterial");
    }

    public function modifyproduct_action() {
        login::assertPerm(Permission::kProductOrderModify);
        $serial = get_request_assert("serial");
        $tpl = new tpl("header", "footer");
        $tpl->set("serial", $serial);
        $tpl->display("order/newproduct");
    }

    public function modifymaterial_action() {
        login::assertPerm(Permission::kMaterialOrderModify);
        $serial = get_request_assert("serial");
        $tpl = new tpl("header", "footer");
        $tpl->set("serial", $serial);
        $tpl->display("order/newmaterial");
    }

    public function pprint_action() {
        $serial = get_request_assert('s');
        $order = Productorder::createBySerial($serial);
        $showqrcode = setting::instance()->load("KEY_SHOW_QRCODE_IN_ORDER");
        $tpl = new tpl();
        $tpl->set("order", $order);
        $tpl->set("stuff", "product");
        if ($showqrcode == 1) {
            $tpl->display("order/pprint");
        } else {
            $tpl->display("order/pprint_without_qrcode");
        }
    }

    public function mprint_action() {
        $serial = get_request_assert('s');
        $order = Materialorder::createBySerial($serial);
        logging::d("Debug", $order);
        $tpl = new tpl();
        $tpl->set("order", $order);
        $tpl->set("stuff", "material");
        $tpl->display("order/mprint");
    }

    public function print_action() {
        $serial = get_request_assert('s');
        $order = Productorder::createBySerial($serial);
        $showqrcode = setting::instance()->load("KEY_SHOW_QRCODE_IN_ORDER");
        $tpl = new tpl();
        $tpl->set("order", $order);
        $tpl->set("stuff", "product");
        if ($showqrcode == 1) {
            $tpl->display("order/cprint");
        } else {
            $tpl->display("order/cprint_without_qrcode");
        }
    }

}













