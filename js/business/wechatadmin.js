
$(document).ready(function() {
    var allnotices = null;
    var page  = new Vue({
        el: "#page-content",
        data: {
            wechatusers: null,
            wechatadmins: null,
            selectedwechatuser: null,
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
                __request("api.v1.wechatadmin.add", {wechatuser: this.selectedwechatuser.id, notify: 0}, reload_data);
                $("#theModal").modal("hide");
            },
            edit: function(event) {
                var vk = $(event.currentTarget).attr("vk");
                var weid = this.wechatadmins[vk].wechatuser.id;
                var notify = this.wechatadmins[vk].notify ? 1 : 0;
                __request("api.v1.wechatadmin.modify", {weid: weid, notify: notify}, reload_data);
            },
            remove: function(event) {
                var vk = $(event.currentTarget).attr("vk");
                console.debug(vk);
                this.selectedwechatuser = this.wechatadmins[vk].wechatuser;
                console.debug(this.selectedwechatuser);
                $("#theDeleteModal").modal();
            },
            doRemove: function(event) {
                if (this.selectedwechatuser == null) {
                    return;
                }
                __request("api.v1.wechatadmin.remove", {weid: this.selectedwechatuser.id}, reload_data);
                $("#theDeleteModal").modal("hide");
            },
            addNew: function(event) {
                this.selectedwechatuser = null;
                for (var k in this.wechatusers) {
                    this.wechatusers[k].selected = false;
                }

                for (var k in this.wechatusers) {
                    page.wechatusers[k].show = true;
                }

                for (var k in this.wechatadmins) {
                    this.wechatadmins[k].wechatuser.show = false;
                }

                $("#theModal").modal();
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

    var find_wechatuser = function(weuserid) {
        for (var k in page.wechatusers) {
            if (page.wechatusers[k].id == weuserid) {
                return page.wechatusers[k];
            }
        }
        return null;
    }


    var reload_data = function() {
        __request("api.v1.wechatuser.list", {}, function(res) {
            console.debug(res);
            for (var k in res.data) {
                res.data[k].selected = false;
                res.data[k].show = true;
                res.data[k].filteredout = false;
            }
            page.wechatusers = res.data;

            __request("api.v1.wechatadmin.list", {}, function(res) {
                console.debug(res);
                var users = [];
                for (var k in res.data) {
                    var weid = res.data[k].wechatuser;
                    var weuser = find_wechatuser(weid);
                    // console.debug("weid = " + weid);
                    // console.debug(weuser);
                    if (weuser == null) {
                        continue;
                    }
                    users.push({
                        wechatuser: weuser,
                        notify: res.data[k].notify == 1,
                    });
                }
                // console.debug(users);
                page.wechatadmins = users;
            });
        });
    }
    reload_data();
});


