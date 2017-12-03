<?php
include_once(dirname(__FILE__) . "/../../../config.php");
include_once(dirname(__FILE__) . "/../v1_base.php");

class productorder_controller extends v1_base {

    public function types_action() {
        login::assertPerm(Permission::kMaterialOrder);
        $arr = Productorder::types();
        return array("op" => "types", "data" => $arr);
    }

    public function list_action() {
        login::assertPerm(Permission::kMaterialOrder);
        $orders = Productorder::all();
        $data = $this->packArray($orders);
        return array("op" => "orders", "data" => $data);
    }

    public function orderinfo_action() {
        $serial = get_request_assert("serial");
        $order = Productorder::createBySerial($serial);
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
            Productorder::remove($serial);
        }
        $serial = Productorder::addOrder($type, $customer, $depot, $order, $comments, $serial);
        return array("op" => "neworder", "data" => $serial);
    }

    public function delete_action() {
        login::assertPerm(Permission::kMaterialOrderModify);
        $serial = get_request_assert("serial");
        $ret = Productorder::remove($serial);
        return $this->checkRet($ret);
    }

    public function ensure_action() {
        login::assertPerm(Permission::kMaterialOrderModify);
        $serial = get_request_assert("serial");
        $product = Productorder::createBySerial($serial);
        $ret = $product->ensure();
        return $this->checkRet($ret);
    }

    public function test_action() {
        return;
        $order = Productorder::create(20);
        logging::d("Debug", $order);
        // $order->notifyAdmin();
        $order->notifyCustomers();
        dump_var($order);
        return;
    }

}













