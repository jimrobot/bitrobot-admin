<?php

include_once(dirname(__FILE__) . "/../config.php");

class db_salesman extends database_table {
    const STATUS_NORMAL = 0;
    const STATUS_DELETED = 1;
    const GENDER_MALE = 0;
    const GENDER_FEMALE = 0;

    private static $instance = null;
    public static function inst() {
        if (self::$instance == null)
            self::$instance = new db_salesman();
        return self::$instance;
    }

    private function db_salesman() {
        parent::database_table(MYSQL_DATABASE, MYSQL_PREFIX . "salesman");
    }

    public function get($id) {
        $id = (int)$id;
        return $this->get_one("id = $id");
    }

    public function all() {
        return $this->get_all();
    }

    public function add($name, $telephone, $gender, $category, $comments) {
        return $this->insert(array("name" => $name, "telephone" => $telephone, "gender" => $gender, "category" => $category, "comments" => $comments));
    }

    public function modify($id, $name, $telephone, $gender, $category, $comments) {
        $id = (int)$id;
        return $this->update(array("name" => $name, "telephone" => $telephone, "gender" => $gender, "category" => $category, "comments" => $comments), "id = $id");
    }

    public function remove($id) {
        $id = (int)$id;
        $now = time(null);
        return $this->update(array("status" => self::STATUS_DELETED, "leave_date" => $now), "id = $id");
    }

};


