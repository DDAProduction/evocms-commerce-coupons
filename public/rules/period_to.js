let periodToRule = {

    getTypes:function(){
        return ['product','cart'];
    },

    getCaption:function () {
        return lang['rule_period_to_cation'];
    },
    getDescriptionForTable:function (value) {

        return '<b>'+this.getCaption()+': </b>'+value;

    },

    addRuleView:function (ruleInnerId,rule) {

        let viewId = "period_to_view";

        let inputId = "period_to_input";


        let obj = {
            id:viewId,
            rows:[
                {
                    name:"periodTo",
                    view:"datepicker",
                    format:"%d.%m.%Y %H:%i:%s",
                    timepicker: true,
                    id: inputId,
                },

            ]
        };
        $$(ruleInnerId).addView(obj);
        if(Object.keys(rule).length){
            $$(inputId).setValue(new Date(rule));
        }
        else{
            $$(inputId).setValue(new Date())
        }


        return true;
    },
    getData(){
        let value = $$("period_to_input").getValue();
        if(value){
            return formatDate(value);
        }
        return null;
    }

};


rulesManager.addRule('periodTo',periodToRule);