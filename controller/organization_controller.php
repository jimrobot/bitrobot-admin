<?php
include_once(dirname(__FILE__) . "/../config.php");

class organization_controller {
    public function preaction($action) {
        login::assert();
    }

    public function category_action() {
        login::assertPerm(Permission::kCategory);
        $tpl = new tpl("header", "footer");
        $tpl->display("organization/category");
    }

    public function product_action() {
        login::assertPerm(Permission::kProduct);
        $tpl = new tpl("header", "footer");
        $tpl->display("organization/product");
    }

    public function material_action() {
        login::assertPerm(Permission::kMaterial);
        $tpl = new tpl("header", "footer");
        $tpl->display("organization/material");
    }

    public function depot_action() {
        login::assertPerm(Permission::kDepot);
        $tpl = new tpl("header", "footer");
        $tpl->display("organization/depot");
    }

    public function salesman_action() {
        login::assertPerm(Permission::kSalesman);
        $tpl = new tpl("header", "footer");
        $tpl->display("organization/salesman");
    }

    public function customer_action() {
        login::assertPerm(Permission::kCustomer);
        $tpl = new tpl("header", "footer");
        $tpl->display("organization/customer");
    }

    public function vendor_action() {
        login::assertPerm(Permission::kVendor);
        $tpl = new tpl("header", "footer");
        $tpl->display("organization/vendor");
    }


}













