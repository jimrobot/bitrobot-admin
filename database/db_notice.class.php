<?php

include_once(dirname(__FILE__) . "/../config.php");

class db_notice extends database_table {
    private static $instance = null;
    public static function inst() {
        if (self::$instance == null)
            self::$instance = new db_notice();
        return self::$instance;
    }

    private function db_notice() {
        parent::database_table(MYSQL_DATABASE, MYSQL_PREFIX . "notice");
    }

    public function get_customer_notices($cid) {
        $cid = (int)$cid;
        return $this->get_all("cid = $cid");
    }

    public function all() {
        return $this->get_all();
    }

    public function add($cid, $wid, $notice) {
        return $this->insert(array("cid" => $cid, "wid" => $wid, "notice" => $notice));
    }

    public function modify($cid, $wid, $notice) {
        $cid = (int)$cid;
        $wid = (int)$wid;
        return $this->update(array("notice" => $notice), "cid = $cid AND wid = $wid");
    }

    public function remove($cid, $wid) {
        $cid = (int)$cid;
        $wid = (int)$wid;
        return $this->delete("cid = $cid AND wid = $wid");
    }
};



