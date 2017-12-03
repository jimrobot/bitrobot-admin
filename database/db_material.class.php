<?php

include_once(dirname(__FILE__) . "/../config.php");

class db_material extends database_table {
    const STATUS_NORMAL = 0;
    const STATUS_DELETED = 1;

    private static $instance = null;
    public static function inst() {
        if (self::$instance == null)
            self::$instance = new db_material();
        return self::$instance;
    }

    private function db_material() {
        parent::database_table(MYSQL_DATABASE, MYSQL_PREFIX . "material");
    }

    public function get($id) {
        $id = (int)$id;
        return $this->get_one("id = $id");
    }

    public function all() {
        return $this->get_all();
    }

    public function add($name, $title, $category, $unit, $standard, $barcode, $qrcode, $serial, $vendor, $mode, $comments) {
        return $this->insert(
            array(
                "name" => $name,
                "title" => $title,
                "category" => $category,
                "unit" => $unit,
                "standard" => $standard,
                "barcode" => $barcode,
                "qrcode" => $qrcode,
                "serial" => $serial,
                "vendor" => $vendor,
                "mode" => $mode,
                "comments" => $comments,
            )
        );
    }

    public function modify($id, $name, $title, $category, $unit, $standard, $barcode, $qrcode, $serial, $vendor, $mode, $comments) {
        $id = (int)$id;
        return $this->update(array(
            "name" => $name,
            "title" => $title,
            "category" => $category,
            "unit" => $unit,
            "standard" => $standard,
            "barcode" => $barcode,
            "qrcode" => $qrcode,
            "serial" => $serial,
            "vendor" => $vendor,
            "mode" => $mode,
            "comments" => $comments,
        ), "id = $id");

    }

    public function remove($id) {
        $id = (int)$id;
        return $this->update(array("status" => self::STATUS_DELETED), "id = $id");
    }



};


