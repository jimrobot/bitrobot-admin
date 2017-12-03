$(document).ready(function() {
    var category = new Vue({
        el: "#page-wrapper",
        data: {
            root: null,
            currentNode: null,
            modifyid: 0,
            viewpath: [{title: "所有类型", node: 0}],
            modal: {
                title: '',
                name: '',
                origname: '',
            }
        },
        methods: {
            ensure: function(event) {
                if (this.modal.name.length == 0) {
                    alert("请输入类型名称。");
                    return;
                }
                if (this.modifyid == 0) {
                    __request("api.v1.category.add", {name: this.modal.name, pid: this.currentNode.id}, reload_data);
                } else {
                    __request("api.v1.category.modify", {id: this.modifyid, name: this.modal.name}, reload_data);
                }
                $("#theModal").modal("hide");
            },
            edit: function(event) {
                var cid = $(event.currentTarget).attr("cid");
                // console.debug(cid);
                var node = find_node(this.root, cid);
                this.modifyid = cid;
                this.modal.title= "修改类型";
                this.modal.name = node.name;
                this.modal.origname = node.name;
                $("#theModal").modal();
            },
            remove: function(event) {
                $("#theModal").modal("hide");
                $("#theDeleteModal").modal();
            },
            doRemove: function(event) {
                __request("api.v1.category.remove", {id: this.modifyid}, reload_data);
                $("#theModal").modal("hide");
                $("#theDeleteModal").modal("hide");
            },
            addNew: function(event) {
                this.modal.title= "新建类型";
                this.modal.name = '';
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
        var currentNode = find_node(category.root, cid);
        enum_node(category.root, function(node) {
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
            category.currentNode = currentNode;
        }

        category.viewpath = [{title: "所有类型", node: 0}];
        var index = 1;
        enum_node(category.root, function(node) {
            if (node.selected) {
                category.viewpath[index] = {title: node.name, node: node.id};
                index++;
            }
        });
    }

    var reload_data = function() {
        __request("api.v1.category.list", {}, function(res) {
            console.debug(res);

            enum_node(res.data, function(node) {
                node.selected = false;
            });

            category.root = res.data;
            if (category.currentNode == null) {
                category.currentNode = res.data;
            } else {
                var id = category.currentNode.id;
                select_node(id);
            }
            // depot.depots = res.data;
        });
    }
    reload_data();

});

