let periodFromRule = {

    getCaption: function () {
        return lang['rule_period_from_cation'];
    },
    getDescriptionForTable: function (value) {
        return '<b>' + this.getCaption() + ': </b>' + value;
    },

    addRuleView: function (ruleInnerId, rule) {

        let viewId = "period_from_view";
        let inputId = "period_from_input";

        let obj = {
            id: viewId,
            rows: [
                {
                    view: "datepicker",
                    format: "%d.%m.%Y %H:%i:%s",
                    id: inputId,
                    name: "period_from",
                    timepicker: true,

                },
            ]
        };
        $$(ruleInnerId).addView(obj);

        if (Object.keys(rule).length) {
            $$(inputId).setValue(new Date(rule));
        } else {
            $$(inputId).setValue(new Date())
        }

        return true;
    },
    getData() {
        let value = $$("period_from_input").getValue();
        if (value) {
            return formatDate($$("period_from_input").getValue());
        }
        return null;
    }
};

rulesManager.addRule('periodFrom', periodFromRule);

