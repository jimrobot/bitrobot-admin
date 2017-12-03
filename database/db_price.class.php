<?php

include_once(dirname(__FILE__) . "/../config.php");

class db_price extends database_table {
    private static $instance = null;
    public static function inst() {
        if (self::$instance == null)
            self::$instance = new db_price();
        return self::$instance;
    }

    private function db_price() {
        parent::database_table(MYSQL_DATABASE, MYSQL_PREFIX . "price");
    }

    public function get($pid, $cid) {
        $pid = (int)$pid;
        $cid = (int)$cid;
        return $this->get_one("product = $pid AND customer = $cid");
    }

    public function get_product_prices($pid) {
        $pid = (int)$pid;
        return $this->get_all("product = $pid");
    }

    public function get_customer_prices($cid) {
        $cid = (int)$cid;
        return $this->get_all("customer = $cid");
    }

    public function all() {
        return $this->get_all();
    }

    public function add($pid, $cid, $price) {
        $now = time(null);
        return $this->insert(array("customer" => $cid, "product" => $pid, "price" => $price, "refresh_time" => $now));
    }

    public function modify($pid, $cid, $price) {
        $pid = (int)$pid;
        $cid = (int)$cid;
        $now = time(null);
        return $this->update(array("price" => $price, "refresh_time" => $now), "product = $pid AND customer = $cid");
    }

    public function remove($pid, $cid) {
        $pid = (int)$pid;
        $cid = (int)$cid;
        return $this->delete("product = $pid AND customer = $cid");
    }
};



