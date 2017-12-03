var __load_order_page = function(stuff) {
    console.debug(stuff);
    var page = new Vue({
        el: "#page-content",
        data: {
            ordertypes: null,
            // category: null,
            // categories: null,
            products: null,
            customers: null,
            vendors: null,
            depots: null,
            order: [],
            currentOrder: null,
            currentOrderType: null,
            currentCustomerType: '客户',
            currentCustomer: {id: 0, title: ''},
            currentDepot: {id: 0, name: ''},
            comments: '',
            orderSerial: '',
            operatorType: serial.length > 0 ? "修改" : "生成",
            typeModal: {
                type: 0,
            },
            modal: {
                title: '',
                customers: null,
                selectedCustomer: null,
            },
            productModal: {
                selectedProduct: null,
            },
            infoModal: {
                currentProduct: null,
                count: 0,
                largess: 0,
            },
            removeModal: {
                currentProduct: null,
            },
            depotModal: {
                depot: 0,
            },
            search: {
                customer: '',
                product: '',
            },
        },
        methods: {
            ///// order-type
            showTypeModal: function(event) {
                this.typeModal.type = this.currentOrderType.type;
                $("#theTypeModal").modal();
            },
            ensureSelectType: function(event) {
                console.debug(this.typeModal.type);
                for (var k in this.ordertypes) {
                    if (this.ordertypes[k].type == this.typeModal.type) {
                        this.currentOrderType = this.ordertypes[k];
                    }
                }
                set_customer_info();
                $("#theTypeModal").modal("hide");

                // if (this.currentOrderType.target == "vendor") {
                //     if (this.modal.customers != this.vendors) {
                //         this.currentCustomer = {id: 0, title: ''};
                //         this.modal.customers = this.vendors;
                //         this.currentCustomerType = "供应商";
                //     }
                // } else {
                //     if (this.modal.customers != this.customers) {
                //         this.currentCustomer = {id: 0, title: ''};
                //         this.modal.customers = this.customers;
                //         this.currentCustomerType = "客户";
                //     }
                // }

            },
            ///// depot
            showDepotModal: function(event) {
                this.depotModal.depot = this.currentDepot.id;
                $("#theDepotModal").modal();
            },
            ensureSelectDepot: function(event) {
                if (typeof(this.depotModal.depot) == "undefined" || this.depotModal.depot == 0) {
                    alert("请选择一个仓库.");
                    return;
                }
                console.debug(this.depotModal.depot);
                for (var k in this.depots) {
                    if (this.depots[k].id == this.depotModal.depot) {
                        this.currentDepot = this.depots[k];
                    }
                }
                $("#theDepotModal").modal("hide");
            },
            ///// customer
            modalCustomer: function(event) {
                this.modal.selectedCustomer = null;
                $("#theCustomerModal").modal();
            },
            searchCustomer: function(event) {
                var text = this.search.customer;
                for (var k in this.modal.customers) {
                    this.modal.customers[k].filteredout = false;
                    if (text.length > 0) {
                        if (this.modal.customers[k].name.indexOf(text) >= 0 ||
                            this.modal.customers[k].title.indexOf(text) >= 0 ||
                            this.modal.customers[k].comments.indexOf(text) >= 0 ||
                            this.modal.customers[k].category.name.indexOf(text) >= 0) {
                            this.modal.customers[k].filteredout = false;
                        } else {
                            this.modal.customers[k].filteredout = true;
                        }
                    }
                }
            },
            selectCustomer: function(event) {
                var ck = $(event.currentTarget).attr("ck");
                for (var k in this.modal.customers) {
                    // console.debug(ck + " vs " + k);
                    this.modal.customers[k].selected = (k == ck);
                    // console.debug(this.modal.customers[k].selected);
                }
                this.modal.selectedCustomer = this.modal.customers[ck];
            },
            ensureSelectCustomer: function(event) {
                if (this.modal.selectedCustomer == null) {
                    alert("请选择一项");
                    return;
                }
                this.currentCustomer = this.modal.selectedCustomer;
                $("#theCustomerModal").modal("hide");
            },
            ///// product
            addNew: function(event) {
                for (var k in this.products) {
                    var added = false;
                    for (var k1 in this.order) {
                        if (this.order[k1].product.id == this.products[k].id) {
                            added = true;
                            break;
                        }
                    }
                    this.products[k].show = !added;
                }
                $("#theProductModal").modal();
            },
            searchProduct: function(event) {
                var text = this.search.product;
                for (var k in this.products) {
                    this.products[k].filteredout = false;
                    if (text.length > 0) {
                        if (this.products[k].title.indexOf(text) >= 0 ||
                            this.products[k].standard.indexOf(text) >= 0 ||
                            this.products[k].mode.indexOf(text) >= 0 ||
                            this.products[k].unit.indexOf(text) >= 0 ||
                            this.products[k].price.indexOf(text) >= 0 ||
                            this.products[k].serial.indexOf(text) >= 0 ||
                            this.products[k].vendor.indexOf(text) >= 0 ||
                            this.products[k].comments.indexOf(text) >= 0 ||
                            this.products[k].category.name.indexOf(text) >= 0) {
                            this.products[k].filteredout = false;
                        } else {
                            this.products[k].filteredout = true;
                        }
                    }
                }
            },
            selectProduct: function(event) {
                var vk = $(event.currentTarget).attr("vk");
                for (var k in this.products) {
                    this.products[k].selected = (k == vk);
                }
                this.productModal.selectedProduct = this.products[vk];
            },
            ensureSelectProduct: function(event) {
                if (this.productModal.selectedProduct == null) {
                    alert("请选择一项");
                    return;
                }
                this.order.push({
                    "product": this.productModal.selectedProduct,
                    "count": 0,
                    "largess": 0,
                });
                $("#theProductModal").modal("hide");
            },
            removeProduct: function(event) {
                $("#theInfoModal").modal("hide");
                $("#theDeleteModal").modal();
            },
            showInfoModal: function(event) {
                var rk = $(event.currentTarget).attr("rk");
                // console.debug(rk);
                var order = this.order[rk];
                // console.debug(order);
                this.currentOrder = this.order[rk];
                this.infoModal.currentProduct = order.product;
                this.infoModal.count = order.count;
                this.infoModal.largess = order.largess;
                $("#theInfoModal").modal();
            },
            ensureProductInfo: function(event) {
                this.currentOrder.count = this.infoModal.count;
                this.currentOrder.largess = this.infoModal.largess;
                $("#theInfoModal").modal("hide");
            },
            doRemove: function(event) {
                for (var k in this.order) {
                    if (this.order[k].product.id == this.currentOrder.product.id) {
                        // console.debug(k);
                        this.order.splice(k, 1);
                    }
                }
                // console.debug(this.order);
                $("#theDeleteModal").modal("hide");
            },
            generateOrder: function(event) {
                if (this.order.length == 0) {
                    alert("请添加产品！");
                    return;
                }
                if (this.currentCustomer.id == 0 && this.currentOrderType.target != "production") {
                    alert("请选择客户或供应商");
                    return;
                }
                if (this.currentDepot.id == 0) {
                    alert("请选择发货或进货仓库");
                    return;
                }
                var data = [];
                for (var k in this.order) {
                    data.push({
                        pid: this.order[k].product.id,
                        count: this.order[k].count,
                        largess: this.order[k].largess,
                    });
                }
                console.debug(data);
                __request(
                    "api.v1." + stuff + "order.add",
                    {
                        serial: serial,
                        type: this.currentOrderType.type,
                        customer: this.currentCustomer.id,
                        depot: this.currentDepot.id,
                        order: data,
                        comments: this.comments,
                    },
                    function(res) {
                        console.debug(res);
                        page.orderSerial = res.data;
                        $("#theDoneModal").modal();
                    }
                );
            },
            moreOrder: function() {
                document.location.reload();
            },
            viewOrder: function() {
                document.location.href = "?order/" + stuff;
            },
        },
    });


    var set_customer_info = function() {
        console.debug(page.currentOrderType.target);
        if (page.currentOrderType.target == "vendor") {
            if (page.modal.customers != page.vendors) {
                page.currentCustomer = {id: 0, title: ''};
                page.modal.customers = page.vendors;
                page.currentCustomerType = "供应商";
            }
        } else if (page.currentOrderType.target == "customer") {
            if (page.modal.customers != page.customers) {
                page.currentCustomer = {id: 0, title: ''};
                page.modal.customers = page.customers;
                page.currentCustomerType = "客户";
            }
        } else if (page.currentOrderType.target == "production") {
            page.currentCustomer = {id: 0, title: '领料生产'};
        }
    };


    var reload_data = function() {
        __request("api.v1." + stuff + "order.types", {}, function(res) {
            console.debug(res);
            page.ordertypes = res.data;
            page.currentOrderType = res.data[0];
            page.typeModal.type = res.data[0].type;
            __request("api.v1.customer.list", {}, function(res) {
                console.debug(res);
                for (var k in res.data) {
                    res.data[k].selected = false;
                    res.data[k].filteredout = false;
                }
                page.customers = res.data;
                __request("api.v1.vendor.list", {}, function(res) {
                    console.debug(res);
                    for (var k in res.data) {
                        res.data[k].selected = false;
                        res.data[k].filteredout = false;
                    }
                    page.vendors = res.data;
                    set_customer_info();

                    __request("api.v1.depot.list", {}, function(res) {
                        console.debug(res);
                        page.depots = res.data;
                        if (res.data.length == 1) {
                            page.currentDepot = res.data[0];

                        }

                        __request("api.v1." + stuff + ".list", {}, function(res) {
                            console.debug(res);
                            for (var k in res.data) {
                                res.data[k].show = true;
                                res.data[k].selected = false;
                                res.data[k].filteredout = false;
                            }
                            page.products = res.data;


                            if (serial.length > 0) {
                                __request("api.v1." + stuff + "order.orderinfo", {serial: serial}, function(res) {
                                    console.debug(res);
                                    var order = res.data;
                                    for (var k in page.ordertypes) {
                                        if (page.ordertypes[k].type == order.type) {
                                            page.currentOrderType = page.ordertypes[k];
                                            break;
                                        }
                                    }
                                    set_customer_info();

                                    if (order.customer != null && order.typeTarget != "production") {
                                        for (var k in page.modal.customers) {
                                            if (page.modal.customers[k].id == order.customer.id) {
                                                page.currentCustomer = page.modal.customers[k];
                                                break;
                                            }
                                        }
                                    }

                                    for (var k in page.depots) {
                                        if (page.depots[k].id == order.depot1.id) {
                                            page.currentDepot = page.depots[k];
                                            break;
                                        }
                                    }

                                    var find_product = function(pid) {
                                        for (var k in page.products) {
                                            if (page.products[k].id == pid) {
                                                return page.products[k];
                                            }
                                        }
                                        return null;
                                    }

                                    for (var k in order.records) {
                                        var pid = stuff =="product" ? order.records[k].productid : order.records[k].materialid;
                                        var product = find_product(pid);
                                        if (product == null) {
                                            console.debug("missing product: " + pid);
                                            continue;
                                        }
                                        page.order.push({
                                            "product": product,
                                            "count": order.records[k].count,
                                            "largess": order.records[k].largess,
                                        });
                                    }
                                });
                            }
                        });
                    }); // depot
                }); // vendor
            }); // customer
        }); // stufforder.types
    }
    reload_data();

};


