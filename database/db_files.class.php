<?php

include_once(dirname(__FILE__) . "/../config.php");

class db_files extends database_table {
    const STATUS_NORMAL = 0;
    const STATUS_DELETED = 1;

    private static $instance = null;
    public static function inst() {
        if (self::$instance == null)
            self::$instance = new db_files();
        return self::$instance;
    }

    protected function __construct() {
        parent::__construct(MYSQL_DATABASE, MYSQL_PREFIX . "files");
    }

    public function get($id) {
        $id = (int)$id;
        return $this->get_one("id = $id");
    }

    public function all() {
        return $this->get_all();
    }

    public function add($filename, $title, $comments, $path) {
        return $this->insert(array("filename" => $filename, "title" => $title, "comments" => $comments, "path" => $path, "status" => self::STATUS_NORMAL));
    }

    public function modify($id, $filename, $title, $comments) {
        $id = (int)$id;
        return $this->update(array("filename" => $filename, "title" => $title, "comments" => $comments), "id = $id");
    }

    public function remove($id) {
        $id = (int)$id;
        return $this->update(array("status" => self::STATUS_DELETED), "id = $id");
    }


};


