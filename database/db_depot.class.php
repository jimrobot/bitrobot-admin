<?php

include_once(dirname(__FILE__) . "/../config.php");

class db_depot extends database_table {
    const STATUS_NORMAL = 0;
    const STATUS_DELETED = 1;
    const STATUS_DISABLED = 2;

    private static $instance = null;
    public static function inst() {
        if (self::$instance == null)
            self::$instance = new db_depot();
        return self::$instance;
    }

    private function db_depot() {
        parent::database_table(MYSQL_DATABASE, MYSQL_PREFIX . "depot");
    }

    public function get($id) {
        $id = (int)$id;
        return $this->get_one("id = $id");
    }

    public function all() {
        return $this->get_all();
    }

    public function add($name, $address, $manager, $category, $comments) {
        return $this->insert(array("name" => $name, "address" => $address, "manager" => $manager, "category" => $category, "comments" => $commets));
    }

    public function modify($id, $name, $address, $manager, $category, $comments) {
        $id = (int)$id;
        return $this->update(array("name" => $name, "address" => $address, "manager" => $manager, "category" => $category, "comments" => $comments), "id = $id");
    }

    public function remove($id) {
        $id = (int)$id;
        return $this->update(array("status" => self::STATUS_DELETED), "id = $id");
    }

};


