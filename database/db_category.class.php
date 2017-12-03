<?php

include_once(dirname(__FILE__) . "/../config.php");

class db_category extends database_table {

    const ROOT_MATERIAL = 1;
    const ROOT_PRODUCT = 2;
    const ROOT_CUSTOMER = 3;
    const ROOT_VENDOR = 4;

    private static $instance = null;
    public static function inst() {
        if (self::$instance == null)
            self::$instance = new db_category();
        return self::$instance;
    }

    private function db_category() {
        parent::database_table(MYSQL_DATABASE, MYSQL_PREFIX . "category");
    }

    public function get($id) {
        $id = (int)$id;
        return $this->get_one("id = $id");
    }

    public function all() {
        return $this->get_all();
    }
    
    public function add($name, $pid) {
        return $this->insert(array("pid" => $pid, "name" => $name));
    }

    public function modify($id, $name, $pid) {
        $id = (int)$id;
        return $this->update(array("pid" => $pid, "name" => $name), "id = $id");
    }

    public function remove($id) {
        $id = (int)$id;
        return $this->delete("id = $id");
    }
};


