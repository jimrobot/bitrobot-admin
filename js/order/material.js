$(document).ready(function() {
    var page = new Vue({
        el: "#page-content",
        data: {
            orders: null,
        },
        methods: {
            selectCustomer: function(event) {
                var ck = $(event.currentTarget).attr("ck");
                for (var k in this.modal.customers) {
                    // console.debug(ck + " vs " + k);
                    this.modal.customers[k].selected = (k == ck);
                    // console.debug(this.modal.customers[k].selected);
                }
                this.modal.selectedCustomer = this.modal.customers[ck];
            },
            viewOrder: function(serial) {
                var vk = $(event.currentTarget).attr("vk");
                var serial = this.orders[vk].serial;
                console.debug(serial);
                document.location.href = "?action=order.mprint&s=" + serial;
            }

        },
    });

    var reload_data = function() {
        __request("api.v1.materialorder.list", {}, function(res) {
            console.debug(res);
            page.orders = res.data;
        });
    }
    reload_data();

});


