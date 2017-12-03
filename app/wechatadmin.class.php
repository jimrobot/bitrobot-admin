<?php
include_once(dirname(__FILE__) . "/../config.php");

class Wechatadmin {
    private $mSummary = null;

    public function Wechatadmin($summary = array()) {
        if (empty($summary)) {
            $summary = array(
                "id" => 0,
                "wechatuser" => 0,
                "notify" => 0,
            );
        }
        $this->mSummary = $summary;
    }

    public function id() {
        return $this->mSummary["id"];
    }

    public function wechatuser() {
        return $this->mSummary["wechatuser"];
    }

    public function notify() {
        return $this->mSummary["notify"];
    }

    public function setWechatuser($n) {
        $this->mSummary["wechatuser"] = $n;
    }

    public function setNotify($n) {
        $this->mSummary["notify"] = $n;
    }

    public function save() {
        $id = $this->id();
        if ($id == 0) {
            $id = db_wechatadmin::inst()->add($this->wechatuser(), $this->notify());
            if ($id !== false) {
                $this->mSummary["id"] = $id;
            }
        } else {
            $id = db_wechatadmin::inst()->modify($id, $this->wechatuser(), $this->notify());
        }
        return $id;
    }

    public function packInfo() {
       return array(
            "id" => $this->id(),
            "wechatuser" => $this->wechatuser(), 
            "notify" => $this->notify(), 
        );
    }

    public static function create($id) {
        $summary = db_wechatadmin::inst()->get($id);
        return new Wechatadmin($summary);
    }

    public function createByWeid($weid) {
        $summary = db_wechatadmin::inst()->getByWeid($weid);
        return new Wechatadmin($summary);
    }

    public static function allOpenids() {
        $wechatusers = Wechatuser::cachedAll();
        $openids = array();
        $all = self::cachedAll();
        foreach ($all as $notice) {
            if ($notice->notify() == 0) {
                continue;
            }
            $wid = $notice->wechatuser();
            if (isset($wechatusers[$wid])) {
                $weuser = $wechatusers[$wid];
                $openids [] = $weuser->openid();
            }
        }
        return $openids;
    }

    public static function all() {
        $items = db_wechatadmin::inst()->all();
        $arr = array();
        foreach ($items as $id => $summary) {
            $arr[$id] = new Wechatadmin($summary);
        }
        return $arr;
    }

    public static function &cachedAll() {
        $cache = cache::instance();
        $all = $cache->load("class.wechatadmin.all", null);
        if ($all === null) {
            $all = Wechatadmin::all();
            $cache->save("class.wechatadmin.all", $all);
        }
        return $all;
    }

    public static function remove($id) {
        return db_wechatadmin::inst()->removeByWeid($id);
    }
};

