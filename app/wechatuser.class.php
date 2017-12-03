<?php
include_once(dirname(__FILE__) . "/../config.php");

class Wechatuser {
    private $mSummary = null;

    public function Wechatuser($summary = array()) {
        if (empty($summary)) {
            $summary = array(
                "id" => 0,
                "openid" => "",
                "nickname" => "",
                "headimgurl" => "",
                "gender" => 0,
                "city" => "",
                "province" => "",
                "country" => "",
                "subscribe" => "",
                "remark" => "",
                "groupid" => 0,
                "tagid_list" => array(),
                "comments" => "",
            );
        }
        if (!isset($summary["id"])) {
            $summary["id"] = 0;
        }
        if (!isset($summary["comments"])) {
            $summary["comments"] = "";
        }
        $this->mSummary = $summary;
    }

    public function assign($o) {
        $id = $this->id();
        $this->mSummary = $o->summary();
        $this->mSummary["id"] = $id;
    }

    public function summary() {
        return $this->mSummary;
    }

    public function id() {
        return $this->mSummary["id"];
    }

    public function setId($v) {
        $this->mSummary["id"] = $v;
    }

    public function openid() {
        return $this->mSummary["openid"];
    }

    public function nickname() {
        return $this->mSummary["nickname"];
    }

    public function headimgurl() {
        return $this->mSummary["headimgurl"];
    }

    public function gender() {
        return $this->mSummary["sex"];
    }

    public function city() {
        return $this->mSummary["city"];
    }

    public function province() {
        return $this->mSummary["province"];
    }

    public function country() {
        return $this->mSummary["country"];
    }

    public function subscribe() {
        return $this->mSummary["subscribe"];
    }

    public function remark() {
        return $this->mSummary["remark"];
    }

    public function groupid() {
        return $this->mSummary["groupid"];
    }

    public function tagid_list() {
        if (is_array($this->mSummary["tagid_list"])) {
            return $this->mSummary["tagid_list"];
        }
        return json_decode($this->mSummary["tagid_list"], true);
    }

    public function comments() {
        return $this->mSummary["comments"];
    }

    public function setComments($v) {
        $this->mSummary["comments"] = $v;
    }

    public function packInfo() {
       return array(
            "id" => $this->id(),
            "openid" => $this->openid(),
            "nickname" => $this->nickname(), 
            "headimgurl" => $this->headimgurl(),
            "gender" => $this->gender(),
            "city" => $this->city(),
            "province" => $this->province(),
            "country" => $this->country(),
            "subscribe" => $this->subscribe(),
            "remark" => $this->remark(),
            "groupid" => $this->groupid(),
            "tagid_list" => $this->tagid_list(),
            "comments" => $this->comments(),
        );
    }

    public function save() {
        $id = $this->id();
        if ($id == 0) {
            $id = db_wechatuser::inst()->add($this->openid(), $this->nickname(), $this->headimgurl(), $this->gender(), $this->city(), $this->province(), $this->country(), $this->subscribe(), $this->remark(), $this->groupid(), json_encode($this->tagid_list()), $this->comments());
            if ($id !== false) {
                $this->mSummary["id"] = $id;
            }
        } else {
            $id = db_wechatuser::inst()->modify($id, $this->openid(), $this->nickname(), $this->headimgurl(), $this->gender(), $this->city(), $this->province(), $this->country(), $this->subscribe(), $this->remark(), $this->groupid(), json_encode($this->tagid_list()), $this->comments());
        }
        return $id;
    }

    public static function sync() {
        $wechat = WeChat::inst();
        $users = $wechat->get_all_users();
        $users = $wechat->get_user_info($users);

        $weusers = array();
        foreach ($users as $user) {
            logging::d("Debug", $user, false);
            $weusers []= new Wechatuser($user);
        }

        $wechatusers = self::all();
        $removeArr = array();
        foreach ($wechatusers as $wid => $localuser) {
            $has = false;
            foreach ($weusers as $onlineuser) {
                if ($onlineuser->openid() == $localuser->openid()) {
                    $has = true;
                    break;
                }
            }
            if (!$has) {
                $removeArr[] = $wid;
            }
        }
        foreach ($removeArr as $id) {
            self::remove($id);
        }

        foreach ($weusers as $k => $onlineuser) {
            $newuser = true;
            foreach ($wechatusers as $localuser) {
                if ($localuser->openid() == $onlineuser->openid()) {
                    $localuser->assign($onlineuser);
                    $localuser->save();
                    $weusers[$k]->setId($localuser->id());
                    $newuser = false;
                }
            }
            if ($newuser) {
                $id = $onlineuser->save();
                $weusers[$k]->setId($id);
            }
        }
        return $weusers;
    }

    public static function create($id) {
        $summary = db_wechatuser::inst()->get($id);
        return new Wechatuser($summary);
    }

    public static function all($ignore_deleted = true) {
        $items = db_wechatuser::inst()->all();
        $arr = array();
        foreach ($items as $id => $summary) {
            if ($ignore_deleted) {
                if ($summary["status"] == db_wechatuser::STATUS_DELETED) {
                    continue;
                }
            }
            $arr[$id] = new Wechatuser($summary);
        }
        return $arr;
    }

    public static function &cachedAll() {
        $cache = cache::instance();
        $all = $cache->load("class.wechatuser.all", null);
        if ($all === null) {
            $all = Wechatuser::all();
            $cache->save("class.wechatuser.all", $all);
        }
        return $all;
    }

    public static function remove($id) {
        return db_wechatuser::inst()->remove($id);
    }
};

