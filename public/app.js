var showedRules = {};

var rulesManager = {

    rules:{},

    addRule:function (ruleId,rule) {
        this.rules[ruleId] = rule;
    },
    getRule:function (ruleId) {
        return this.rules[ruleId];
    },

    getRules:function () {
        return this.rules;
    }
};

function getRulesValue(){
    let values = {};
    for(let ruleId in showedRules){

        let ruleStore = showedRules[ruleId];
        let rule = rulesManager.getRule(ruleId);

        let ruleValue = rule.getData(ruleStore);

        if(ruleValue === null){
            continue;
        }

        values[ruleId] = rule.getData(ruleStore);
    }
    return values;
}

function updateDataCollection(DataCollection,data) {
    DataCollection.clearAll();

    for(let key in data){
        let el = data[key];
        DataCollection.add(el);
    }
}



function resizeEditWindow(parent) {
    var $$scroll = $$('window_'+parent+'_scrollview');


    let maxHeight = window.innerHeight - 100;

    let bodyHeight = $$scroll.getBody().$height;

    if(bodyHeight>maxHeight){
        bodyHeight = maxHeight;
    }

    $$scroll.define('minHeight',bodyHeight+10);
    $$scroll.resize();

}

function formatDate(date) {
    let day = date.getDate();
    if(day<10){
        day = "0"+day;
    }
    let month = date.getMonth() + 1;
    if(month < 10){
        month = "0"+month;
    }
    let year = date.getFullYear();

    let hours = date.getHours();
    if(hours < 10){
        hours = "0"+hours;
    }

    let minutes = date.getMinutes();
    if(minutes < 10){
        minutes = "0"+minutes;
    }

    let seconds = date.getSeconds();
    if(seconds < 10){
        seconds = "0"+seconds;
    }

    return `${year}-${month}-${day} ${hours}:${minutes}:${seconds}`;
}




function addRule(parent,ruleId,ruleValues) {


    let rule = rulesManager.getRule(ruleId);

    let ruleInnerId = addRuleOwner(parent,ruleId,rule.getCaption());

    rule.addRuleView(ruleInnerId,ruleValues);
    //
    // discountRulesStores[ruleId] = ruleStore;
    showedRules[ruleId] = true;
    updateAvailableRules(parent);
    resizeEditWindow(parent);

}

function addRuleOwner(parent,ruleId,caption){
    let viewId = "rule_"+ruleId;
    let viewInnerId = "rule_inner_"+ruleId;

    let object = {
        view: "fieldset",
        id:viewId,
        label: caption ,
        body: {
            rows: [
                {
                    cols: [
                        {
                            id:viewInnerId,
                            rows:[]
                        },
                        {
                            align: "bottom,middle",
                            body: {
                                view: "icon",
                                icon: "wxi-trash",
                                click:function () {

                                    $$(parent+"_rules").removeView(viewId);
                                     delete showedRules[ruleId];
                                     updateAvailableRules(parent);
                                     resizeEditWindow(parent);
                                }

                            },
                        },
                    ]
                }
            ]
        }
    };
    $$(parent+"_rules").addView(object);
    return viewInnerId;
}




function updateAvailableRules(parent) {



    let rules = rulesManager.getRules();
    let options = [];

    for(let ruleId in rules){

        if(showedRules[ruleId]){
            continue;
        }

        let rule = rulesManager.getRule(ruleId);

        options.push({
            id:ruleId,
            value:rule.getCaption()
        })

    }


    $$(parent+"_rules_select").define("options", options);
    $$(parent+"_rules_select").refresh();

}


