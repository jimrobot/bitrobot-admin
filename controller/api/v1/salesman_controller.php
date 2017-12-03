<?php
include_once(dirname(__FILE__) . "/../../../config.php");
include_once(dirname(__FILE__) . "/../v1_base.php");

class salesman_controller extends v1_base {
    public function preaction($action) {
        login::assertPerm(Permission::kSalesman);
    }


    public function list_action() {
        $salesman = Salesman::all();
        $data = $this->packArray($salesman);
        return array("op" => "salesman", "data" => $data);
    }

    public function add_action() {
        $name = get_request_assert("name");
        $telephone = get_request_assert("telephone");
        $gender = get_request_assert("gender");
        $category = get_request_assert("category");
        $comments = get_request_assert("comments");

        $o = new Salesman();
        $o->setName($name);
        $o->setTelephone($telephone);
        $o->setGender($gender);
        $o->setCategory($category);
        $o->setComments($comments);
        $o->save();
        return $this->op("newsalesman", $o->packInfo());
    }

    public function modify_action() {
        $id = get_request_assert("id");
        $name = get_request_assert("name");
        $telephone = get_request_assert("telephone");
        $gender = get_request_assert("gender");
        $category = get_request_assert("category");
        $comments = get_request_assert("comments");

        $o = Salesman::create($id);
        $o->setName($name);
        $o->setTelephone($telephone);
        $o->setGender($gender);
        $o->setCategory($category);
        $o->setComments($comments);
        $o->save();
        return $this->op("modifysalesman", $o->packInfo());
    }

    public function remove_action() {
        $id = get_request_assert("id");
        $ret = Salesman::remove($id);
        return $this->checkRet($ret);
    }
}













