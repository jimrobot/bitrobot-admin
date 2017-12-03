<?php
include_once(dirname(__FILE__) . "/../../../config.php");
include_once(dirname(__FILE__) . "/../v1_base.php");

class price_controller extends v1_base {
    public function preaction($action) {
        login::assertPerm(Permission::kCustomerPrice);
    }


    public function list_action() {
        $prices = Price::instance()->packInfo();
        return array("op" => "prices", "data" => $prices);
    }

    public function update_action() {
        $pid = get_request_assert("pid");
        $cid = get_request_assert("cid");
        $price = get_request_assert("price");

        Price::instance()->update($pid, $cid, $price);
        return $this->list_action();
    }

    public function remove_action() {
        $pid = get_request_assert("pid");
        $cid = get_request_assert("cid");
        Price::instance()->remove($pid, $cid);
        return $this->list_action();
    }
}













