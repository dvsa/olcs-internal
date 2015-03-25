$(function() {
  "use strict";

  OLCS.cascadeForm({
    cascade: false,
    rulesets: {
      "form-actions": function() {
        return OLCS.formHelper.isChecked("licence-decision-affect-immediate", "immediateAffect");
      },
      "licence-decision-curtail": function() {
        return !OLCS.formHelper.isChecked("licence-decision-affect-immediate", "immediateAffect");
      }
    }
  });
});
