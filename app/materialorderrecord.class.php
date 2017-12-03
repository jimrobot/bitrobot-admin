<?php
include_once(dirname(__FILE__) . "/../config.php");

class Materialorderrecord {
    private $mSummary = null;
    private $mMaterial = null;
    private $mSavedMaterial = null;

    public function Materialorderrecord($summary = array()) {
        $this->mSummary = $summary;
    }

    public function id() {
        return $this->mSummary["id"];
    }

    public function orderid() {
        return $this->mSummary["orderid"];
    }

    public function materialid() {
        return $this->mSummary["materialid"];
    }

    public function count() {
        return $this->mSummary["count"];
    }

    public function totalprice() {
        return $this->mSummary["totalprice"];
    }

    public function product($cached = true) {
        if ($this->mMaterial == null) {
            $pid = $this->materialid();
            $materials = Material::cachedAll();
            if (!isset($materials[$pid])) {
                return null;
            }
            $this->mMaterial = $materials[$pid];
        }
        return $this->mMaterial;
    }

    public function savedProduct() {
        if ($this->mSavedMaterial == null) {
            $p = new Material();
            $p->setName($this->mSummary["name"]);
            $p->setTitle($this->mSummary["title"]);
            $p->setUnit($this->mSummary["unit"]);
            $p->setStandard($this->mSummary["standard"]);
            $p->setComments($this->mSummary["comments"]);
            $this->mSavedMaterial = $p;
        }
        return $this->mSavedMaterial;
    }

    public function setName($n) {
        $this->mSummary["name"] = $n;
    }

    public function ensure($cid) {
        // $price = Price::instance()->getFinalPrice($this->materialid(), $cid);
        // $this->mSummary["finalprice"] = $price;
        // return db_materialorderrecord::inst()->update_price($this->id(), $price);
    }

    // public function save() {
    //     // $id = $this->id();
    //     // if ($id == 0) {
    //     //     $id = db_materialorderrecord::inst()->add();
    //     //     if ($id !== false) {
    //     //         $this->mSummary["id"] = $id;
    //     //     }
    //     // } else {
    //     //     $id = db_materialorderrecord::inst()->modify($id);
    //     // }
    //     // return $id;
    // }

    public function packInfo() {
        $product = $this->product();
        return array(
            "id" => $this->id(),
            "materialid" => $this->materialid(),
            "count" => $this->count(),
            "product" => ($product != null) ? $product->packInfo() : null,
            "totalprice" => $this->totalprice(),
        );
    }

    public static function create($id) {
        $summary = db_materialorderrecord::inst()->get($id);
        return new Materialorderrecord($summary);
    }


    public static function all($orderid = 0) {
        $items = db_materialorderrecord::inst()->all();
        $arr = array();
        foreach ($items as $id => $summary) {
            $arr[$id] = new Materialorderrecord($summary);
        }
        return $arr;
    }

    public static function &cachedAll() {
        $cache = cache::instance();
        $all = $cache->load("class.materialorderrecord.all", null);
        if ($all === null) {
            $all = Materialorderrecord::all();
            $cache->save("class.materialorderrecord.all", $all);
        }
        return $all;
    }

    public static function remove($id) {
        return db_materialorderrecord::inst()->remove($id);
    }
};

