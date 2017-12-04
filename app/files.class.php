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
        // $id = $this->id();
        // if ($id == 0) {
        //     $id = db_user::inst()->add($this->username(), $this->password(), $this->nickname(), $this->telephone(), $this->email(), $this->mSummary["groups"], $this->comments());
        //     if ($id !== false) {
        //         $this->mSummary["id"] = $id;
        //     }
        // } else {
        //     $id = db_user::inst()->modify($id, $this->username(), $this->password(), $this->nickname(), $this->telephone(), $this->email(), $this->mSummary["groups"], $this->comments());
        // }
        // return $id;
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
        $user = db_user::inst()->get($uid);
        return new User($user);
    }

    public static function all($include_deleted = false) {
        $users = db_user::inst()->all();
        $arr = array();
        foreach ($users as $uid => $user) {
            if (!$include_deleted) {
                if ($user["status"] == db_user::STATUS_DELETED) {
                    continue;
                }
            }
            $arr[$uid] = new User($user);
        }
        return $arr;
    }

    public static function &cachedAll() {
        $cache = cache::instance();
        $all = $cache->load("class.user.all", null);
        if ($all === null) {
            $all = User::all();
            $cache->save("class.user.all", $all);
        }
        return $all;
    }

    public static function oneByName($username) {
        $users = self::cachedAll();
        foreach ($users as $user) {
            if ($user->username() == $username) {
                return $user;
            }
        }
        return null;
    }

    public static function remove($uid) {
        return db_user::inst()->remove($uid);
    }
};

