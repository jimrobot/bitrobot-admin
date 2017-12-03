<?php
include_once(dirname(__FILE__) . "/../config.php");

class Notice {

    public static function &instance() {
        $cache = cache::instance();
        $instance = $cache->load("class.notice.instance", null);
        if ($instance === null) {
            $instance = new Notice();
            $cache->save("class.notice.instance", $instance);
        }
        return $instance;
    }

    private $mNotices = null;
    private function Notice() {
        $items = db_notice::inst()->all();
        $this->mNotices = array();
        foreach ($items as $id => $summary) {
            $this->mNotices[$id] = $summary;
        }
    }

    public function getOpenids($cid) {
        $wechatusers = Wechatuser::cachedAll();
        $openids = array();
        foreach ($this->mNotices as $notice) {
            if ($notice["notice"] == 0) {
                continue;
            }
            if ($notice["cid"] == $cid) {
                $wid = $notice["wid"];
                if (isset($wechatusers[$wid])) {
                    $weuser = $wechatusers[$wid];
                    $openids [] = $weuser->openid();
                }
            }
        }
        return $openids;
    }

    public function packInfo() {
        $info = array();
        if (!empty($this->mNotices)) {
            foreach ($this->mNotices as $record) {
                $info []= array("cid" => $record["cid"], "wid" => $record["wid"], "notice" => $record["notice"]);
            }
        }
        return $info;
    }

    public function update($cid, $wid, $notice) {
        foreach ($this->mNotices as $k => $pr) {
            if ($pr["cid"] == $cid && $pr["wid"] == $wid) {
                $this->mNotices[$k]["notice"] = $notice;
                return db_notice::inst()->modify($cid, $wid, $notice);
            }
        }
        $this->mNotices[] = array("cid" => $cid, "wid" => $wid, "notice" => $notice);
        return db_notice::inst()->add($cid, $wid, $notice);
    }

    public function remove($cid, $wid) {
        $ret = db_notice::inst()->remove($cid, $wid);
        if ($ret !== false) {
            foreach ($this->mNotices as $k => $pr) {
                if ($pr["cid"] == $cid && $pr["wid"] == $wid) {
                    unset ($this->mNotices[$k]);
                }
            }
        }
        return $ret;
    }
};

