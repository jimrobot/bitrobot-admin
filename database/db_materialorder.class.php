<?php

include_once(dirname(__FILE__) . "/../config.php");

class db_materialorder extends database_table {
    const STATUS_NORMAL = 0;
    const STATUS_DELETED = 1;
    const STATUS_ENSURED = 2;


    const TYPE_NOTYPE = 0;

    const TYPE_SALES = -1;
    const TYPE_PRODUCTION = -2;
    const TYPE_PURCHASEREFUND = -3;
    const TYPE_PURCHASE = 1;
    const TYPE_SALESREFUND = 2;

    private static $instance = null;
    public static function inst() {
        if (self::$instance == null)
            self::$instance = new db_materialorder();
        return self::$instance;
    }

    private function db_materialorder() {
        parent::database_table(MYSQL_DATABASE, MYSQL_PREFIX . "materialorder");
    }

    public function get($id) {
        $id = (int)$id;
        return $this->get_one("id = $id");
    }

    public function find($serial) {
        $serial = $this->escape($serial);
        return $this->get_one("serial = $serial");
    }

    public function all() {
        return $this->get_all("", "ORDER BY id DESC");
    }

    public function has($serial) {
        $serial = $this->escape($serial);
        $ret = $this->get_one("serial = $serial");
        return (!empty($ret));
    }

    public function add($serial, $depot1, $depot2, $customer, $operator, $operatorName, $comments, $type) {
        $now = time(null);
        return $this->insert(array("serial" => $serial, "depot1" => $depot1, "depot2" => $depot2, "customer" => $customer, "operator" => $operator, "operatorname" => $operatorName, "comments" => $comments, "type" => "$type", "ordertime" => $now));
    }

    public function ensure($id, $totalprice) {
        $id = (int)$id;
        $now = time(null);
        return $this->update(array("totalprice" => $totalprice, "ensuretime" => $now, "status" => self::STATUS_ENSURED), "id = $id");
    }

    public function remove($id) {
        $id = (int)$id;
        return $this->delete("id = $id");
    }


};


