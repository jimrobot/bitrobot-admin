<?php
include_once(dirname(__FILE__) . "/../config.php");

class Price {

    public static function &instance() {
        $cache = cache::instance();
        $price = $cache->load("class.price.instance", null);
        if ($price === null) {
            $price = new Price();
            $cache->save("class.price.instance", $price);
        }
        return $price;
    }

    private $mPrices = null;
    private function Price() {
        $items = db_price::inst()->all();
        $this->mPrices = array();
        foreach ($items as $id => $summary) {
            $this->mPrices[$id] = $summary;
        }
    }

    public function getCustomerPrice($pid, $cid) {
        foreach ($this->mPrices as $price) {
            if ($price["product"] == $pid && $price["customer"] == $cid) {
                return $price["price"];
            }
        }
        return null;
    }

    public function getDefaultPrice($pid) {
        $products = Product::cachedAll();
        if (isset($products[$pid])) {
            return $products[$pid]->price();
        }
        return null;
    }

    public function getFinalPrice($pid, $cid) {
        $price = $this->getCustomerPrice($pid, $cid);
        if ($price !== null) {
            return $price;
        }
        $price = $this->getDefaultPrice($pid);
        return $price;
    }

    public function packInfo() {
        $prices = $this->mPrices;
        $info = array();
        if (!empty($prices)) {
            // logging::d("Debug", $prices, false);
            foreach ($prices as $price) {
                $info []= array("cid" => $price["customer"], "pid" => $price["product"], "price" => $price["price"]);
            }
        }
        return $info;
    }

    public function update($pid, $cid, $price) {
        foreach ($this->mPrices as $k => $pr) {
            if ($pr["product"] == $pid && $pr["customer"] == $cid) {
                $this->mPrices[$k]["price"] = $price;
                return db_price::inst()->modify($pid, $cid, $price);
            }
        }
        $this->mPrices[] = array("product" => $pid, "customer" => $cid, "price" => $price);
        return db_price::inst()->add($pid, $cid, $price);
    }

    public function remove($pid, $cid) {
        $ret = db_price::inst()->remove($pid, $cid);
        if ($ret !== false) {
            foreach ($this->mPrices as $k => $pr) {
                if ($pr["product"] == $pid && $pr["customer"] == $cid) {
                    unset ($this->mPrices[$k]);
                }
            }
        }
        return $ret;
    }

};

