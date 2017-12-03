$(document).ready(function() {
    var edituid = 0;
    var userlist = new Vue({
        el: "#page-wrapper",
        data: {
            users: null,
            modal: {
                label: '新建用户',
                username: '',
                password: '',
                nickname: '',
                telephone: '',
                email: '',
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
                var uk = $(event.currentTarget).attr("uk");
                var uid = this.users[uk].id;
                console.debug(uid);
                __request("api.v1.user.removeuser", {uid: uid}, function(res) {
                    if (res.data.code == 0) {
                        reload_data();
                    }
                });
            },
            editUser: function(event) {
                var uk = $(event.currentTarget).attr("uk");
                var uid = this.users[uk].id;
                this.modal.label = "修改用户";
                this.modal.username = this.users[uk].username;
                this.modal.password = this.users[uk].password;
                this.modal.nickname = this.users[uk].nickname;
                this.modal.telephone = this.users[uk].telephone;
                this.modal.email = this.users[uk].email;
                this.modal.comments = this.users[uk].comments;
                edituid = uid;
                $("#newUserModal").modal();
            }
        }
    });
    var reload_data = function() {
        __request("api.v1.user.getusers", {}, function(res) {
            console.debug(res);
            userlist.users = res.data;
        });
    };
    reload_data();

    $("#addNewUser").click(function() {
        var username = userlist.modal.username.trim();
        var password = userlist.modal.password.trim();
        if (username.length == 0 || password.length == 0) {
            return;
        }

        if (edituid == 0) {
            __request("api.v1.user.adduser", {
                username: username,
                password: password,
                nickname: userlist.modal.nickname,
                telephone: userlist.modal.telephone,
                email: userlist.modal.email,
                comments: userlist.modal.comments
            }, function(res) {
                console.debug(res.data);
                userlist.users.push(res.data);
            });
        } else {
            __request("api.v1.user.edituser", {
                uid: edituid,
                username: username,
                password: password,
                nickname: userlist.modal.nickname,
                telephone: userlist.modal.telephone,
                email: userlist.modal.email,
                comments: userlist.modal.comments
            }, function(res) {
                console.debug(res.data);
                for (var k in userlist.users) {
                    if (userlist.users[k].id == res.data.id) {
                        for (var k1 in res.data) {
                            userlist.users[k][k1] = res.data[k1];
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

    $("#newUser").click(function() {
        edituid = 0;
        userlist.modal.label = "新建用户";
        userlist.modal.username = "";
        userlist.modal.password = "";
        userlist.modal.nickname = "";
        userlist.modal.telephone = "";
        userlist.modal.email = "";
        userlist.modal.comments = "";
        $("#newUserModal").modal();
    });

});

