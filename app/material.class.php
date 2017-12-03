<?php
include_once(dirname(__FILE__) . "/../config.php");

class Material {
    private $mSummary = null;

    public function Material($summary = array()) {
        if (empty($summary)) {
            $summary = array(
                "id" => 0,
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

    public function title() {
        return $this->mSummary["title"];
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

    public function unit() {
        return $this->mSummary["unit"];
    }

    public function standard() {
        return $this->mSummary["standard"];
    }

    public function barcode() {
        return $this->mSummary["barcode"];
    }

    public function qrcode() {
        return $this->mSummary["qrcode"];
    }

    public function serial() {
        return $this->mSummary["serial"];
    }

    public function vendor() {
        return $this->mSummary["vendor"];
    }

    public function mode() {
        return $this->mSummary["mode"];
    }

    public function comments() {
        return $this->mSummary["comments"];
    }

    public function setName($n) {
        $this->mSummary["name"] = $n;
    }

    public function setTitle($v) {
        $this->mSummary["title"] = $v;
    }

    public function setCategory($v) {
        $this->mSummary["category"] = $v;
    }
 
    public function setUnit($v) {
        $this->mSummary["unit"] = $v;
    }

    public function setStandard($v) {
        $this->mSummary["standard"] = $v;
    }

    public function setBarcode($v) {
        $this->mSummary["barcode"] = $v;
    }

    public function setQrcode($v) {
        $this->mSummary["qrcode"] = $v;
    }

    public function setSerial($v) {
        $this->mSummary["serial"] = $v;
    }

    public function setVendor($v) {
        $this->mSummary["vendor"] = $v;
    }

    public function setMode($v) {
        $this->mSummary["mode"] = $v;
    }

    public function setComments($v) {
        $this->mSummary["comments"] = $v;
    }

    public function save() {
        $id = $this->id();
        if ($id == 0) {
            $id = db_material::inst()->add($this->name(), $this->title(), $this->category(), $this->unit(), $this->standard(), $this->barcode(), $this->qrcode(), $this->serial(), $this->vendor(), $this->mode(), $this->comments());
            if ($id !== false) {
                $this->mSummary["id"] = $id;
            }
        } else {
            $id = db_material::inst()->modify($id, $this->name(), $this->title(), $this->category(), $this->unit(), $this->standard(), $this->barcode(), $this->qrcode(), $this->serial(), $this->vendor(), $this->mode(), $this->comments());
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
            "title" => $this->title(),
            "unit" => $this->unit(),
            "standard" => $this->standard(),
            "barcode" => $this->barcode(),
            "qrcode" => $this->qrcode(),
            "serial" => $this->serial(),
            "vendor" => $this->vendor(),
            "mode" => $this->mode(),
            "comments" => $this->comments(),
            "category" => $cinfo,
        );
    }

    public static function create($id) {
        $summary = db_material::inst()->get($id);
        return new Material($summary);
    }

    public static function all($ignore_deleted = true) {
        $items = db_material::inst()->all();
        $arr = array();
        foreach ($items as $id => $summary) {
            if ($ignore_deleted) {
                if ($summary["status"] == db_material::STATUS_DELETED) {
                    continue;
                }
            }
            $arr[$id] = new Material($summary);
        }
        return $arr;
    }

    public static function &cachedAll() {
        $cache = cache::instance();
        $all = $cache->load("class.material.all", null);
        if ($all === null) {
            $all = Product::all();
            $cache->save("class.material.all", $all);
        }
        return $all;
    }

    public static function remove($id) {
        return db_material::inst()->remove($id);
    }
};

