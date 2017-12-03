<?php

include_once(dirname(__FILE__) . "/../config.php");

class db_group extends database_table {
    private static $instance = null;
    public static function inst() {
        if (self::$instance == null)
            self::$instance = new db_group();
        return self::$instance;
    }

    private function db_group() {
        parent::database_table(MYSQL_DATABASE, MYSQL_PREFIX . "group");
    }

    public function get($gid) {
        $gid = (int)$gid;
        return $this->get_one("id = $gid");
    }

    public function all() {
        return $this->get_all();
    }

    public function add($name, $permissions) {
        if (is_array($permissions)) {
            $permissions = implode(",", $permissions);
        }
        return $this->insert(array("name" => $name, "permissions" => $permissions));
    }

    public function modify($id, $name, $permissions) {
        $id = (int)$id;
        if (is_array($permissions)) {
            $permissions = implode(",", $permissions);
        }
        return $this->update(array("name" => $name, "permissions" => $permissions), "id = $id");
    }

    public function remove($id) {
        $id = (int)$id;
        return $this->delete("id = $id");
    }
};


