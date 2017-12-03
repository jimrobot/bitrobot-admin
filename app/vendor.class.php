<?php
include_once(dirname(__FILE__) . "/../config.php");

class Vendor {
    private $mSummary = null;

    public function Vendor($summary = array()) {
        if (empty($summary)) {
            $summary = array(
                "id" => 0,
                "category" => 0,
                "name" => "",
                "title" => "",
                "telephone" => "",
                "email" => "",
                "province" => "",
                "city" => "",
                "district" => "",
                "address" => "",
                "salesman" => "",
                "comments" => "",
                "wechat_userid" => 0,
                "wechat_notify" => 0,
            );
        }
        $this->mSummary = $summary;
    }

    public function id() {
        return $this->mSummary["id"];
    }

    public function category() {
        return $this->mSummary["category"];
    }

    public function categoryClass() {
        if ($this->category() == 0) {
            return new Category();
        }
        $all = Category::cachedAll();
        if (isset($all[$this->category()])) {
            return $all[$this->category()];
        }
        return new Category();
    }

    public function name() {
        return $this->mSummary["name"];
    }

    public function title() {
        return $this->mSummary["title"];
    }

    public function telephone() {
        return $this->mSummary["telephone"];
    }

    public function email() {
        return $this->mSummary["email"];
    }

    public function address() {
        return $this->mSummary["address"];
    }

    public function comments() {
        return $this->mSummary["comments"];
    }

    public function province() {
        return $this->mSummary["province"];
    }

    public function city() {
        return $this->mSummary["city"];
    }

    public function district() {
        return $this->mSummary["district"];
    }

    public function setName($n) {
        $this->mSummary["name"] = $n;
    }

    public function setCategory($v) {
        $this->mSummary["category"] = $v;
    }

    public function setTitle($v) {
        $this->mSummary["title"] = $v;
    }

    public function setTelephone($v) {
        $this->mSummary["telephone"] = $v;
    }

    public function setEmail($v) {
        $this->mSummary["email"] = $v;
    }

    public function setAddress($v) {
        $this->mSummary["address"] = $v;
    }

    public function setComments($v) {
        $this->mSummary["comments"] = $v;
    }

    public function setProvince($v) {
        $this->mSummary["province"] = $v;
    }

    public function setCity($v) {
        $this->mSummary["city"] = $v;
    }

    public function setDistrict($v) {
        $this->mSummary["district"] = $v;
    }


    public function save() {
        $id = $this->id();
        if ($id == 0) {
            $id = db_vendor::inst()->add($this->name(), $this->title(), $this->category(), $this->telephone(), $this->email(), $this->address(), $this->comments(), $this->province(), $this->city(), $this->district());
            if ($id !== false) {
                $this->mSummary["id"] = $id;
            }
        } else {
            $id = db_vendor::inst()->modify($id, $this->name(), $this->title(), $this->category(), $this->telephone(), $this->email(), $this->address(), $this->comments(), $this->province(), $this->city(), $this->district());
        }
        return $id;
    }

    public function packInfo() {
        $category = $this->categoryClass();
        $cinfo = null;
        if ($category != null) {
            $cinfo = $category->packInfo();
        }
        return array(
            "id" => $this->id(),
            "name" => $this->name(), 
            "category" => $cinfo,
            "name" => $this->name(),
            "title" => $this->title(),
            "telephone" => $this->telephone(),
            "email" => $this->email(),
            "address" => $this->address(),
            "comments" => $this->comments(),
            "province" => $this->province(),
            "city" => $this->city(),
            "district" => $this->district(),
        );
    }

    public static function create($id) {
        $summary = db_vendor::inst()->get($id);
        return new Vendor($summary);
    }

    public static function all($ignore_deleted = true) {
        $items = db_vendor::inst()->all();
        $arr = array();
        foreach ($items as $id => $summary) {
            if ($ignore_deleted) {
                if ($summary["status"] == db_vendor::STATUS_DELETED) {
                    continue;
                }
            }
            $arr[$id] = new Vendor($summary);
        }
        return $arr;
    }

    public static function &cachedAll() {
        $cache = cache::instance();
        $all = $cache->load("class.vendor.all", null);
        if ($all === null) {
            $all = Vendor::all();
            $cache->save("class.vendor.all", $all);
        }
        return $all;
    }

    public static function remove($id) {
        return db_vendor::inst()->remove($id);
    }
};