function showEditWindow(item) {
    webix.ui({
        id: "window_edit",
        view: "window",
        position: "center",
        modal: true,
        width: 800,

        head: {
            view: "toolbar",
            margin: 5, cols: [
                {
                    view: "label", label: lang['window_edit_caption']
                },
                {
                    view: "icon", icon: "wxi-close", tooltip: lang['close'],
                    click: function () {
                        $$('window_edit').close();
                    },

                }
            ]
        },


        body: {
            view: "scrollview",
            id: "window_edit_scrollview",
            scroll: 'auto',

            body: {
                rows: [
                    {
                        view: "form",
                        id:"edit_form",

                        elements: [

                            {
                                cols: [
                                    {
                                        labelPosition: "top",
                                        label: lang['input_coupon_title'],
                                        view: "text",
                                        name: "title"
                                    },
                                    {
                                        labelWidth: 0,
                                        labelPosition: "top",
                                        label: lang['input_coupon_active'],
                                        labelRight: lang['input_coupon_active_yes'],
                                        view: "checkbox",
                                        name: "active"
                                    }
                                ]
                            },
                            {
                                cols:[
                                    {
                                        labelPosition: "top",
                                        label: lang['input_coupon_coupon'],
                                        view: "text",
                                        name: "coupon"
                                    },
                                    {
                                        labelPosition: "top",
                                        label: lang['input_coupons_limit'],
                                        view: "counter",
                                        name: "limit"
                                    },
                                ]
                            },
                            {
                                cols: [
                                    {
                                        view: "text",
                                        name: "discount_value",
                                        label: lang['discount_value'],
                                        labelPosition: "top"
                                    },
                                    {
                                        view: "select",
                                        name: "discount_type",
                                        label: lang['discount_type'],
                                        labelPosition: "top",
                                        options: [
                                            {id: "amount", value: lang['discount_type_amount']},
                                            {id: "percent", value: lang['discount_type_percent']},
                                        ]
                                    }
                                ]
                            },
                        ]
                    },

                    {
                        template: lang['rules'], type: "section"
                    },
                    {
                        id: "edit_rules",  rows: [],
                    },
                    {
                        cols: [
                            {

                            },
                            {
                                id: "edit_rules_select",
                                view: "select",
                                options:[],
                            },
                            {
                                id: "edit_rules_add",
                                view: "button",
                                label:lang['add'],
                                click:function () {
                                    addRule("edit",$$("edit_rules_select").getValue(), {});
                                }
                            },
                            {}
                        ],
                    },
                    {
                        view:"button",
                        label:lang['save'],
                        click:function () {
                            let values = $$('edit_form').getValues();
                            values.rules = getRulesValue();



                            $$('coupons').updateItem(item['id'],values);
                            $$('window_edit').close();
                        }
                    }
                ]
            }
        }
    });
    $$('window_edit').show();



    $$('edit_form').setValues(item);
    showedRules = {};

    for(let ruleId in item.rules){
        let ruleValue = item.rules[ruleId];

        showedRules[ruleId] = true;

        addRule("edit",ruleId, ruleValue);
    }



    updateAvailableRules("edit");
    resizeEditWindow("edit");
}


function showAutogenerateWindow() {
    webix.ui({
        id: "window_autogenerate",
        view: "window",
        position: "center",
        modal: true,
        width: 800,

        head: {
            view: "toolbar",
            margin: 5, cols: [
                {
                    view: "label", label: lang['window_autogenerate_caption']
                },
                {
                    view: "icon", icon: "wxi-close", tooltip: lang['close'],
                    click: function () {
                        $$('window_autogenerate').close();
                    },

                }
            ]
        },


        body: {
            view: "scrollview",
            id: "window_autogenerate_scrollview",
            scroll: 'auto',

            body: {
                rows: [
                    {
                        view: "form",
                        id:"autogenerate_form",

                        elements: [
                            {
                                cols: [
                                    {
                                        labelPosition: "top",
                                        label: lang['input_coupons_count'],
                                        view: "counter",
                                        name: "coupons_count",
                                        value:5,
                                    },
                                    {
                                        labelPosition: "top",
                                        label: lang['input_coupons_symbol_counts'],
                                        view: "counter",
                                        name: "symbol_counts",
                                        value:6,
                                    }
                                ]
                            },

                            {
                                cols: [
                                    {
                                        labelPosition: "top",
                                        label: lang['input_coupon_title'],
                                        view: "text",
                                        name: "title"
                                    },
                                    {
                                        labelWidth: 0,
                                        labelPosition: "top",
                                        label: lang['input_coupon_active'],
                                        labelRight: lang['input_coupon_active_yes'],
                                        view: "checkbox",
                                        name: "active",
                                        value:1,
                                    }
                                ]
                            },
                            {
                                labelPosition: "top",
                                label: lang['input_coupons_limit'],
                                view: "counter",
                                name: "limit",
                                value:1,
                            },
                            {
                                cols: [
                                    {
                                        view: "text",
                                        name: "discount_value",
                                        label: lang['discount_value'],
                                        labelPosition: "top"
                                    },
                                    {
                                        view: "select",
                                        name: "discount_type",
                                        label: lang['discount_type'],
                                        labelPosition: "top",
                                        options: [
                                            {id: "amount", value: lang['discount_type_amount']},
                                            {id: "percent", value: lang['discount_type_percent']},
                                        ],
                                        value:"amount",
                                    }
                                ]
                            },
                        ]
                    },

                    {
                        template: lang['rules'], type: "section"
                    },
                    {
                        id: "autogenerate_rules",  rows: [],
                    },
                    {
                        cols: [
                            {

                            },
                            {
                                id: "autogenerate_rules_select",
                                view: "select",
                                options:[],
                            },
                            {
                                id: "autogenerate_rules_add",
                                view: "button",
                                label:lang['add'],
                                click:function () {
                                    addRule("autogenerate",$$("autogenerate_rules_select").getValue(), {});
                                }
                            },
                            {}
                        ],
                    },
                    {
                        view:"button",
                        label:lang['generate'],
                        click:function () {
                            let values = $$('autogenerate_form').getValues();
                            values.rules = getRulesValue();

                            $.post(appConfig.moduleUrl+"action=coupons-generate",values,function () {

                                $$('coupons').clearAll()
                                $$('coupons').load(appConfig.moduleUrl+"action=coupons-load")

                                $$('window_autogenerate').close();

                            });
                        }
                    }
                ]
            }
        }
    });
    $$('window_autogenerate').show();
    showedRules = {};

    updateAvailableRules("autogenerate");
    resizeEditWindow("autogenerate");
}


