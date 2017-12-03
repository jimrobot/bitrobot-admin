$(document).ready(function() {
    var editgid = 0;
    var grouplist = new Vue({
        el: "#page-wrapper",
        data: {
            groups: null,
            modallabel: "新建用户组",
            editgroupname: '',
        },
        methods: {
            update: function(event) {
                var gk = $(event.currentTarget).attr("gk");
                var pk = $(event.currentTarget).attr("pk");
                var gid = this.groups[gk].id;
                var perms = this.groups[gk].permissions[pk];
                var permkey = perms.key;
                var granted = perms.granted ? 1 : 0;
                console.debug(permkey);
                console.debug(granted);
                __request("api.v1.user.setgroupperm", {gid: gid, perm: permkey, granted: granted}, function(res) {
                }, function(res) {
                    document.location.reload();
                });
            },
            removeGroup: function(event) {
                var gk = $(event.currentTarget).attr("gk");
                var gid = this.groups[gk].id;
                console.debug(gid);
                __request("api.v1.user.removegroup", {gid: gid}, function(res) {
                    if (res.data.code == 0) {
                        // delete this.groups[gk];
                        document.location.reload();
                    }
                });
            },
            editGroup: function(event) {
                var gk = $(event.currentTarget).attr("gk");
                var gid = this.groups[gk].id;
                console.debug(this.groups[gk]);
                this.editgroupname = this.groups[gk].name;
                this.modallabel = "修改用户组";
                editgid = gid;
                $("#newGroupModal").modal();
            }
        }
    });
    __request("api.v1.user.getgroups", {}, function(res) {
        console.debug(res);
        grouplist.groups = res.data;
    });

    $("#addNewGroup").click(function() {
        var name = $("#newGroupName").val();
        name = name.trim();
        if (name.length == 0) {
            $("#newGroupForm").addClass("has-error");
            return;
        }
        if (editgid == 0) {
            __request("api.v1.user.addgroup", {name: name}, function(res) {
                console.debug(res.data);
                grouplist.groups.push(res.data);
            });
        } else {
            __request("api.v1.user.editgroup", {gid: editgid, name: name}, function(res) {
                console.debug(res.data);
                for (var k in grouplist.groups) {
                    if (grouplist.groups[k].id == res.data.id) {
                        grouplist.groups[k].name = res.data.name;
                    }
                }
            });
        }
        $("#newGroupModal").modal("hide");
    });

    $("#newGroupName").change(function() {
        $("#newGroupForm").removeClass("has-error");
    });

    $("#newGroup").click(function() {
        editgid = 0;
        grouplist.modallabel = "新建用户组";
        grouplist.editgroupname = "";
        $("#newGroupModal").modal();
    });

});

