<?php

include_once(dirname(__FILE__) . "/../config.php");

class db_productorderrecord extends database_table {
    const STATUS_NORMAL = 0;
    const STATUS_DELETED = 1;

    private static $instance = null;
    public static function inst() {
        if (self::$instance == null)
            self::$instance = new db_productorderrecord();
        return self::$instance;
    }

    private function db_productorderrecord() {
        parent::database_table(MYSQL_DATABASE, MYSQL_PREFIX . "productorderrecord");
    }

    public function get($id) {
        $id = (int)$id;
        return $this->get_one("id = $id");
    }

    public function all($orderid = 0) {
        if ($orderid == 0) {
            return $this->get_all();
        } else {
            $orderid = (int)$orderid;
            return $this->get_all("orderid = $orderid");
        }
    }

    public function add($orderid, $serial, $productid, $name, $title, $unit, $standard, $mode, $comments, $count, $largess) {
        return $this->insert(
            array(
                "orderid" => $orderid,
                "serial" => $serial,
                "productid" => $productid,
                "name" => $name,
                "title" => $title,
                "unit" => $unit,
                "standard" => $standard,
                "count" => $count,
                "largess" => $largess,
                "comments" => $comments,
                "mode" => $mode,
            )
        );
    }

    public function update_price($id, $price) {
        $id = (int)$id;
        return $this->update(array("finalprice" => $price), "id = $id");
    }

    public function removeByOrderId($id) {
        $id = (int)$id;
        return $this->delete("orderid = $id");
    }


};


