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
            // update: function(event) {
            //     var uk = $(event.currentTarget).attr("uk");
            //     var gk = $(event.currentTarget).attr("gk");
            //     var uid = this.users[uk].id;
            //     var gid = this.users[uk].groups[gk].id;
            //     var join = this.users[uk].groups[gk].join ? 1 : 0;
            //     console.debug("uid = " + uid);
            //     console.debug("gid = " + gid);
            //     console.debug("join = " + join);
            //     __request("api.v1.user.setusergroup", {uid: uid, gid: gid, join: join}, function(res) {
            //         if (res.data.code != 0) {
            //             document.location.reload();
            //         }
            //     }, function(res) {
            //         document.location.reload();
            //     });
            // },
            removeFile: function(event) {
                var fid = this.editfid;
                console.debug(fid);
                if (fid == 0) {
                    return;
                }
                __request("api.v1.files.remove", {fid: fid}, function(res) {
                    if (res.data.code == 0) {
                        reload_data();
                    }
                });
                $("#newFileModal").modal('hide');
            },
            editFile: function(event) {
                var fk = $(event.currentTarget).attr("fk");
                console.debug(fk);
                console.debug(this.files);
                var fid = this.files[fk].id;
                this.modal.label = "修改文件信息";
                this.modal.filename = this.files[fk].filename;
                this.modal.title = this.files[fk].title;
                this.modal.comments = this.files[fk].comments;
                this.editfid = fid;
                $("#newFileModal").modal();
            }
        }
    });
    var reload_data = function() {
        __request("api.v1.files.list", {}, function(res) {
            console.debug(res);
            filelist.files = res.data;
        });
    };
    reload_data();

    $("#addNewFile").click(function() {
        var filename = filelist.modal.filename.trim();
        var title = filelist.modal.title.trim();
        var comments = filelist.modal.comments.trim();
        if (filename.length == 0 || title.length == 0) {
            return;
        }

        if (filelist.editfid == 0) {
            var file = $("#newFileUpload").get(0).files[0];
            if (typeof(file) == "undefined") {
                alert("选择要上传的文件!");
                return;
            }

            __file_upload("api.v1.files.upload", $("#newFileUpload").get(0), {filename: filename, title: title, comments: comments}, function(res) {
                console.debug(res);
                alert("上传成功！");
                reload_data();
            });
        } else {
            __request("api.v1.files.edit", {
                fid: filelist.editfid,
                filename: filename,
                title: title,
                comments: comments
            }, function(res) {
                reload_data();
                // console.debug(res.data);
                // for (var k in filelist.users) {
                //     if (filelist.users[k].id == res.data.id) {
                //         for (var k1 in res.data) {
                //             filelist.users[k][k1] = res.data[k1];
                //         }
                //     }
                // }
            });
        }
        $("#newFileModal").modal("hide");
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

