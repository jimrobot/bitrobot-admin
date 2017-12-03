<?php
include_once(dirname(__FILE__) . "/../../../config.php");
include_once(dirname(__FILE__) . "/../v1_base.php");

class materialorder_controller extends v1_base {
    public function types_action() {
        login::assertPerm(Permission::kMaterialOrder);
        $arr = Materialorder::types();
        return array("op" => "types", "data" => $arr);
    }

    public function list_action() {
        login::assertPerm(Permission::kMaterialOrder);
        $orders = Materialorder::all();
        $data = $this->packArray($orders);
        return array("op" => "orders", "data" => $data);
    }

    public function orderinfo_action() {
        login::assertPerm(Permission::kMaterialOrder);
        $serial = get_request_assert("serial");
        $order = Materialorder::createBySerial($serial);
        return array("op" => "orderinfo", "data" => $order->packInfo(true));
    }

    public function add_action() {
        login::assertPerm(Permission::kMaterialOrderModify);
        $serial = get_request_assert("serial");
        $type = get_request_assert("type");
        $customer = get_request_assert("customer");
        $order = get_request_assert("order");
        $depot = get_request_assert("depot");
        $comments = get_request_assert("comments");

        if (!empty($serial)) {
            Materialorder::remove($serial);
        }
        $serial = Materialorder::addOrder($type, $customer, $depot, $order, $comments, $serial);
        return array("op" => "neworder", "data" => $serial);
    }

    public function delete_action() {
        login::assertPerm(Permission::kMaterialOrderModify);
        $serial = get_request_assert("serial");
        $ret = Materialorder::remove($serial);
        return $this->checkRet($ret);
    }

    public function ensure_action() {
        login::assertPerm(Permission::kMaterialOrderModify);
        $serial = get_request_assert("serial");
        $material = Materialorder::createBySerial($serial);
        $ret = $material->ensure();
        return $this->checkRet($ret);
    }

}













