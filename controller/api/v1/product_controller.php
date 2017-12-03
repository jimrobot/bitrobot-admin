<?php
include_once(dirname(__FILE__) . "/../../../config.php");
include_once(dirname(__FILE__) . "/../v1_base.php");

class product_controller extends v1_base {
    public function preaction($action) {
        login::assertPerm(Permission::kProduct);
    }


    public function list_action() {
        $products = Product::all();
        $data = $this->packArray($products);
        return array("op" => "products", "data" => $data);
    }

    public function add_action() {
        $name = get_request_assert("name");
        $category = get_request_assert("category");
        $unit = get_request_assert("unit");
        $standard = get_request_assert("standard");
        $barcode = get_request_assert("barcode");
        $qrcode = get_request_assert("qrcode");
        $serial = get_request_assert("serial");
        $vendor = get_request_assert("vendor");
        $mode = get_request_assert("mode");
        $comments = get_request_assert("comments");
        $price = get_request_assert("price");

        $o = new Product();
        $o->setName($name);
        $o->setTitle($name);
        $o->setCategory($category);
        $o->setUnit($unit);
        $o->setStandard($standard);
        $o->setBarcode($barcode);
        $o->setQrcode($qrcode);
        $o->setSerial($serial);
        $o->setVendor($vendor);
        $o->setMode($mode);
        $o->setComments($comments);
        $o->setPrice($price);
        $o->save();
        return $this->op("newproduct", $o->packInfo());
    }

    public function modify_action() {
        $id = get_request_assert("id");
        $name = get_request_assert("name");
        $category = get_request_assert("category");
        $unit = get_request_assert("unit");
        $standard = get_request_assert("standard");
        $barcode = get_request_assert("barcode");
        $qrcode = get_request_assert("qrcode");
        $serial = get_request_assert("serial");
        $vendor = get_request_assert("vendor");
        $mode = get_request_assert("mode");
        $comments = get_request_assert("comments");
        $price = get_request_assert("price");

        $o = Product::create($id);
        $o->setTitle($name);
        $o->setCategory($category);
        $o->setUnit($unit);
        $o->setStandard($standard);
        $o->setBarcode($barcode);
        $o->setQrcode($qrcode);
        $o->setSerial($serial);
        $o->setVendor($vendor);
        $o->setMode($mode);
        $o->setComments($comments);
        $o->setPrice($price);
        $o->save();
        return $this->op("modifyproduct", $o->packInfo());
    }

    public function remove_action() {
        $id = get_request_assert("id");
        $ret = Product::remove($id);
        return $this->checkRet($ret);
    }


}













