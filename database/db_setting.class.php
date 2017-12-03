<?php

include_once(dirname(__FILE__) . "/../config.php");

class db_setting extends database_table {
    private static $instance = null;
    public static function inst() {
        if (self::$instance == null)
            self::$instance = new db_setting();
        return self::$instance;
    }

    public function __construct() {
        parent::__construct(MYSQL_DATABASE, MYSQL_PREFIX . "setting");
    }

    public function load_all() {
        return $this->get_all();
    }

    public function load($key) {
        $key = $this->escape($key);
        return $this->get_one("name = $key");
    }

    public function save($key, $value) {
        $row = $this->load($key);
        if (empty($row)) {
            return $this->insert(array("name" => $key, "value" => $value));
        } else {
            $id = $row["id"];
            $ret = $this->update(array("value" => $value), "id = $id");
            return ($ret !== false) ? $id : $ret;
        }
    }

    public function modify($id, $value) {
        $id = (int)$id;
        return $this->update(array("value" => $value), "id = $id");
    }
};


