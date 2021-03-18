let usersStore = new webix.DataCollection({});


webix.ui({
    view: "window",
    id: "win_users_tree",
    move: true,
    width: 500,
    height: 500,
    position: "center",
    modal: true,
    head: {
        view: "toolbar", margin: 5, cols: [
            {
                view: "label", label: lang['rule_users_model_caption']
            },
            {
                view: "icon",
                icon: "wxi-close",
                click: "$$('win_users_tree').hide();",
                tooltip: lang['close_without_save']
            }
        ]
    },
    body: {
        rows: [
            {
                cols: [
                    {
                        view: "toolbar", cols: [
                            {
                                view: "text",
                                css: "search_users_tree_field",
                                id: "search_users_tree_field",
                                placeholder: lang['rule_users_model_filter_by_name'],

                            },
                            {
                                view: "button",
                                type: "icon",
                                icon: "wxi-filter",
                                label: "",
                                tooltip: lang['rule_users_model_search'],
                                autowidth: true,
                                click: function () {

                                    $$("users_tree_selected").selectAll();


                                    var ids = $$("users_tree_selected").getSelectedId();
                                    var search = $$("search_users_tree_field").getValue();

                                    $$("users_tree").clearAll();
                                    $$("users_tree").load(appConfig.moduleUrl + "&action=rule-users-search&" + "&checked=" + ids + "&search=" + search);


                                }
                            }
                        ]
                    },
                    {
                        view: "toolbar", cols: [
                            {
                                view: "template",
                                align: "middle,middle",
                                template: lang['rule_users_users_with_sale'],
                                height: 10
                            },
                        ]
                    },
                ]
            },
            {
                cols: [
                    {
                        view: "list",
                        label: lang['rule_users_user_list'],
                        id: "users_tree",
                        autowidth: true,
                        maxHeight: 400,
                        template: "#title#",
                        drag: "move"
                    },
                    {
                        view: "list",
                        label: lang['rule_users_selected_user'],
                        id: "users_tree_selected",
                        template: "#title#",
                        autowidth: true,
                        maxHeight: 400,
                        drag: "move"
                    },
                ]
            },
            {
                cols: [
                    {
                        view: "button",
                        value: lang['save'],
                        type: "form",
                        click: function () {
                            updateDataCollection(usersStore, $$('users_tree_selected').serialize());
                            $$("win_users_tree").hide()
                        }
                    },
                    {
                        view: "button",
                        value: lang['close'],
                        click: function () {
                            $$("win_users_tree").hide()
                        }
                    }
                ]
            },

        ]
    },
})

let usersRule = {

    getTypes: function () {
        return ['product', 'cart'];
    },


    getCaption: function () {
        return lang['rule_users_rule_caption']
    },

    getDescriptionForTable: function (users) {
        let output = '<b>' + this.getCaption() + ': </b>';
        let names = [];
        for (let i = 0; i < users.length; i++) {
            names.push(users[i].title);
        }
        output += names.join(', ');
        return output;
    },

    addRuleView: function (ruleInnerId, rule) {

        let viewId = ruleInnerId + "_users_view";
        let listId = ruleInnerId + "_users_list";

        updateDataCollection(usersStore, rule);

        let obj = {
            id: viewId,
            rows: [
                {
                    view: "list",
                    id: listId,
                    template: "#title#",

                    autoheight: true,
                    maxHeight: 200,
                },
                {
                    view: "button",
                    label: lang['rule_users_show_user_list'],

                    on: {
                        onItemClick: function () {
                            $$('win_users_tree').show();

                            $$('users_tree').clearAll();
                            $$('users_tree_selected').clearAll();

                            let items = usersStore.serialize();
                            for (let key in items) {
                                let item = items[key];


                                $$('users_tree_selected').add(item);

                            }
                        }
                    }

                },
            ]
        };

        $$(ruleInnerId).addView(obj);
        $$(listId).sync(usersStore);
    },
    getData() {
        return usersStore.serialize();
    }

};


rulesManager.addRule('users', usersRule);

