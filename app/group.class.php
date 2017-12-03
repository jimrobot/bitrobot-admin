<?php
include_once(dirname(__FILE__) . "/../config.php");

class Group {
    private $mSummary = null;
    private $mPermissions = null;

    public function Group($summary = array()) {
        if (empty($summary)) {
            $summary = array("id" => 0, "name" => "", "permissions" => "");
        }
        $this->mSummary = $summary;
    }

    public function id() {
        return $this->mSummary["id"];
    }

    public function name() {
        return $this->mSummary["name"];
    }

    public function perms() {
        return $this->mSummary["permissions"];
    }

    public function setName($name) {
        $this->mSummary["name"] = $name;
    }

    public function hasPerm($pkey) {
        $arr = $this->permsarr();
        return in_array($pkey, $arr);
    }

    public function addPerm($pkey) {
        $arr = $this->permsarr();
        if (!in_array($pkey, $arr)) {
            $arr []= $pkey;
        }
        logging::d("Debug", "add perm: $pkey");
        $this->mSummary["permissions"] = implode(",", $arr);
    }

    public function removePerm($pkey) {
        $arr = $this->permsarr();
        foreach ($arr as $k => $v) {
            if ($v == $pkey) {
                unset($arr[$k]);
            }
        }
        logging::d("Debug", "remove perm: $pkey");
        $this->mSummary["permissions"] = implode(",", $arr);
    }

    public function save() {
        $id = $this->id();
        if ($id == 0) {
            $id = db_group::inst()->add($this->name(), $this->perms());
            if ($id !== false) {
                $this->mSummary["id"] = $id;
            }
        } else {
            $id = db_group::inst()->modify($id, $this->name(), $this->perms());
        }
        return $id;
    }

    public function permsarr() {
        $perms = $this->perms();
        if (empty($perms)) {
            return array();
        }
        return explode(",", $perms);
    }

    public function &permissions() {
        if (empty($this->mPermissions)) {
            $perms = $this->perms();
            if (empty($perms)) {
                return array();
            }
            $perms = explode(",", $perms);
            $this->mPermissions = array();
            foreach ($perms as $perm) {
                $this->mPermissions []= new Permission($perm);
            }
            return $arr;
        }
        return $this->mPermissions;
    }

    public function packInfo($pack_all_permissions = true) {
        $allperms = null;
        if ($pack_all_permissions) {
            $allperms = Permission::packInfo();
            $ps = $this->permsarr();
            foreach ($ps as $pkey) {
                foreach ($allperms as $k => $perm) {
                    if ($perm["key"] == $pkey) {
                        $allperms[$k]["granted"] = 1;
                        break;
                    }
                }
            }
        } else {
            $perms = Permission::packInfo();
            $pss = $this->permsarr();
            foreach ($perms as $k => $perm) {
                if (in_array($perm["key"], $pss)) {
                    $perm["granted"] = 1;
                    $allperms []= $perm;
                }
            }
        }
        return array("id" => $this->id(), "name" => $this->name(), "permissions" => $allperms);
    }


    public static function create($gid) {
        $group = db_group::inst()->get($gid);
        return new Group($group);
    }

    public static function all() {
        $groups = db_group::inst()->all();
        $arr = array();
        foreach ($groups as $gid => $group) {
            $arr[$gid] = new Group($group);
        }
        return $arr;
    }

    public static function remove($gid) {
        return db_group::inst()->remove($gid);
    }
};

