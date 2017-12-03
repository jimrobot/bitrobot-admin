<?php
include_once(dirname(__FILE__) . "/../config.php");

class report_controller {
    public function preaction($action) {
        login::assert();
    }

    public function price_action() {
        login::assertPerm(Permission::kReportSheet);
        $tpl = new tpl("header", "footer");
        $tpl->display("report/price");
    }

    public function sales_action() {
        login::assertPerm(Permission::kReportSheet);
        $tpl = new tpl("header", "footer");
        $tpl->display("report/sales");
    }

    public function salesanalysing_action() {
        login::assertPerm(Permission::kReportSheet);
        $tpl = new tpl("header", "footer");
        $tpl->display("report/salesanalysing");
    }

    public function balance_action() {
        login::assertPerm(Permission::kBalance);
        $tpl = new tpl("header", "footer");
        $tpl->display("report/balance");
    }

}













