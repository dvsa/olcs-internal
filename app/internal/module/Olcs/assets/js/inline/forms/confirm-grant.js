$(function() {
  "use strict";

  $('#inspectionRequestGrantDetails').hide();
  OLCS.cascadeForm({
    form: "#Grant",
    rulesets: {
      "inspection-request-details": {
        "*": function() {
          return OLCS.formHelper.isChecked("inspection-request-confirm", "createInspectionRequest");
        }
      }
    }
  });

});
