<?php
include_once(dirname(__FILE__) . "/../config.php");

class Depot {
    private $mSummary = null;

    public function Depot($summary = array()) {
        if (empty($summary)) {
            $summary = array(
                "id" => 0,
                "name" => "",
                "address" => "",
                "manager" => "",
                "cagetory" => 0,
                "comments" => "",
                "status" => 0
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

    public function address() {
        return $this->mSummary["address"];
    }

    public function manager() {
        return $this->mSummary["manager"];
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

    public function setAddress($a) {
        $this->mSummary["address"] = $a;
    }

    public function setManager($m) {
        $this->mSummary["manager"] = $m;
    }

    public function setComments($v) {
        $this->mSummary["comments"] = $v;
    }

    public function setCategory($v) {
        $this->mSummary["category"] = $v;
    }

    public function save() {
        $id = $this->id();
        if ($id == 0) {
            $id = db_depot::inst()->add($this->name(), $this->address(), $this->manager(), $this->category(), $this->comments());
            if ($id !== false) {
                $this->mSummary["id"] = $id;
            }
        } else {
            $id = db_depot::inst()->modify($id, $this->name(), $this->address(), $this->manager(), $this->category(), $this->comments());
        }
        return $id;
    }

    public function packInfo() {
        $category = $this->categoryClass();
        $cinfo = null;
        if ($category != null) {
            $cinfo = $category->packInfo();
        }

        $manager = User::create($this->manager());
        return array(
            "id" => $this->id(),
            "name" => $this->name(), 
            "address" => $this->address(), 
            "manager" => $manager->packInfo(false),
            "category" => $cinfo,
            "comments" => $this->comments(),
        );
    }

    public static function create($id) {
        $summary = db_depot::inst()->get($id);
        return new Depot($summary);
    }

    public static function all($include_deleted = false) {
        $items = db_depot::inst()->all();
        $arr = array();
        foreach ($items as $id => $summary) {
            if (!$include_deleted) {
                if ($summary["status"] == db_depot::STATUS_DELETED) {
                    continue;
                }
            }
            $arr[$id] = new Depot($summary);
        }
        return $arr;
    }

    public static function &cachedAll() {
        $cache = cache::instance();
        $all = $cache->load("class.depot.all", null);
        if ($all === null) {
            $all = Depot::all();
            $cache->save("class.depot.all", $all);
        }
        return $all;
    }

    public static function remove($id) {
        return db_depot::inst()->remove($id);
    }
};

