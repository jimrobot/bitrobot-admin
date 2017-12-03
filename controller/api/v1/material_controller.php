<?php
include_once(dirname(__FILE__) . "/../../../config.php");
include_once(dirname(__FILE__) . "/../v1_base.php");

class material_controller extends v1_base {
    public function preaction($action) {
        login::assertPerm(Permission::kMaterial);
    }


    public function list_action() {
        $vendors = Material::all();
        $data = $this->packArray($vendors);
        return array("op" => "materials", "data" => $data);
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

        $o = new Material();
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
        $o->save();
        return $this->op("newmaterial", $o->packInfo());
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

        $o = Material::create($id);
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
        $o->save();
        return $this->op("modifymaterial", $o->packInfo());
    }

    public function remove_action() {
        $id = get_request_assert("id");
        $ret = Material::remove($id);
        return $this->checkRet($ret);
    }


}













