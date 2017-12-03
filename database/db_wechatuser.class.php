<?php

include_once(dirname(__FILE__) . "/../config.php");

class db_wechatuser extends database_table {
    const STATUS_NORMAL = 0;
    const STATUS_DELETED = 1;

    private static $instance = null;
    public static function inst() {
        if (self::$instance == null)
            self::$instance = new db_wechatuser();
        return self::$instance;
    }

    private function db_wechatuser() {
        parent::database_table(MYSQL_DATABASE, MYSQL_PREFIX . "wechatuser");
    }

    public function get($id) {
        $id = (int)$id;
        return $this->get_one("id = $id");
    }

    public function all() {
        return $this->get_all();
    }

    public function add($openid, $nickname, $headimgurl, $gender, $city, $province, $country, $subscribe, $remark, $groupid, $tagid_list, $comments) {
        return $this->insert(
            array(
                "openid" => $openid,
                "nickname" => $nickname,
                "headimgurl" => $headimgurl,
                "sex" => $gender,
                "city" => $city,
                "province" => $province,
                "country" => $country,
                "subscribe" => $subscribe,
                "remark" => $remark,
                "groupid" => $groupid,
                "tagid_list" => $tagid_list,
                "comments" => $comments,
                "status" => self::STATUS_NORMAL, 
            )
        );
    }

    public function modify($id, $openid, $nickname, $headimgurl, $gender, $city, $province, $country, $subscribe, $remark, $groupid, $tagid_list, $comments) {
        $id = (int)$id;
        return $this->update(
            array(
                "openid" => $openid,
                "nickname" => $nickname,
                "headimgurl" => $headimgurl,
                "sex" => $gender,
                "city" => $city,
                "province" => $province,
                "country" => $country,
                "subscribe" => $subscribe,
                "remark" => $remark,
                "groupid" => $groupid,
                "tagid_list" => $tagid_list,
                "comments" => $comments,
            ),
            "id = $id"
        );
    }

    public function remove($id) {
        $id = (int)$id;
        // return $this->update(array("status" => self::STATUS_DELETED), "id = $id");
        return $this->delete("id = $id");
    }
};


