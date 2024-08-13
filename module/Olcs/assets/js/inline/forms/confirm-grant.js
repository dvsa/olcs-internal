$(function () {
    "use strict";
    OLCS.cascadeForm({
        form: "#Grant",
        rulesets: {
            "inspection-request-grant-details": {
                "*": function () {
                    return OLCS.formHelper.isChecked("inspection-request-confirm", "createInspectionRequest");
                }
            }
        }
    });
});
