
$(document).ready(function() {
    var allprices = null;
    var page  = new Vue({
        el: "#page-content",
        data: {
            customers: null,
            products: null,
            selectedcustomer: null,
            selectedproduct: null,
            prices: null,
            modifyid: 0,
            search: {
                customer: '',
                product: '',
            },
            modal: {
                title: '',
                price: '',
            }
        },
        methods: {
            selectCustomer: function(event) {
                var ck = $(event.currentTarget).attr("ck");
                // console.debug(ck);
                this.selectedcustomer = this.customers[ck];
                select_customer();
            },
            selectProduct: function(event) {
                var pk = $(event.currentTarget).attr("pk");
                for (var k in this.products) {
                    this.products[k].selected = false;
                }
                this.products[pk].selected = true;
                this.selectedproduct = this.products[pk];
            },
            ensureSelectProduct: function(event) {
                if (this.selectedproduct == null) {
                    alert("请选择一个产品");
                    return;
                }
                this.modal.price = this.selectedproduct.price;
                this.modal.title = this.selectedproduct.title;
                $("#theModal").modal("hide");
                $("#priceModal").modal();
            },
            ensure: function(event) {
                if (this.modal.price.length == 0) {
                    alert("请输入价格。");
                    return;
                }
                __request("api.v1.price.update", {
                    pid: this.selectedproduct.id,
                    cid: this.selectedcustomer.id,
                    price: this.modal.price,
                }, function(res) {
                    console.debug(res);
                    allprices = res.data;
                    select_customer();
                });
                $("#priceModal").modal("hide");
            },
            edit: function(event) {
                var pk = $(event.currentTarget).attr("pk");
                var product = this.prices[pk].product;
                var price = this.prices[pk].price;
                this.selectedproduct = product;
                this.modal.title= product.title;
                this.modal.price = price;
                this.modifyid = 1;
                $("#priceModal").modal();
            },
            remove: function(event) {
                $("#priceModal").modal("hide");
                $("#theDeleteModal").modal();
            },
            doRemove: function(event) {
                if (this.selectedproduct == null || this.selectedcustomer == null) {
                    return;
                }
                __request("api.v1.price.remove", {
                    pid: this.selectedproduct.id,
                    cid: this.selectedcustomer.id,
                }, function(res) {
                    allprices = res.data;
                    select_customer();
                });
                $("#price").modal("hide");
                $("#theDeleteModal").modal("hide");
            },
            addNew: function(event) {
                this.modal.title= "新增供应商";
                this.modal.price = '';
                this.modifyid = 0;
                this.selectedproduct = null;
                for (var k in this.products) {
                    this.products[k].selected = false;
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
            searchProduct: function(event) {
                var text = this.search.product;
                for (var k in this.products) {
                    this.products[k].filteredout = false;
                    if (text.length > 0) {
                        if (this.products[k].title.indexOf(text) >= 0 ||
                            this.products[k].standard.indexOf(text) >= 0 ||
                            this.products[k].mode.indexOf(text) >= 0 ||
                            this.products[k].price.indexOf(text) >= 0 ||
                            this.products[k].category.name.indexOf(text) >= 0) {
                            this.products[k].filteredout = false;
                        } else {
                            this.products[k].filteredout = true;
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


    var find_product = function(pid) {
        for (var k in page.products) {
            if (page.products[k].id == pid) {
                return page.products[k];
            }
        }
        return null;
    }

    var select_customer = function() {
        for (var k in page.products) {
            page.products[k].show = true;
        }

        var prices = [];
        for (var k in allprices) {
            if (allprices[k].cid == page.selectedcustomer.id) {
                var product = find_product(allprices[k].pid);
                if (product == null) {
                    continue;
                }
                // console.debug(product);
                prices.push({
                    product: product,
                    price: allprices[k].price,
                });
                product.show = false;
            }
        }
        page.prices = prices;
        console.debug(page.prices);

        for (var k in page.customers) {
            page.customers[k].selected = false;
        }
        page.selectedcustomer.selected = true;
    }

    var reload_data = function() {
        __request("api.v1.price.list", {}, function(res) {
            console.debug(res);
            allprices = res.data;
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

    __request("api.v1.product.list", {}, function(res) {
        console.debug(res);
        for (var k in res.data) {
            res.data[k].selected = false;
            res.data[k].show = true;
            res.data[k].filteredout = false;
        }
        page.products = res.data;
    });
});


