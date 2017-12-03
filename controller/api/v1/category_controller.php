<?php
include_once(dirname(__FILE__) . "/../../../config.php");
include_once(dirname(__FILE__) . "/../v1_base.php");

class category_controller extends v1_base {
    public function preaction($action) {
        login::assertPerm(Permission::kCategory);
    }

    public function list_action() {
        $root = Category::makeTree();
        return array("op" => "categories", "data" => $root->packInfo(true));
    }

    public function add_action() {
        $name = get_request_assert("name");
        $pid = get_request_assert("pid");
        if ($pid == 0) {
            return $this->checkRet(false);
        }

        $category = new Category();
        $category->setName($name);
        $category->setPid($pid);
        $category->save();
        return $this->op("newcategory", $category->packInfo());
    }

    public function modify_action() {
        $id = get_request_assert("id");
        $name = get_request_assert("name");

        $category = Category::create($id);
        if ($category->pid() == 0) {
            return $this->checkRet(false);
        }

        $category->setName($name);
        $category->save();
        return $this->op("modifycategory", $category->packInfo());
    }

    public function remove_action() {
        $id = get_request_assert("id");
        $ret = Category::remove($id);
        return $this->checkRet($ret);
    }

}













