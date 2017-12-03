<?php

class Permission {
    const kUser = "user";
    const kGroup = "group";
    const kSetting = "setting";

    const kCategory = "category";
    const kProduct = "product";
    const kMaterial = "material";
    const kVendor = "vendor";
    const kCustomer = "customer";
    const kSalesman = "salesman";
    const kDepot = "depot";

    const kProductOrder = "productorder";
    const kProductOrderModify = "productordermodify";
    const kMaterialOrder = "materialorder";
    const kMaterialOrderModify = "materialordermodify";

    const kCustomerPrice = "customerprice";
    const kWechatUser = "wechatuser";
    const kWechatNotify = "wechatnotify";
    const kWechatAdmin = "wechatadmin";

    const kReportSheet = "reportsheet";
    const kBalance = "balance";


    private $permission = null;
    public function Permission($perm) {
        if (is_string($perm)) {
            $this->permission = $perm;
        }
    }

    public function title() {
        $all = self::all();
        foreach ($all as $type => $arr) {
            foreach ($arr as $key => $title) {
                if ($key == $this->permission) {
                    return $title;
                }
            }
        }
        return null; 
    }

    // public function packInfo() {
    //     return array("key" => $this->permission, "title" => $this->title());
    // }

    public static function has($perm) {
        return false;
    }

    public static function all() {
        return array(
            "组织" => array(
                self::kCategory => "分类管理",
                self::kProduct => "产品管理",
                self::kMaterial => "原料管理",
                self::kCustomer => "客户信息",
                self::kVendor => "供应商信息",
                self::kSalesman => "业务员管理",
                self::kDepot => "仓库管理",
            ),
            "订单" => array(
                self::kProductOrder => "查看产品订单",
                self::kProductOrderModify => "修改产品订单",
                self::kMaterialOrder => "查看原料订单",
                self::kMaterialOrderModify => "修改原料订单",
            ),
            "业务" => array(
                self::kCustomerPrice => "修改客户价格",
                self::kWechatUser => "微信用户",
                self::kWechatNotify => "微信通知",
                self::kWechatAdmin => "微信管理员",
            ),
            "报表" => array(
                self::kReportSheet => "查看报表",
            ),
            "系统" => array(
                self::kBalance => "结算",
                self::kUser => "用户管理",
                self::kGroup => "用户组管理",
                self::kSetting => "系统设置",
            ),
        );
    }
    public static function getAllPlain() {
        $all = self::all();
        $res = array();
        foreach ($all as $type => $arr) {
            foreach ($arr as $key => $title) {
                $res[$key] = $title;
            }
        }
        return $res;
    }

    public static function packInfo() {
        $all = self::all();
        $res = array();
        foreach ($all as $type => $arr) {
            foreach ($arr as $key => $title) {
                $res []= array("key" => $key, "title" => $title, "granted" => 0);
            }
        }
        return $res;
    }
};


