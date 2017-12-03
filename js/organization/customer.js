
$(document).ready(function() {
    var CUSTOMER_CATEGORY_INDEX = 3;
    var customer = new Vue({
        el: "#page-content",
        data: {
            category: null,
            categories: null,
            customers: null,
            currentNode: null,
            modifyid: 0,
            modal: {
                title: '',
                name: '',
                showname: '',
                category: CUSTOMER_CATEGORY_INDEX,
                telephone: '',
                email: '',
                address: '',
                comments: '',
                vprovince: '',
                vcity: '',
                vdistrict: '',
            }
        },
        updated: function() {
            $("#province").val(this.modal.vprovince);
            $("#province").trigger("change");
            $("#city").val(this.modal.vcity);
            $("#city").trigger("change");
            $("#district").val(this.modal.vdistrict);
            $("#district").trigger("change");
        },
        methods: {
            selectAll: function() {
                select_node(CUSTOMER_CATEGORY_INDEX);
            },
            ensure: function(event) {
                if (this.modal.name.length == 0 || this.modal.showname.length == 0) {
                    alert("请输入名称。");
                    return;
                }
                var province = $("#province").val();
                var city = $("#city").val();
                var district = $("#district").val();
                console.debug(province + city + district);
                if (this.modifyid == 0) {
                    __request("api.v1.customer.add", {
                        name: this.modal.name,
                        title: this.modal.showname,
                        category: this.modal.category,
                        telephone: this.modal.telephone,
                        email: this.modal.email,
                        address: this.modal.address,
                        comments: this.modal.comments,
                        province: province,
                        city: city,
                        district: district,
                    }, reload_data);
                } else {
                    __request("api.v1.customer.modify", {
                        id: this.modifyid,
                        name: this.modal.name,
                        title: this.modal.showname,
                        category: this.modal.category,
                        telephone: this.modal.telephone,
                        email: this.modal.email,
                        address: this.modal.address,
                        comments: this.modal.comments,
                        province: province,
                        city: city,
                        district: district,
                    }, reload_data);
                }
                $("#theModal").modal("hide");
            },
            edit: function(event) {
                if ($(event.target).is("a")) {
                    return;
                }
                var vk = $(event.currentTarget).attr("vk");
                var vid = this.customers[vk].id;
                this.modifyid = vid;
                this.modal.title= "修改供应商";
                this.modal.name = this.customers[vk].name;
                this.modal.showname = this.customers[vk].title;
                this.modal.category = this.customers[vk].category.id;
                this.modal.telephone = this.customers[vk].telephone;
                this.modal.email = this.customers[vk].email;
                this.modal.address = this.customers[vk].address;
                this.modal.comments = this.customers[vk].comments;
                this.modal.vprovince = this.customers[vk].province;
                this.modal.vcity = this.customers[vk].city;
                this.modal.vdistrict = this.customers[vk].district;
                // console.debug(vid);
                $("#theModal").modal();
            },
            remove: function(event) {
                $("#theModal").modal("hide");
                $("#theDeleteModal").modal();
            },
            doRemove: function(event) {
                __request("api.v1.customer.remove", {id: this.modifyid}, reload_data);
                $("#theModal").modal("hide");
                $("#theDeleteModal").modal("hide");
            },
            addNew: function(event) {
                this.modal.title= "新增供应商";
                this.modal.name = '';
                this.modal.showname = '';
                this.modal.category = CUSTOMER_CATEGORY_INDEX;
                this.modal.telephone = '';
                this.modal.email = '';
                this.modal.address = '';
                this.modal.comments = '';
                this.modifyid = 0;
                $("#theModal").modal();
            },
            enterCategory: function(event) {
                var target = $(event.target);
                if ($(target).is("button") || $(target).is("i")) {
                    return;
                }
                var cid = $(event.currentTarget).attr("cid");
                // console.debug(cid);
                select_node(cid);
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

    var enum_node = function(node, callback) {
        callback(node)
        if (typeof(node.children) == "undefined") {
            return;
        }
        for (var k in node.children) {
            enum_node(node.children[k], callback);
        }
    }

    var find_node = function(node, id) {
        if (node.id == id) {
            return node;
        }
        if (typeof (node.children) == "undefined") {
            return null;
        }
        for (var k in node.children) {
            var ret = find_node(node.children[k], id);
            if (ret != null) {
                return ret;
            }
        }
        return null;
    };

    var select_node = function(cid) {
        // console.debug("select node: " + cid);
        var currentNode = find_node(customer.category, cid);
        enum_node(customer.category, function(node) {
            node.selected = false;
            if (currentNode != null) {
                for (var k in currentNode.ancestors) {
                    if (currentNode.ancestors[k] == node.id) {
                        node.selected = true;
                        break;
                    }
                }
            }
        });
        if (currentNode != null) {
            currentNode.selected = true;
            // console.debug(currentNode.descendants);
            // CUSTOMER_CATEGORY_INDEX
            for (var k in customer.customers) {
                if (currentNode.id == customer.customers[k].category.id || (cid == CUSTOMER_CATEGORY_INDEX && customer.customers[k].category.id == 0)) {
                    customer.customers[k].show = true;
                    continue;
                }
                var flag = false;
                for (var k1 in currentNode.descendants) {
                    if (currentNode.descendants[k1] == customer.customers[k].category.id) {
                        customer.customers[k].show = true;
                        flag = true;
                        break;
                    }
                }
                if (!flag) {
                    customer.customers[k].show = false;
                }
            }
        }
        customer.currentNode = currentNode;
    }

    var reload_data = function() {
        __request("api.v1.customer.list", {}, function(res) {
            console.debug(res);
            for (var k in res.data) {
                res.data[k].show = true;
            }
            customer.customers = res.data;
            if (customer.currentNode != null) {
                select_node(customer.currentNode.id);
            }
        });
    }

    __request("api.v1.category.list", {}, function(res) {
        console.debug(res);
        enum_node(res.data, function(node) {
            node.selected = false;
        });
        // res.data.children[CUSTOMER_CATEGORY_INDEX - 1].name = "供应商";
        customer.category = res.data.children[CUSTOMER_CATEGORY_INDEX - 1];

        categories = []; // [{name: '所有分类', id: 3}];
        enum_node(res.data.children[CUSTOMER_CATEGORY_INDEX - 1], function(node) {
            categories.push(node);
        });
        customer.categories = categories;

        reload_data();
    });

    $("#theDistpicker").distpicker();
});


