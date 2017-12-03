<?php
include_once(dirname(__FILE__) . "/../config.php");

class Category {
    private $mSummary = null;
    private $mChildren = null;
    private $mParent = null;

    public function Category($summary = array()) {
        if (empty($summary)) {
            $summary = array(
                "id" => 0,
                "name" => "",
                "pid" => 0,
            );
        }
        $this->mSummary = $summary;
    }

    public function id() {
        return $this->mSummary["id"];
    }

    public function pid() {
        return $this->mSummary["pid"];
    }

    public function name() {
        return $this->mSummary["name"];
    }

    public function setName($n) {
        $this->mSummary["name"] = $n;
    }

    public function setPid($pid) {
        $this->mSummary["pid"] = $pid;
    }

    public function &parent() {
        return $this->mParent;
    }

    public function children() {
        return $this->mChildren;
    }

    public function addChild(&$child) {
        $child->mParent = $this;
        $this->mChildren []= $child;
    }

    public function save() {
        $id = $this->id();
        if ($id == 0) {
            $id = db_category::inst()->add($this->name(), $this->pid());
            if ($id !== false) {
                $this->mSummary["id"] = $id;
            }
        } else {
            $id = db_category::inst()->modify($id, $this->name(), $this->pid());
        }
        return $id;
    }

    public function find($cid) {
        if ($this->id() == $cid) {
            return $this;
        }
        foreach ($this->children() as $child) {
            $ret = $child->find($cid);
            if ($ret != null) {
                return $ret;
            }
        }
        return null;
    }

    public function descendants() {
        $arr = array();
        $children = $this->children();
        if (empty($children)) {
            return $arr;
        }
        foreach ($children as $child) {
            $arr []= $child->id();
            $arr = array_merge($arr, $child->descendants());
        }
        return $arr;
    }

    public function packInfo($packTree = false) {
        if ($packTree) {
            $children = $this->children();
            if (empty($children)) {
                return $this->packInfo(false);
            }

            $cinfo = array();
            foreach ($children as $child) {
                $cinfo [] = $child->packInfo(true);
            }
            $info = $this->packInfo(false);
            $info["children"] = $cinfo;
            return $info;
        } else {
            $ancestors = array();
            $category = $this->parent();
            while ($category != null && $category->id() != 0) {
                $ancestors [] = $category->id();
                $category = $category->parent();
            }
            return array(
                "id" => $this->id(),
                "name" => $this->name(), 
                "pid" => $this->pid(), 
                "ancestors" => $ancestors,
                "descendants" => $this->descendants(),
            );
        }
    }

    public static function create($id) {
        $summary = db_category::inst()->get($id);
        return new Category($summary);
    }

    public static function all() {
        $items = db_category::inst()->all();
        $arr = array();
        foreach ($items as $id => $summary) {
            $arr[$id] = new Category($summary);
        }
        return $arr;
    }

    public static function &cachedAll() {
        $cache = cache::instance();
        $all = $cache->load("class.category.all", null);
        if ($all === null) {
            $all = Category::all();
            $cache->save("class.category.all", $all);
        }
        return $all;
    }

    public static function makeTree() {
        $root = new Category();
        $all = self::cachedAll();
        foreach ($all as &$category) {
            if ($category->pid() == 0) {
                $root->addChild($category);
            } else {
                if (isset($all[$category->pid()])) {
                    $all[$category->pid()]->addChild($category);
                }
            }
        }
        unset($category);
        return $root;
    }

    public static function remove($id) {
        $root = self::makeTree();
        $node = $root->find($id);
        if ($node == null) {
            return;
        }
        $delarr = $node->descendants();
        $delarr []= $id;
        logging::d("Debug", $delarr);
        $ret = true;
        foreach ($delarr as $cid) {
            $ret &= db_category::inst()->remove($cid);
        }
        return $ret;
    }
};


