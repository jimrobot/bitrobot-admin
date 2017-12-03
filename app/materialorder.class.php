<?php
include_once(dirname(__FILE__) . "/../config.php");

class Materialorder {
    private $mSummary = null;
    private $mDepot1 = null;
    private $mDepot2 = null;
    private $mCustomer = null;
    private $mUser = null;
    private $mRecords = null;

    public function Materialorder($summary = array()) {
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

    public function serial() {
        return $this->mSummary["serial"];
    }

    public function depot1() {
        return $this->mSummary["depot1"];
    }

    public function depot2() {
        return $this->mSummary["depot2"];
    }

    public function depot1Obj() {
        if ($this->mDepot1 === null) {
            $did = $this->depot1();
            if ($did == 0) {
                return null;
            }
            $depots = Depot::cachedAll();
            if (!isset($depots[$did])) {
                return null;
            }
            $this->mDepot1 = $depots[$did];
        }
        return $this->mDepot1;
    }

    public function depot2Obj() {
        if ($this->mDepot1 === null) {
            $did = $this->depot2();
            if ($did == 0) {
                return null;
            }
            $depots = Depot::cachedAll();
            if (!isset($depots[$did])) {
                return null;
            }
            $this->mDepot1 = $depots[$did];
        }
        return $this->mDepot1;
    }

    public function customer() {
        return $this->mSummary["customer"];
    }

    public function customerObj() {
        if ($this->mCustomer == null) {
            $cid = $this->customer();
            if ($cid == 0) {
                return null;
            }
            if ($this->typeTarget() == "customer") {
                $customers = Customer::cachedAll();
            } else if ($this->typeTarget() == "vendor") {
                $customers = Vendor::cachedAll();
            } else {
                return null;
            }
            if (!isset($customers[$cid])) {
                return null;
            }
            $this->mCustomer = $customers[$cid];
        }
        return $this->mCustomer;
    }

    public function operator() {
        return $this->mSummary["operator"];
    }

    public function operatorName() {
        return $this->mSummary["operatorname"];
    }

    public function operatorObj() {
        if ($this->mUser == null) {
            $uid = $this->operator();
            if ($uid == 0) {
                return null;
            }
            $users = User::cachedAll();
            if (!isset($users[$uid])) {
                return null;
            }
            $this->mUser = $users[$uid];
        }
        return $this->mUser;
    }

    public function comments() {
        return $this->mSummary["comments"];
    }

    public function ordertime() {
        $t = $this->mSummary["ordertime"];
        $t = (int)$t;
        return $t;
    }

    public function ensuretime() {
        return $this->mSummary["ensuretime"];
    }

    public function type() {
        return $this->mSummary["type"];
    }

    public function typeTitle() {
        return self::getTypeTitle($this->type());
    }

    public function typeTarget() {
        return self::getTypeTarget($this->type());
    }

    public function status() {
        return $this->mSummary["status"];
    }

    public function isTempOrder() {
        return $this->mSummary["status"] == db_materialorder::STATUS_NORMAL;
    }

    public function totalprice() {
        return $this->mSummary["totalprice"];
    }

    public function records($fromCache = true) {
        if ($this->mRecords === null) {
            if ($fromCache) {
                $records = Materialorderrecord::cachedAll();
            } else {
                $records = Materialorderrecord::all($this->id());
            }
            foreach ($records as $record) {
                if ($record->orderid() == $this->id()) {
                    $this->mRecords[] = $record;
                }
            }
        }
        return $this->mRecords;
    }

    public function calcTotalPrice() {
        $records = $this->records();
        $totalprice = 0;
        foreach ($records as $record) {
            $totalprice += $record->totalprice();
        }
        return $totalprice;
    }

    public function ensure() {
        $records = $this->records();
        $ret = true;
        foreach ($records as $record) {
            $ret &= $record->ensure($this->customer());
        }
        $totalprice = $this->calcTotalPrice();
        $ret &= db_materialorder::inst()->ensure($this->id(), $totalprice);
        return $ret;
    }

    public function packInfo($packRecords = false) {
        $depot1 = $this->depot1Obj();
        $depot2 = $this->depot2Obj();
        $customer = $this->customerObj();
        $user = $this->operatorObj();

        $totalprice = ($this->status() == db_materialorder::STATUS_NORMAL) ? $this->calcTotalPrice() : $this->totalprice();

        $recordinfo = array();
        $count = 0;
        if ($packRecords) {
            $records = $this->records();
            if (!empty($records)) {
                foreach ($records as $record) {
                    $recordinfo []= $record->packInfo();
                    $count += $record->count();
                }
            }
        }

        return array(
            "id" => $this->id(),
            "serial" => $this->serial(), 
            "depot1" => ($depot1 != null) ? $depot1->packInfo() : null,
            "depot2" => ($depot2 != null) ? $depot2->packInfo() : null,
            "customer" => ($customer != null) ? $customer->packInfo() : null,
            "operator" => ($user != null) ? $user->packInfo() : null,
            "operatorName" => $this->operatorName(),
            "comments" => $this->comments(),
            "ordertime" => Date("Y-m-d H:i:s", $this->ordertime()),
            "ensuretime" => $this->ensuretime(),
            "type" => $this->type(),
            "typeTitle" => $this->typeTitle(),
            "typeTarget" => $this->typeTarget(),
            "status" => $this->status(),
            "temp" => $this->isTempOrder(),
            "totalprice" => sprintf("%0.2f", $totalprice),
            "records" => $recordinfo,
            "totalcount" => $count,
        );
    }

    public static function create($id) {
        $summary = db_materialorder::inst()->get($id);
        return new Materialorder($summary);
    }

    public static function createBySerial($serial) {
        $summary = db_materialorder::inst()->find($serial);
        return new Materialorder($summary);
    }

    public static function all() {
        $items = db_materialorder::inst()->all();
        $arr = array();
        foreach ($items as $id => $summary) {
            $arr[$id] = new Materialorder($summary);
        }
        return $arr;
    }

    public static function remove($serial) {
        $po = self::createBySerial($serial);
        if ($po->id() == 0) {
            return false;
        }
        $ret = db_materialorder::inst()->remove($po->id());
        $ret &= db_materialorderrecord::inst()->removeByOrderId($po->id());
        return $ret;
    }

    public static function types() {
        $arr = array(
            array("type" => db_materialorder::TYPE_PURCHASE, "title" => "进货单", "target" => "vendor"),
            array("type" => db_materialorder::TYPE_PURCHASEREFUND, "title" => "进货退货单", "target" => "vendor"),
            array("type" => db_materialorder::TYPE_SALES, "title" => "销售单", "target" => "customer"),
            array("type" => db_materialorder::TYPE_SALESREFUND, "title" => "销售退货单", "target" => "customer"),
            array("type" => db_materialorder::TYPE_PRODUCTION, "title" => "领料生产单", "target" => "production"),
        );  
        return $arr;
    }

    public static function getTypeTitle($type) {
        $arr = self::types();
        foreach ($arr as $a) {
            if ($a["type"] == $type) {
                return $a["title"];
            }
        }
        return "";
    }

    public static function getTypeTarget($type) {
        $arr = self::types();
        foreach ($arr as $a) {
            if ($a["type"] == $type) {
                return $a["target"];
            }
        }
        return "";
    }

    private static function orderhash($type, $cid, $depotid, $order, $comments) {
        static $guid = ''; 
        $uid = uniqid("", true);
        $data = json_encode($order) . json_encode($comments);
        $data .= $_SERVER['REQUEST_TIME'];
        $data .= $_SERVER['HTTP_USER_AGENT'];
        $data .= $_SERVER['REMOTE_ADDR'];
        $data .= $_SERVER['REMOTE_PORT'];
        $hash = strtoupper(hash('ripemd128', $uid . $guid . md5($data)));
        $guid = '' .
            substr($hash,  0,  8) .
            '' .
            substr($hash,  8,  4) .
            '' .
            substr($hash, 12,  4) .
            '' .
            substr($hash, 16,  4) .
            '' .
            substr($hash, 20, 12) .
            ''; 
        $date = date("YmdHis");

        $type = (($type + 100) % 100);
        $serial = sprintf("OD%dC%dD%dT%s%s", $type, $cid, $depotid, $date, substr($guid, 0, 5)); 
        $checksum = substr(md5($serial), 3, 3);
        $serial = $serial . strtoupper($checksum);
        return $serial;
    }

    public static function addOrder($type, $cid, $depotid, $order, $comments, $replaceSerial = null) {
        $serial = $replaceSerial;
        if (empty($serial)) {
            do {
                $serial = self::orderhash($type, $cid, $depotid, $order, $comments);
            } while(db_materialorder::inst()->has($serial));
        }

        $operator = get_session("user.id", 0);
        $operatorName = get_session("user.nickname", "");

        logging::d("Debug", "serial = $serial");

        $id = db_materialorder::inst()->add($serial, $depotid, 0, $cid, $operator, $operatorName, $comments, $type);
        foreach ($order as $record) {
            $pid = $record["pid"];
            $count = $record["count"];
            $totalprice = $record["largess"];
            $material = Material::create($pid);
            if ($material == null) {
                continue;
            }
            // $price = Price::instance()->getFinalPrice($pid, $cid);
            db_materialorderrecord::inst()->add($id, $pid, $count, $totalprice);
        }
        return $serial;
    }

};


