<?php

include_once(dirname(__FILE__) . "/../config.php");

class db_wechatadmin extends database_table {

    private static $instance = null;
    public static function inst() {
        if (self::$instance == null)
            self::$instance = new db_wechatadmin();
        return self::$instance;
    }

    private function db_wechatadmin() {
        parent::database_table(MYSQL_DATABASE, MYSQL_PREFIX . "wechatadmin");
    }

    public function get($id) {
        $id = (int)$id;
        return $this->get_one("id = $id");
    }

    public function getByWeid($weid) {
        $weid = (int)$weid;
        return $this->get_one("wechatuser = $weid");
    }

    public function all() {
        return $this->get_all();
    }

    public function add($wechatuser, $notify) {
        return $this->insert(array("wechatuser" => $wechatuser, "notify" => $notify));
    }

    public function modify($id, $wechatuser, $notify) {
        $id = (int)$id;
        return $this->update(array("wechatuser" => $wechatuser, "notify" => $notify), "id = $id");
    }

    public function remove($id) {
        $id = (int)$id;
        return $this->delete("id = $id");
    }

    public function removeByWeid($id) {
        $id = (int)$id;
        return $this->delete("wechatuser = $id");
    }



};


