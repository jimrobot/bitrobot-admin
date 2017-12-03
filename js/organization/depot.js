$(document).ready(function() {
    var DEPOT_CATEGORY_INDEX = 6;
    var depot = new Vue({
        el: "#page-content",
        data: {
            category: null,
            categories: null,
            depots: null,
            users: null,
            currentNode: null,
            modifyid: 0,
            modal: {
                title: '',
                name: '',
                address: '',
                manager: 0,
                category: DEPOT_CATEGORY_INDEX,
                comments: '',
            }
        },
        methods: {
            selectAll: function() {
                select_node(DEPOT_CATEGORY_INDEX);
            },
            ensure: function(event) {
                if (this.modal.name.length == 0) {
                    alert("请输入名称。");
                    return;
                }
                if (this.modifyid == 0) {
                    __request("api.v1.depot.add", {
                        name: this.modal.name,
                        category: this.modal.category,
                        address: this.modal.address,
                        manager: this.modal.manager,
                        comments: this.modal.comments,
                    }, reload_data);
                } else {
                    __request("api.v1.depot.modify", {
                        id: this.modifyid,
                        name: this.modal.name,
                        category: this.modal.category,
                        address: this.modal.address,
                        manager: this.modal.manager,
                        comments: this.modal.comments,
                    }, reload_data);
                }
                $("#theModal").modal("hide");
            },
            edit: function(event) {
                var vk = $(event.currentTarget).attr("dk");
                var vid = this.depots[vk].id;
                this.modifyid = vid;
                this.modal.title= "修改产品";
                this.modal.name = this.depots[vk].name;
                this.modal.category = this.depots[vk].category.id;
                this.modal.address = this.depots[vk].address;
                this.modal.manager = this.depots[vk].manager.id;
                this.modal.comments = this.depots[vk].comments;

                // console.debug(vid);
                $("#theModal").modal();
            },
            remove: function(event) {
                $("#theModal").modal("hide");
                $("#theDeleteModal").modal();
            },
            doRemove: function(event) {
                __request("api.v1.depot.remove", {id: this.modifyid}, reload_data);
                $("#theModal").modal("hide");
                $("#theDeleteModal").modal("hide");
            },
            addNew: function(event) {
                this.modifyid = 0;
                this.modal.title= "新增产品";
                this.modal.name = '';
                this.modal.category = DEPOT_CATEGORY_INDEX;
                this.modal.address = '';
                this.modal.manager = 0;
                this.modal.comments = '';
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
        var currentNode = find_node(depot.category, cid);
        enum_node(depot.category, function(node) {
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
            // DEPOT_CATEGORY_INDEX
            for (var k in depot.depots) {
                if (currentNode.id == depot.depots[k].category.id || (cid == DEPOT_CATEGORY_INDEX && depot.depots[k].category.id == 0)) {
                    depot.depots[k].show = true;
                    continue;
                }
                var flag = false;
                for (var k1 in currentNode.descendants) {
                    if (currentNode.descendants[k1] == depot.depots[k].category.id) {
                        depot.depots[k].show = true;
                        flag = true;
                        break;
                    }
                }
                if (!flag) {
                    depot.depots[k].show = false;
                }
            }
        }
        depot.currentNode = currentNode;
    }

    var reload_data = function() {
        __request("api.v1.depot.list", {}, function(res) {
            console.debug(res);
            for (var k in res.data) {
                res.data[k].show = true;
            }
            depot.depots = res.data;
            if (depot.currentNode != null) {
                select_node(depot.currentNode.id);
            }
        });
    }

    __request("api.v1.category.list", {}, function(res) {
        console.debug(res);
        enum_node(res.data, function(node) {
            node.selected = false;
        });
        depot.category = res.data.children[DEPOT_CATEGORY_INDEX - 1];

        categories = []; // [{name: '所有分类', id: 3}];
        enum_node(res.data.children[DEPOT_CATEGORY_INDEX - 1], function(node) {
            categories.push(node);
        });
        depot.categories = categories;

        reload_data();
    });
    __request("api.v1.user.getusers", {}, function(res) {
        depot.users = res.data;
    });

});


