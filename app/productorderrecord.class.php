<?php
include_once(dirname(__FILE__) . "/../config.php");

class Productorderrecord {
    private $mSummary = null;
    private $mProduct = null;
    private $mSavedProduct = null;

    public function Productorderrecord($summary = array()) {
        $this->mSummary = $summary;
    }

    public function id() {
        return $this->mSummary["id"];
    }

    public function orderid() {
        return $this->mSummary["orderid"];
    }

    public function productid() {
        return $this->mSummary["productid"];
    }

    public function count() {
        return $this->mSummary["count"];
    }

    public function largess() {
        return $this->mSummary["largess"];
    }

    public function finalprice() {
        return $this->mSummary["finalprice"];
    }

    public function currentPrice($cid) {
        $finalprice = $this->finalprice();
        if (empty($finalprice)) {
            $finalprice = Price::instance()->getFinalPrice($this->productid(), $cid);
        }
        return $finalprice;
    }

    public function product($cached = true) {
        if ($this->mProduct == null) {
            $pid = $this->productid();
            $products = Product::cachedAll();
            if (!isset($products[$pid])) {
                return null;
            }
            $this->mProduct = $products[$pid];
        }
        return $this->mProduct;
    }

    public function savedProduct() {
        if ($this->mSavedProduct == null) {
            $p = new Product();
            $p->setName($this->mSummary["name"]);
            $p->setTitle($this->mSummary["title"]);
            $p->setUnit($this->mSummary["unit"]);
            $p->setStandard($this->mSummary["standard"]);
            $p->setComments($this->mSummary["comments"]);
            $this->mSavedProduct = $p;
        }
        return $this->mSavedProduct;
    }

    public function setName($n) {
        $this->mSummary["name"] = $n;
    }

    public function ensure($cid) {
        $price = Price::instance()->getFinalPrice($this->productid(), $cid);
        $this->mSummary["finalprice"] = $price;
        return db_productorderrecord::inst()->update_price($this->id(), $price);
    }

    // public function save() {
    //     // $id = $this->id();
    //     // if ($id == 0) {
    //     //     $id = db_productorderrecord::inst()->add();
    //     //     if ($id !== false) {
    //     //         $this->mSummary["id"] = $id;
    //     //     }
    //     // } else {
    //     //     $id = db_productorderrecord::inst()->modify($id);
    //     // }
    //     // return $id;
    // }

    public function packInfo($temp, $cid) {
        if ($temp) {
            $product = $this->product();
        } else {
            $product = $this->savedProduct();
        }
        $currentPrice = $this->currentPrice($cid);
        return array(
            "id" => $this->id(),
            "productid" => $this->productid(),
            "count" => $this->count(),
            "largess" => $this->largess(),
            "product" => ($product != null) ? $product->packInfo() : null,
            "price" => sprintf("%0.2f", $currentPrice),
            "totalprice" => sprintf("%0.2f", $currentPrice * $this->count()),
        );
    }

    public static function create($id) {
        $summary = db_productorderrecord::inst()->get($id);
        return new Productorderrecord($summary);
    }


    public static function all($orderid = 0) {
        $items = db_productorderrecord::inst()->all();
        $arr = array();
        foreach ($items as $id => $summary) {
            $arr[$id] = new Productorderrecord($summary);
        }
        return $arr;
    }

    public static function &cachedAll() {
        $cache = cache::instance();
        $all = $cache->load("class.productorderrecord.all", null);
        if ($all === null) {
            $all = Productorderrecord::all();
            $cache->save("class.productorderrecord.all", $all);
        }
        return $all;
    }

    public static function remove($id) {
        return db_productorderrecord::inst()->remove($id);
    }
};

