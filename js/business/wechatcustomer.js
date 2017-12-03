
$(document).ready(function() {
    var allnotices = null;
    var page  = new Vue({
        el: "#page-content",
        data: {
            customers: null,
            wechatusers: null,
            selectedcustomer: null,
            selectedwechatuser: null,
            notices: null,
            search: {
                customer: '',
                wechatuser: '',
            },
        },
        methods: {
            selectCustomer: function(event) {
                var ck = $(event.currentTarget).attr("ck");
                // console.debug(ck);
                this.selectedcustomer = this.customers[ck];
                select_customer();
            },
            selectWechatuser: function(event) {
                var wk = $(event.currentTarget).attr("wk");
                for (var k in this.wechatusers) {
                    this.wechatusers[k].selected = false;
                }
                this.wechatusers[wk].selected = true;
                this.selectedwechatuser = this.wechatusers[wk];
            },
            ensureSelectWechatuser: function(event) {
                if (this.selectedwechatuser == null) {
                    alert("请选择一个微信用户");
                    return;
                }
                __request("api.v1.notice.update", {
                    cid: this.selectedcustomer.id,
                    wid: this.selectedwechatuser.id,
                    notice: 0,
                }, function(res) {
                    console.debug(res);
                    allnotices = res.data;
                    select_customer();
                });
                $("#theModal").modal("hide");
            },
            edit: function(event) {
                if (this.selectedcustomer == null) {
                    return;
                }
                var nk = $(event.currentTarget).attr("nk");
                var wid = this.notices[nk].wechatuser.id;
                var notice = this.notices[nk].notice ? 1 : 0;
                __request("api.v1.notice.update", {cid: this.selectedcustomer.id, wid: wid, notice: notice}, function(res) {
                    console.debug(res);
                    allnotices = res.data;
                    select_customer();
                });
            },
            remove: function(event) {
                var nk = $(event.currentTarget).attr("nk");
                console.debug(nk);
                this.selectedwechatuser = this.notices[nk].wechatuser;
                $("#theDeleteModal").modal();
            },
            doRemove: function(event) {
                if (this.selectedwechatuser == null || this.selectedcustomer == null) {
                    return;
                }
                __request("api.v1.notice.remove", {
                    cid: this.selectedcustomer.id,
                    wid: this.selectedwechatuser.id,
                }, function(res) {
                    allnotices = res.data;
                    select_customer();
                });
                $("#theDeleteModal").modal("hide");
            },
            addNew: function(event) {
                this.selectedwechatuser = null;
                for (var k in this.wechatusers) {
                    this.wechatusers[k].selected = false;
                }
                $("#theModal").modal();
            },
            searchCustomer: function(event) {
                var text = this.search.customer;
                for (var k in this.customers) {
                    this.customers[k].filteredout = false;
                    if (text.length > 0) {
                        if (this.customers[k].name.indexOf(text) >= 0 ||
                            this.customers[k].title.indexOf(text) >= 0 ||
                            this.customers[k].category.name.indexOf(text) >= 0) {
                            this.customers[k].filteredout = false;
                        } else {
                            this.customers[k].filteredout = true;
                        }
                    }
                }
            },
            searchWechatuser: function(event) {
                var text = this.search.wechatuser;
                for (var k in this.wechatusers) {
                    this.wechatusers[k].filteredout = false;
                    if (text.length > 0) {
                        if (this.wechatusers[k].nickname.indexOf(text) >= 0 ||
                            this.wechatusers[k].city.indexOf(text) >= 0 ||
                            this.wechatusers[k].province.indexOf(text) >= 0 ||
                            this.wechatusers[k].country.indexOf(text) >= 0 ||
                            this.wechatusers[k].comments.indexOf(text) >= 0) {
                            this.wechatusers[k].filteredout = false;
                        } else {
                            this.wechatusers[k].filteredout = true;
                        }
                    }
                }
            },
        },
        components: {
            'category-tree': {
                name: 'category-tree',
                props: ['node'],
                template: '<li><div class="tree-node" v-bind:class=\'{ "selected": node.selected }\' v-on:click="selectNode" :cid="node.id"><i class="fa fa-fw fa-folder-open"></i>{{node.name}}</div><ul><category-tree v-for="c in node.children" :node="c" :key="c.id"></category-tree></ul></li>',
                data: function() {
                    return {};
                },
                methods: {
                    selectNode: function() {
                        var cid = $(event.currentTarget).attr("cid");
                        // console.debug(cid);
                        select_node(cid);
                    },
                }
            }
        }
    });


    var find_wechatuser = function(pid) {
        for (var k in page.wechatusers) {
            if (page.wechatusers[k].id == pid) {
                return page.wechatusers[k];
            }
        }
        return null;
    }

    var select_customer = function() {
        for (var k in page.wechatusers) {
            page.wechatusers[k].show = true;
        }

        var notices = [];
        for (var k in allnotices) {
            if (allnotices[k].cid == page.selectedcustomer.id) {
                var wechatuser = find_wechatuser(allnotices[k].wid);
                if (wechatuser == null) {
                    continue;
                }
                notices.push({
                    wechatuser: wechatuser,
                    notice: (allnotices[k].notice != 0 ? true : false),
                });
                wechatuser.show = false;
            }
        }
        page.notices = notices;
        // console.debug(page.notices);

        for (var k in page.customers) {
            page.customers[k].selected = false;
        }
        page.selectedcustomer.selected = true;
    }

    var reload_data = function() {
        __request("api.v1.notice.list", {}, function(res) {
            console.debug(res);
            allnotices = res.data;
        });
    }
    reload_data();

    __request("api.v1.customer.list", {}, function(res) {
        console.debug(res);
        for (var k in res.data) {
            res.data[k].selected = false;
            res.data[k].filteredout = false;
        }
        page.customers = res.data;
    });

    __request("api.v1.wechatuser.list", {}, function(res) {
        console.debug(res);
        for (var k in res.data) {
            res.data[k].selected = false;
            res.data[k].show = true;
            res.data[k].filteredout = false;
        }
        page.wechatusers = res.data;
    });
});


