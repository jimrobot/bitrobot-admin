$(document).ready(function() {
    var filelist = new Vue({
        el: "#page-wrapper",
        data: {
            files: null,
            editfid: 0,
            modal: {
                label: '新建用户',
                filename: '',
                title: '',
                comments: '',
            },
        },
        methods: {
            update: function(event) {
                var uk = $(event.currentTarget).attr("uk");
                var gk = $(event.currentTarget).attr("gk");
                var uid = this.users[uk].id;
                var gid = this.users[uk].groups[gk].id;
                var join = this.users[uk].groups[gk].join ? 1 : 0;
                console.debug("uid = " + uid);
                console.debug("gid = " + gid);
                console.debug("join = " + join);
                __request("api.v1.user.setusergroup", {uid: uid, gid: gid, join: join}, function(res) {
                    if (res.data.code != 0) {
                        document.location.reload();
                    }
                }, function(res) {
                    document.location.reload();
                });
            },
            removeUser: function(event) {
                // var uk = $(event.currentTarget).attr("uk");
                // var uid = this.users[uk].id;
                var uid = this.edituid;
                console.debug(uid);
                if (uid == 0) {
                    return;
                }
                __request("api.v1.user.removeuser", {uid: uid}, function(res) {
                    if (res.data.code == 0) {
                        reload_data();
                    }
                });
                $("#newUserModal").modal('hide');
            },
            editUser: function(event) {
                var uk = $(event.currentTarget).attr("uk");
                console.debug(uk);
                console.debug(this.users);
                var uid = this.users[uk].id;
                this.modal.label = "修改用户";
                this.modal.username = this.users[uk].username;
                this.modal.password = this.users[uk].password;
                this.modal.nickname = this.users[uk].nickname;
                this.modal.telephone = this.users[uk].telephone;
                this.modal.email = this.users[uk].email;
                this.modal.comments = this.users[uk].comments;
                this.edituid = uid;
                $("#newUserModal").modal();
            }
        }
    });
    var reload_data = function() {
        __request("api.v1.user.getusers", {}, function(res) {
            console.debug(res);
            filelist.users = res.data;
        });
    };
    reload_data();

    $("#addNewUser").click(function() {
        var username = filelist.modal.username.trim();
        var password = filelist.modal.password.trim();
        if (username.length == 0 || password.length == 0) {
            return;
        }

        if (filelist.edituid == 0) {
            __request("api.v1.user.adduser", {
                username: username,
                password: password,
                nickname: filelist.modal.nickname,
                telephone: filelist.modal.telephone,
                email: filelist.modal.email,
                comments: filelist.modal.comments
            }, function(res) {
                console.debug(res.data);
                filelist.users.push(res.data);
            });
        } else {
            __request("api.v1.user.edituser", {
                uid: filelist.edituid,
                username: username,
                password: password,
                nickname: filelist.modal.nickname,
                telephone: filelist.modal.telephone,
                email: filelist.modal.email,
                comments: filelist.modal.comments
            }, function(res) {
                console.debug(res.data);
                for (var k in filelist.users) {
                    if (filelist.users[k].id == res.data.id) {
                        for (var k1 in res.data) {
                            filelist.users[k][k1] = res.data[k1];
                        }
                    }
                }
            });
        }
        $("#newUserModal").modal("hide");
    });

    $("input.form-control").change(function() {
        $(this).parent().removeClass("has-error");
    });

    $("#newFile").click(function() {
        filelist.editfid = 0;
        filelist.modal.label = "上传文件";
        filelist.modal.filename = "";
        filelist.modal.title= "";
        filelist.modal.comments = "";
        $("#newFileModal").modal();
    });

});

