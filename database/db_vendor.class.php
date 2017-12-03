<?php

include_once(dirname(__FILE__) . "/../config.php");

class db_vendor extends database_table {
    const STATUS_NORMAL = 0;
    const STATUS_DELETED = 1;

    private static $instance = null;
    public static function inst() {
        if (self::$instance == null)
            self::$instance = new db_vendor();
        return self::$instance;
    }

    private function db_vendor() {
        parent::database_table(MYSQL_DATABASE, MYSQL_PREFIX . "vendor");
    }

    public function get($id) {
        $id = (int)$id;
        return $this->get_one("id = $id");
    }

    public function all() {
        return $this->get_all();
    }

    public function add($name, $title, $category, $telephone, $email, $address, $comments, $province, $city, $district) {
        return $this->insert(array("name" => $name, "title" => $title, "category" => $category, "telephone" => $telephone, "email" => $email, "address" => $address, "comments" => $comments, "province" => $province, "city" => $city, "district" => $district));
    }

    public function modify($id, $name, $title, $category, $telephone, $email, $address, $comments, $province, $city, $district) {
        $id = (int)$id;
        return $this->update(array("name" => $name, "title" => $title, "category" => $category, "telephone" => $telephone, "email" => $email, "address" => $address, "comments" => $comments, "province" => $province, "city" => $city, "district" => $district), "id = $id");
    }

    public function remove($id) {
        $id = (int)$id;
        return $this->update(array("status" => self::STATUS_DELETED), "id = $id");
    }

};


