<?php
include_once(dirname(__FILE__) . "/../config.php");

class Files {
    private $mSummary = null;
    // private $mGroups = null;

    public function __construct($summary = array()) {
        if (empty($summary)) {
            $summary = array(
                "id" => 0,
                "filename" => "",
                "path" => "",
                "title" => "",
                "commments" => "",
            );
        }
        $this->mSummary = $summary;
    }

    public function id() {
        return $this->mSummary["id"];
    }

    public function filename() {
        return $this->mSummary["filename"];
    }

    public function path() {
        return $this->mSummary["path"];
    }

    public function title() {
        return $this->mSummary["title"];
    }

    public function comments() {
        return $this->mSummary["comments"];
    }

    public function url() {
    }

    public function setFilename($n) {
        $this->mSummary["filename"] = $n;
    }

    public function setPath($p) {
        $this->mSummary["path"] = $p;
    }

    public function setTitle($n) {
        $this->mSummary["title"] = $n;
    }

    public function setComments($c) {
        $this->mSummary["comments"] = $c;
    }

    public function save() {
        $id = $this->id();
        if ($id == 0) {
            $id = db_files::inst()->add($this->filename(), $this->title(), $this->comments(), $this->path());
            if ($id !== false) {
                $this->mSummary["id"] = $id;
            }
        } else {
            $id = db_files::inst()->modify($id, $this->filename(), $this->title(), $this->comments());
        }
        return $id;
    }

    // private static function cachedAllGroups() {
    //     $cache = cache::instance();
    //     $groups = $cache->load("class.user.allgroups", null);
    //     if ($groups === null) {
    //         $groups = Group::all();
    //         $cache->save("class.user.allgroups", $groups);
    //     }
    //     return $groups;
    // }

    public function packInfo() {
       return array(
            "id" => $this->id(),
            "filename" => $this->filename(), 
            "url" => $this->url(), 
            "title" => $this->title(), 
            "comments" => $this->comments(), 
        );
    }

    public static function create($uid) {
        $files = db_files::inst()->get($uid);
        return new Files($files);
    }

    public static function all($include_deleted = false) {
        $files = db_files::inst()->all();
        $arr = array();
        foreach ($files as $fid => $file) {
            if (!$include_deleted) {
                if ($file["status"] == db_user::STATUS_DELETED) {
                    continue;
                }
            }
            $arr[$fid] = new Files($file);
        }
        return $arr;
    }

    // public static function &cachedAll() {
    //     $cache = cache::instance();
    //     $all = $cache->load("class.user.all", null);
    //     if ($all === null) {
    //         $all = User::all();
    //         $cache->save("class.user.all", $all);
    //     }
    //     return $all;
    // }

    // public static function oneByName($username) {
    //     $users = self::cachedAll();
    //     foreach ($users as $user) {
    //         if ($user->username() == $username) {
    //             return $user;
    //         }
    //     }
    //     return null;
    // }

    public static function remove($uid) {
        return db_user::inst()->remove($uid);
    }
};

