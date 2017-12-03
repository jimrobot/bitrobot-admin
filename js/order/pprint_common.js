$(document).ready(function() {
    var page = new Vue({
        el: "#page-content",
        data: {
            order: null,
        },
        methods: {
            doRemove: function(event) {
                __request("api.v1." + stuff + "order.delete", {serial: serial}, function(res) {
                    console.debug(res);
                    if (res.op == "result" && res.data.code != 0) {
                        alert("失败：" + res.data.reason);
                        return;
                    }
                    alert("删除成功！");
                    document.location.href = "?order/" + stuff;
                });
            },
            doEnsure: function(event) {
                __request("api.v1." + stuff + "order.ensure", {serial: serial}, function(res) {
                    console.debug(res);
                    if (res.op == "result" && res.data.code != 0) {
                        alert("数据库操作失败，请不要使用此单并尽快联系管理员处理。");
                        return;
                    }
                    alert("确认发货成功！");
                    document.location.href = "?order/" + stuff;
                });
            },
        },
    });

    var reload_data = function() {
        __request("api.v1." + stuff + "order.orderinfo", {serial: serial}, function(res) {
            console.debug(res);
            page.order = res.data;
        });
    }
    reload_data();

});


