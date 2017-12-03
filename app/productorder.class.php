<?php
include_once(dirname(__FILE__) . "/../config.php");

class Productorder {
    private $mSummary = null;
    private $mDepot1 = null;
    private $mDepot2 = null;
    private $mCustomer = null;
    private $mUser = null;
    private $mRecords = null;

    public function Productorder($summary = array()) {
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
            } else {
                $customers = Vendor::cachedAll();
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
        return $this->mSummary["status"] == db_productorder::STATUS_NORMAL;
    }

    public function totalprice() {
        return $this->mSummary["totalprice"];
    }

    public function records($fromCache = true) {
        if ($this->mRecords === null) {
            if ($fromCache) {
                $records = Productorderrecord::cachedAll();
            } else {
                $records = Productorderrecord::all($this->id());
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
        $cid = $this->customer();
        foreach ($records as $record) {
            $pid = $record->productid();
            $price = Price::instance()->getFinalPrice($pid, $cid);
            $price *= $record->count();
            $totalprice += $price;
        }
        return $totalprice;
    }

    public function calcTotalCount() {
        $records = $this->records();
        $count = 0;
        $largess = 0;
        foreach ($records as $record) {
            $count += $record->count();
            $largess += $record->largess();
        }
        return array("count" => $count, "largess" => $largess);
    }

    public function ensure() {
        $records = $this->records();
        $ret = true;
        foreach ($records as $record) {
            $ret &= $record->ensure($this->customer());
        }
        $totalprice = $this->calcTotalPrice();
        $ret &= db_productorder::inst()->ensure($this->id(), $totalprice);

        $this->mSummary["status"] = db_productorder::STATUS_ENSURED;
        $this->mSummary["totalprice"] = $totalprice;

        $this->notifyAdmin();
        $this->notifyCustomers();

        return $ret;
    }

    public function notifyAdmin() {
        $title = $this->isTempOrder() ? "已下单" : "已发货";
        $title .= "， 客户：" . $this->customerObj()->title();
        $serial = $this->serial();
        $total = $this->calcTotalCount();
        $message = "共" . $total["count"] . "件，赠送" . $total["largess"] . "件。";
        $time = Date("Y-m-d H:i:s", $this->ordertime());
        $totalprice = $this->isTempOrder() ? $this->calcTotalPrice() : $this->totalprice();
        $remark = "共{$totalprice}元。";
        $color = $this->isTempOrder() ? "#0000ff" : "#ff0000";

        $admin_array = array(
            "touser" => "",
            "template_id" => setting::instance()->load("KEY_WECHAT_CUSTOMERNOTIFY"),
            "url" => HOME_URL . "?action=order.print&s=" . $serial,
            "topcolor" => $color,
            "data" => array(
                "first" => array("value" => $title, "color" => $color),
                "keyword1" => array("value" => $serial, "color" => $color),
                "keyword2" => array("value" => $message, "color" => $color),
                "keyword3" => array("value" => $time, "color" => $color),
                "remark" => array("value" => $remark, "color" => $color),
            ),
        );

        $admins = Wechatadmin::allOpenids();
        $wx = WeChat::inst();
        foreach ($admins as $openid) {
            $admin_array["touser"] = $openid;
            $ret = $wx->send_template_message($admin_array);
            logging::d("Debug", "send template message to admin: $openid result: ");
            logging::d("Debug", $ret);
        }
    }

    public function notifyCustomers() {
        if ($this->isTempOrder()) {
            return;
        }
        $title = "您的订单已发货";
        $serial = $this->serial();
        $total = $this->calcTotalCount();
        $message = "共" . $total["count"] . "件，赠送" . $total["largess"] . "件。";
        $time = Date("Y-m-d H:i:s", $this->ordertime());
        $totalprice = $this->totalprice();
        $remark = "共{$totalprice}元。";
        $color = "#000000";

        $message_array = array(
            "touser" => "",
            "template_id" => setting::instance()->load("KEY_WECHAT_CUSTOMERNOTIFY"),
            "url" => HOME_URL . "?action=order.print&s=" . $serial,
            "topcolor" => $color,
            "data" => array(
                "first" => array("value" => $title, "color" => $color),
                "keyword1" => array("value" => $serial, "color" => $color),
                "keyword2" => array("value" => $message, "color" => $color),
                "keyword3" => array("value" => $time, "color" => $color),
                "remark" => array("value" => $remark, "color" => $color),
            ),
        );

        $customers = Notice::instance()->getOpenids($this->customer());
        logging::d("Debug", "customers: ");
        logging::d("Debug", $customers);
        $wx = WeChat::inst();
        foreach ($customers as $openid) {
            $message_array["touser"] = $openid;
            $ret = $wx->send_template_message($message_array);
            logging::d("Debug", "send template message to customer: $openid result: ");
            logging::d("Debug", $ret);
        }

    }

    public function packInfo($packRecords = false) {
        $depot1 = $this->depot1Obj();
        $depot2 = $this->depot2Obj();
        $customer = $this->customerObj();
        $user = $this->operatorObj();

        $totalprice = ($this->status() == db_productorder::STATUS_NORMAL) ? $this->calcTotalPrice() : $this->totalprice();

        $recordinfo = array();
        $count = 0;
        $largess = 0;
        if ($packRecords) {
            $records = $this->records();
            foreach ($records as $record) {
                $recordinfo []= $record->packInfo($this->isTempOrder(), $this->customer());
                $count += $record->count();
                $largess += $record->largess();
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
            "totallargess" => $largess,
        );
    }

    public static function create($id) {
        $summary = db_productorder::inst()->get($id);
        logging::d("Debug", $summary);
        return new Productorder($summary);
    }

    public static function createBySerial($serial) {
        $summary = db_productorder::inst()->find($serial);
        return new Productorder($summary);
    }

    public static function all() {
        $items = db_productorder::inst()->all();
        $arr = array();
        foreach ($items as $id => $summary) {
            $arr[$id] = new Productorder($summary);
        }
        return $arr;
    }

    public static function remove($serial) {
        $po = self::createBySerial($serial);
        if ($po->id() == 0) {
            return false;
        }
        $ret = db_productorder::inst()->remove($po->id());
        $ret &= db_productorderrecord::inst()->removeByOrderId($po->id());
        return $ret;
    }

    public static function types() {
        $arr = array(
            array("type" => db_productorder::TYPE_INVOICE, "title" => "发货单", "target" => "customer"),
            array("type" => db_productorder::TYPE_SALES, "title" => "销售单", "target" => "customer"),
            array("type" => db_productorder::TYPE_SALESREFUND, "title" => "销售退货单", "target" => "customer"),
            array("type" => db_productorder::TYPE_PURCHASE, "title" => "进货单", "target" => "vendor"),
            array("type" => db_productorder::TYPE_PURCHASEREFUND, "title" => "进货退货单", "target" => "vendor"),
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
            } while(db_productorder::inst()->has($serial));
        }

        $operator = get_session("user.id", 0);
        $operatorName = get_session("user.nickname", "");

        logging::d("Debug", "serial = $serial");

        $id = db_productorder::inst()->add($serial, $depotid, 0, $cid, $operator, $operatorName, $comments, $type);
        foreach ($order as $record) {
            $pid = $record["pid"];
            $count = $record["count"];
            $largess = $record["largess"];
            $product = Product::create($pid);
            if ($product == null) {
                continue;
            }
            // $price = Price::instance()->getFinalPrice($pid, $cid);
            db_productorderrecord::inst()->add($id, $product->serial(), $pid, $product->name(), $product->title(), $product->unit(), $product->standard(), $product->mode(), $product->comments(), $count, $largess);
        }

        $order = self::create($id);
        $order->notifyAdmin();

        return $serial;
    }

};


