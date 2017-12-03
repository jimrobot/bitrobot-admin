<?php

include_once(dirname(__FILE__) . "/../config.php");

class db_user extends database_table {
    const STATUS_NORMAL = 0;
    const STATUS_DELETED = 1;
    const STATUS_LEAVE = 2;

    private static $instance = null;
    public static function inst() {
        if (self::$instance == null)
            self::$instance = new db_user();
        return self::$instance;
    }

    protected function __construct() {
        parent::database_table(MYSQL_DATABASE, MYSQL_PREFIX . "user");
    }

    public function get($id) {
        $id = (int)$id;
        return $this->get_one("id = $id");
    }

    public function all() {
        return $this->get_all();
    }


    public function add($username, $password, $nickname, $telephone, $email, $groups, $comments) {
        if (is_array($groups)) {
            $groups = implode(",", $groups);
        }
        $now = time(null);
        return $this->insert(array(
            "username" => $username,
            "password" => $password,
            "nickname" => $nickname,
            "telephone" => $telephone,
            "email" => $email,
            "groups" => $groups,
            "comments" => $comments,
            "create_time" => $now,
        ));
    }

    public function modify($id, $username, $password, $nickname, $telephone, $email, $groups, $comments) {
        $id = (int)$id;
        if (is_array($groups)) {
            $groups = implode(",", $groups);
        }
        return $this->update(array(
            "username" => $username,
            "password" => $password,
            "nickname" => $nickname,
            "telephone" => $telephone,
            "email" => $email,
            "groups" => $groups,
            "comments" => $comments,
        ), "id = $id");
    }

    public function leave($id) {
        $id = (int)$id;
        return $this->update(array("status" => self::STATUS_LEAVE), "id = $id");
    }

    public function remove($id) {
        $id = (int)$id;
        return $this->update(array("status" => self::STATUS_DELETED), "id = $id");
    }

};


