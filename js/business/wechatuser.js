
$(document).ready(function() {
    var page  = new Vue({
        el: "#page-content",
        data: {
            wechatusers: null,
            modifyid: 0,
            modal: {
                title: '',
                comments: '',
            }
        },
        methods: {
            sync: function(event) {
                reload_data("sync");
            },
            selectCustomer: function(event) {
                var ck = $(event.currentTarget).attr("ck");
                // console.debug(ck);
                this.selectedcustomer = this.customers[ck];
                select_customer();
            },
            ensure: function(event) {
                if (this.modal.comments.length == 0) {
                    $("#theModal").modal("hide");
                    return;
                }
                __request("api.v1.wechatuser.modify", {id: this.modifyid, comments: this.modal.comments}, reload_data);
                $("#theModal").modal("hide");
            },
            edit: function(event) {
                var wk = $(event.currentTarget).attr("wk");
                this.modifyid = this.wechatusers[wk].id;
                this.modal.title = this.wechatusers[wk].nickname;
                this.modal.comments = this.wechatusers[wk].comments;
                $("#theModal").modal();
            },
        },
    });


    var reload_data = function(res) {
        var action = "api.v1.wechatuser.list";
        if (res == "sync") {
            action = "api.v1.wechatuser.sync";
        }
        console.debug(action);
        __request(action, {}, function(res) {
            var wechatusers = res.data;
            console.debug(res);
            // page.wechatusers = res.data;

            var read_customers = function(res) {
                console.debug(res);
                var customers = res.data;

                var find_customer = function(cid) {
                    for (var k in customers) {
                        if (customers[k].id == cid) {
                            return customers[k];
                        }
                    }
                    return null;
                };
                var assoc_customer = function(res) {
                    console.debug(res);
                    var notices = res.data;
                    for (var k1 in wechatusers) {
                        var cuss = [];
                        for (var k2 in notices) {
                            var wid = notices[k2].wid;
                            var cid = notices[k2].cid;
                            if (wid == wechatusers[k1].id) {
                                var customer = find_customer(cid);
                                console.debug(customer);
                                if (customer == null) {
                                    continue;
                                }
                                // customer.notice = notices[k2].notice;
                                cuss.push({
                                    info: customer,
                                    notice: notices[k2].notice,
                                });
                            }
                        }
                        wechatusers[k1].customers = cuss;
                    }
                    page.wechatusers = wechatusers;
                }
                __request("api.v1.notice.list", {}, assoc_customer);

            };
            __request("api.v1.customer.list", {}, read_customers);
        });
    }
    reload_data();

});


