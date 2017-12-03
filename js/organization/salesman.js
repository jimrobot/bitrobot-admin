$(document).ready(function() {
    var SALESMAN_CATEGORY_INDEX = 5;
    var page = new Vue({
        el: "#page-content",
        data: {
            category: null,
            categories: null,
            salesman: null,
            currentNode: null,
            modifyid: 0,
            modal: {
                title: '',
                name: '',
                telephone: '',
                category: SALESMAN_CATEGORY_INDEX,
                gender: '',
                comments: '',
            }
        },
        methods: {
            selectAll: function() {
                select_node(SALESMAN_CATEGORY_INDEX);
            },
            ensure: function(event) {
                if (this.modal.name.length == 0) {
                    alert("请输入名称。");
                    return;
                }
                if (this.modifyid == 0) {
                    __request("api.v1.salesman.add", {
                        name: this.modal.name,
                        telephone: this.modal.telephone,
                        gender: this.modal.gender,
                        category: this.modal.category,
                        comments: this.modal.comments,
                    }, reload_data);
                } else {
                    __request("api.v1.salesman.modify", {
                        id: this.modifyid,
                        name: this.modal.name,
                        telephone: this.modal.telephone,
                        gender: this.modal.gender,
                        category: this.modal.category,
                        comments: this.modal.comments,
                    }, reload_data);
                }
                $("#theModal").modal("hide");
            },
            edit: function(event) {
                if ($(event.target).is("a")) {
                    return;
                }
                var vk = $(event.currentTarget).attr("sk");
                var vid = this.salesman[vk].id;
                this.modifyid = vid;
                this.modal.title= "修改供应商";
                this.modal.name = this.salesman[vk].name;
                this.modal.telephone = this.salesman[vk].telephone;
                this.modal.gender = this.salesman[vk].gender;
                this.modal.category = this.salesman[vk].category.id;
                this.modal.comments = this.salesman[vk].comments;
                $("#theModal").modal();
            },
            remove: function(event) {
                $("#theModal").modal("hide");
                $("#theDeleteModal").modal();
            },
            doRemove: function(event) {
                __request("api.v1.salesman.remove", {id: this.modifyid}, reload_data);
                $("#theModal").modal("hide");
                $("#theDeleteModal").modal("hide");
            },
            addNew: function(event) {
                this.modal.title= "新增供应商";
                this.modal.name = '';
                this.modal.category = SALESMAN_CATEGORY_INDEX;
                this.modal.telephone = '';
                this.modal.gender= '';
                this.modal.comments = '';
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
        var currentNode = find_node(page.category, cid);
        enum_node(page.category, function(node) {
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
            // SALESMAN_CATEGORY_INDEX
            for (var k in page.salesman) {
                if (currentNode.id == page.salesman[k].category.id || (cid == SALESMAN_CATEGORY_INDEX && page.salesman[k].category.id == 0)) {
                    page.salesman[k].show = true;
                    continue;
                }
                var flag = false;
                for (var k1 in currentNode.descendants) {
                    if (currentNode.descendants[k1] == page.salesman[k].category.id) {
                        page.salesman[k].show = true;
                        flag = true;
                        break;
                    }
                }
                if (!flag) {
                    page.salesman[k].show = false;
                }
            }
        }
        page.currentNode = currentNode;
    }

    var reload_data = function() {
        __request("api.v1.salesman.list", {}, function(res) {
            console.debug(res);
            for (var k in res.data) {
                res.data[k].show = true;
            }
            page.salesman = res.data;
            if (page.currentNode != null) {
                select_node(page.currentNode.id);
            }
        });
    }

    __request("api.v1.category.list", {}, function(res) {
        console.debug(res);
        enum_node(res.data, function(node) {
            node.selected = false;
        });
        // res.data.children[SALESMAN_CATEGORY_INDEX - 1].name = "供应商";
        page.category = res.data.children[SALESMAN_CATEGORY_INDEX - 1];

        categories = []; // [{name: '所有分类', id: 3}];
        enum_node(res.data.children[SALESMAN_CATEGORY_INDEX - 1], function(node) {
            categories.push(node);
        });
        page.categories = categories;

        reload_data();
    });
});


