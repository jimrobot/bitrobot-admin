
$(document).ready(function() {
    var editoptionid = 0;
    var settings = new Vue({
        el: "#page-wrapper",
        data: {
            options: null,
            modal: {
                title: '',
                value: '',
            }
        },
        methods: {
            update: function(event) {
                var ok = $(event.currentTarget).attr("ok");
                if (this.options[ok].type == 1) {
                    var id = this.options[ok].id;
                    var value = this.options[ok].value ? 1 : 0;
                    console.debug(value);
                    __request("api.v1.setting.editsetting", {id: id, value: value}, reload_data);
                    return;
                }
                editoptionid = this.options[ok].id;
                this.modal.title = this.options[ok].title;
                this.modal.value = this.options[ok].value;
                $("#settingModal").modal();
            },
            ensure: function(event) {
                __request("api.v1.setting.editsetting", {id: editoptionid, value: this.modal.value.trim()}, reload_data);
                $("#settingModal").modal("hide");
            }
        }
    });

    var reload_data = function() {
        __request("api.v1.setting.listsettings", {}, function(res) {
            console.debug(res);
            settings.options = res.data;
        });
    };
    reload_data();

});

