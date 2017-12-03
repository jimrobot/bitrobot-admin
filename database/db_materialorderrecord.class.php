<?php

include_once(dirname(__FILE__) . "/../config.php");

class db_materialorderrecord extends database_table {
    const STATUS_NORMAL = 0;
    const STATUS_DELETED = 1;

    private static $instance = null;
    public static function inst() {
        if (self::$instance == null)
            self::$instance = new db_materialorderrecord();
        return self::$instance;
    }

    private function db_materialorderrecord() {
        parent::database_table(MYSQL_DATABASE, MYSQL_PREFIX . "materialorderrecord");
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

    public function add($orderid, $materialid, $count, $totalprice) {
        return $this->insert(
            array(
                "orderid" => $orderid,
                "materialid" => $materialid,
                "count" => $count,
                "totalprice" => $totalprice,
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


