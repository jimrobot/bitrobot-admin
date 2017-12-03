<?php
include_once(dirname(__FILE__) . "/../config.php");

class Salesman {
    private $mSummary = null;

    public function Salesman($summary = array()) {
        if (empty($summary)) {
            $summary = array(
                "id" => 0,
                "name" => "",
                "telephone" => "",
                "status" => 0,
                "gender" => "",
                "leave_date" => "",
                "category" => 0,
                "comments" => "",
            );
        }
        $this->mSummary = $summary;
    }

    public function id() {
        return $this->mSummary["id"];
    }

    public function name() {
        return $this->mSummary["name"];
    }

    public function telephone() {
        return $this->mSummary["telephone"];
    }

    public function gender() {
        return $this->mSummary["gender"];
    }

    public function comments() {
        return $this->mSummary["comments"];
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


    public function setName($n) {
        $this->mSummary["name"] = $n;
    }

    public function setTelephone($v) {
        $this->mSummary["telephone"] = $v;
    }

    public function setGender($v) {
        $this->mSummary["gender"] = $v;
    }

    public function setCategory($v) {
        $this->mSummary["category"] = $v;
    }

    public function setComments($v) {
        $this->mSummary["comments"] = $v;
    }

    public function save() {
        $id = $this->id();
        if ($id == 0) {
            $id = db_salesman::inst()->add($this->name(), $this->telephone(), $this->gender(), $this->category(), $this->comments());
            if ($id !== false) {
                $this->mSummary["id"] = $id;
            }
        } else {
            $id = db_salesman::inst()->modify($id, $this->name(), $this->telephone(), $this->gender(), $this->category(), $this->comments());
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
            "telephone" => $this->telephone(),
            "gender" => $this->gender(),
            "comments" => $this->comments(),
            "category" => $cinfo,
        );
    }

    public static function create($id) {
        $summary = db_salesman::inst()->get($id);
        return new Salesman($summary);
    }

    public static function all($ignore_deleted = true) {
        $items = db_salesman::inst()->all();
        $arr = array();
        foreach ($items as $id => $summary) {
            if ($ignore_deleted) {
                if ($summary["status"] == db_salesman::STATUS_DELETED) {
                    continue;
                }
            }
            $arr[$id] = new Salesman($summary);
        }
        return $arr;
    }

    public static function remove($id) {
        return db_salesman::inst()->remove($id);
    }
};

