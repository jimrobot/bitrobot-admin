$(document).ready(function() {
    var PRODUCT_CATEGORY_INDEX = 2;
    var product = new Vue({
        el: "#page-content",
        data: {
            category: null,
            categories: null,
            products: null,
            currentNode: null,
            modifyid: 0,
            modal: {
                title: '',
                name: '',
                category: PRODUCT_CATEGORY_INDEX,
                unit: '',
                standard: '',
                barcode: '',
                qrcode: '',
                serial: '',
                vendor: '',
                mode: '',
                comments: '',
                price: '',
            }
        },
        methods: {
            selectAll: function() {
                select_node(PRODUCT_CATEGORY_INDEX);
            },
            ensure: function(event) {
                if (this.modal.name.length == 0) {
                    alert("请输入名称。");
                    return;
                }
                if (this.modifyid == 0) {
                    __request("api.v1.product.add", {
                        name: this.modal.name,
                        category: this.modal.category,
                        unit: this.modal.unit,
                        standard: this.modal.standard,
                        barcode: this.modal.barcode,
                        qrcode: this.modal.qrcode,
                        serial: this.modal.serial,
                        vendor: this.modal.vendor,
                        mode: this.modal.mode,
                        comments: this.modal.comments,
                        price: this.modal.price,
                    }, reload_data);
                } else {
                    __request("api.v1.product.modify", {
                        id: this.modifyid,
                        name: this.modal.name,
                        category: this.modal.category,
                        unit: this.modal.unit,
                        standard: this.modal.standard,
                        barcode: this.modal.barcode,
                        qrcode: this.modal.qrcode,
                        serial: this.modal.serial,
                        vendor: this.modal.vendor,
                        mode: this.modal.mode,
                        comments: this.modal.comments,
                        price: this.modal.price,
                    }, reload_data);
                }
                $("#theModal").modal("hide");
            },
            edit: function(event) {
                var vk = $(event.currentTarget).attr("vk");
                var vid = this.products[vk].id;
                this.modifyid = vid;
                this.modal.title= "修改产品";
                this.modal.name = this.products[vk].title;
                this.modal.category = this.products[vk].category.id;
                this.modal.unit = this.products[vk].unit;
                this.modal.standard = this.products[vk].standard;
                this.modal.barcode = this.products[vk].barcode;
                this.modal.qrcode = this.products[vk].qrcode;
                this.modal.serial = this.products[vk].serial;
                this.modal.vendor = this.products[vk].vendor;
                this.modal.mode = this.products[vk].mode;
                this.modal.comments = this.products[vk].comments;
                this.modal.price = this.products[vk].price;

                // console.debug(vid);
                $("#theModal").modal();
            },
            remove: function(event) {
                $("#theModal").modal("hide");
                $("#theDeleteModal").modal();
            },
            doRemove: function(event) {
                __request("api.v1.product.remove", {id: this.modifyid}, reload_data);
                $("#theModal").modal("hide");
                $("#theDeleteModal").modal("hide");
            },
            addNew: function(event) {
                this.modifyid = 0;
                this.modal.title= "新增产品";
                this.modal.name = '';
                this.modal.category = PRODUCT_CATEGORY_INDEX;
                this.modal.unit = '';
                this.modal.standard = '';
                this.modal.barcode = '';
                this.modal.qrcode = '';
                this.modal.serial = '';
                this.modal.vendor = '';
                this.modal.mode = '';
                this.modal.comments = '';
                this.modal.price = '';
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
        var currentNode = find_node(product.category, cid);
        enum_node(product.category, function(node) {
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
            // PRODUCT_CATEGORY_INDEX
            for (var k in product.products) {
                if (currentNode.id == product.products[k].category.id || (cid == PRODUCT_CATEGORY_INDEX && product.products[k].category.id == 0)) {
                    product.products[k].show = true;
                    continue;
                }
                var flag = false;
                for (var k1 in currentNode.descendants) {
                    if (currentNode.descendants[k1] == product.products[k].category.id) {
                        product.products[k].show = true;
                        flag = true;
                        break;
                    }
                }
                if (!flag) {
                    product.products[k].show = false;
                }
            }
        }
        product.currentNode = currentNode;
    }

    var reload_data = function() {
        __request("api.v1.product.list", {}, function(res) {
            console.debug(res);
            for (var k in res.data) {
                res.data[k].show = true;
            }
            product.products = res.data;
            if (product.currentNode != null) {
                select_node(product.currentNode.id);
            }
        });
    }

    __request("api.v1.category.list", {}, function(res) {
        console.debug(res);
        enum_node(res.data, function(node) {
            node.selected = false;
        });
        product.category = res.data.children[PRODUCT_CATEGORY_INDEX - 1];

        categories = []; // [{name: '所有分类', id: 3}];
        enum_node(res.data.children[PRODUCT_CATEGORY_INDEX - 1], function(node) {
            categories.push(node);
        });
        product.categories = categories;

        reload_data();
    });
});


