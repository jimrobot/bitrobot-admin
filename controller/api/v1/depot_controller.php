<?php
include_once(dirname(__FILE__) . "/../../../config.php");
include_once(dirname(__FILE__) . "/../v1_base.php");

class depot_controller extends v1_base {
    public function preaction($action) {
        login::assertPerm(Permission::kDepot);
    }


    public function list_action() {
        $depots = Depot::all();
        $data = $this->packArray($depots);
        return array("op" => "depots", "data" => $data);
    }

    public function add_action() {
        $name = get_request_assert("name");
        $address = get_request_assert("address");
        $manager = get_request_assert("manager");
        $category = get_request_assert("category");
        $comments = get_request_assert("comments");

        $depot = new Depot();
        $depot->setName($name);
        $depot->setAddress($address);
        $depot->setManager($manager);
        $depot->setCategory($category);
        $depot->setComments($comments);
        $depot->save();
        return $this->op("newdepot", $depot->packInfo());
    }

    public function modify_action() {
        $id = get_request_assert("id");
        $name = get_request_assert("name");
        $address = get_request_assert("address");
        $manager = get_request_assert("manager");
        $category = get_request_assert("category");
        $comments = get_request_assert("comments");

        $depot = Depot::create($id);
        $depot->setName($name);
        $depot->setAddress($address);
        $depot->setManager($manager);
        $depot->setCategory($category);
        $depot->setComments($comments);
        $depot->save();
        return $this->op("modifydepot", $depot->packInfo());
    }

    public function remove_action() {
        $id = get_request_assert("id");
        $ret = Depot::remove($id);
        return $this->checkRet($ret);
    }
}