webix.ui({
    container: "app",
    autoheight: true,
    rows: [
        {
            view: "template", type: "header", template: lang.caption
        },

        {
            view: "toolbar", id: "mybar", elements: [
                {
                    view: "button",
                    type: "icon",
                    icon: "fas fa-list",
                    label: lang.toolbar_autogenerate,
                    autowidth: true,

                    click: showAutogenerateWindow
                },
                {
                    view: "button",
                    type: "icon",
                    icon: "fas fa-edit",
                    label: lang.toolbar_edit,
                    autowidth: true,

                    click: function () {

                        var selectedId = $$('coupons').getSelectedId();

                        if(selectedId === undefined){
                            webix.alert(lang['selected_anything']);
                            return;
                        }

                        showEditWindow($$('coupons').getItem(selectedId));

                    }
                },
                {
                    view: "button",
                    type: "icon",
                    icon: "fas fa-plus-circle",
                    label: lang.toolbar_add,
                    autowidth: true,

                    click: function () {
                        $$("coupons").add({},0)
                    }
                },

                {
                    view: "button",
                    type: "icon",
                    icon: "fas fa-minus-circle",
                    label: lang.toolbar_remove,
                    autowidth: true,

                    click: function () {

                        var selectedId = $$('coupons').getSelectedId();

                        if(selectedId === undefined){
                            webix.alert(lang['selected_anything']);
                            return;
                        }
                        $$('coupons').remove(selectedId)

                    }
                }
            ]
        },
        {

            id: "coupons",
            view: "datatable",
            autoheight: true,
            select: true,

            fixedRowHeight:false,
            on:{
                onAfterLoad:function () {
                    $$("coupons").adjustRowHeight()
                },
                onDataUpdate:function () {
                    $$("coupons").adjustRowHeight()
                }
            },


            columns: [
                {
                    id: "id", header: ["#",{content:"serverFilter"}], width:50, sort:"server",
                },
                {
                    id: "title", header: [lang['col_title'],{content:"serverFilter"}], fillspace:3, sort:"server",
                },
                {
                    id: "coupon", header: [lang['col_coupon'],{content:"serverFilter"}], fillspace:3, sort:"server",
                },

                {
                    id: "rules", css:"rules", header: lang['col_rules'], fillspace:4, template:function (item,$view,rules) {

                        let output = "";
                        for(let ruleId in rules){
                            let ruleValues = rules[ruleId];
                            let rule = rulesManager.getRule(ruleId);
                            output += "<p style='margin: 0;white-space: nowrap;'>"+rule.getDescriptionForTable(ruleValues)+"</p>";
                        }

                        return output;
                    },
                },
                {
                    id: "limit", header: [lang['col_limit'],{content:"serverFilter"}], fillspace:3, sort:"server",
                },
                {
                    id: "used", header: [lang['col_used']], fillspace:3, sort:"server",
                },

                {
                    id: "discount_value", header: [lang['col_discount_value'],{content:"serverFilter"}], fillspace:2, sort:"server",
                },
                {
                    id: "discount_type", header: [lang['col_discount_type'],{content:"serverSelectFilter"}], fillspace:2, sort:"server",
                    options:[
                        { id:"amount", value:lang['discount_type_amount'] },
                        { id:"percent", value:lang['discount_type_percent'] },
                    ]
                },
                {
                    id: "created_at", header: [lang['col_created']], fillspace:3, sort:"server",
                },
                {
                    id: "active", header: [lang['col_active'],{content:"serverFilter"}], width:100, sort:"server",
                    template:"{common.checkbox()}"
                },
            ],
            url: appConfig.moduleUrl+"action=coupons-load",
            save:{
                insert: appConfig.moduleUrl+"action=coupons-add",
                update: function(id, operation, update){
                    return webix.ajax().post(
                        appConfig.moduleUrl+"action=coupons-update",
                        update
                    ).then( (response)=>{
                        if(response.json().error){
                            webix.alert({
                                title: "Data was not saved!",
                                text: response.json().error,
                                type:"alert-error"
                            });
                            this.updateItem(id, response.json().old_data);
                        }
                    });
                },
                delete: appConfig.moduleUrl+"action=coupons-remove",
            },


            pager:{
                template:'{common.first()} {common.pages()}  {common.last()}',
                container: 'pager',
                group:6,
                size:10,
            },

        }

    ]
})
