<?php
include_once(dirname(__FILE__) . "/../../../config.php");
include_once(dirname(__FILE__) . "/../v1_base.php");

class customer_controller extends v1_base {
    public function preaction($action) {
        login::assertPerm(Permission::kCustomer);
    }


    public function list_action() {
        $customers = Customer::all();
        $data = $this->packArray($customers);
        return array("op" => "customers", "data" => $data);
    }

    public function add_action() {
        $name = get_request_assert("name");
        $title = get_request_assert("title");
        $category = get_request_assert("category");
        $telephone = get_request_assert("telephone");
        $email = get_request_assert("email");
        $address = get_request_assert("address");
        $comments = get_request_assert("comments");
        $province = get_request_assert("province");
        $city = get_request_assert("city");
        $district = get_request_assert("district");

        $o = new Customer();
        $o->setName($name);
        $o->setTitle($title);
        $o->setCategory($category);
        $o->setTelephone($telephone);
        $o->setEmail($email);
        $o->setAddress($address);
        $o->setComments($comments);
        $o->setProvince($province);
        $o->setCity($city);
        $o->setDistrict($district);
        $o->save();
        return $this->op("newcustomer", $o->packInfo());
    }

    public function modify_action() {
        $id = get_request_assert("id");
        $name = get_request_assert("name");
        $title = get_request_assert("title");
        $category = get_request_assert("category");
        $telephone = get_request_assert("telephone");
        $email = get_request_assert("email");
        $address = get_request_assert("address");
        $comments = get_request_assert("comments");
        $province = get_request_assert("province");
        $city = get_request_assert("city");
        $district = get_request_assert("district");


        $o = Customer::create($id);
        $o->setName($name);
        $o->setTitle($title);
        $o->setCategory($category);
        $o->setTelephone($telephone);
        $o->setEmail($email);
        $o->setAddress($address);
        $o->setComments($comments);
        $o->setProvince($province);
        $o->setCity($city);
        $o->setDistrict($district);
        $o->save();
        return $this->op("modifycustomer", $o->packInfo());
    }

    public function remove_action() {
        $id = get_request_assert("id");
        $ret = Customer::remove($id);
        return $this->checkRet($ret);
    }

}













