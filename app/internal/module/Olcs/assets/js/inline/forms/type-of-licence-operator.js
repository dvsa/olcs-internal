OLCS.ready(function() {

  // jshint newcap:false

  "use strict";

  var F = OLCS.formHelper;

  function setupCascade() {

    var operatorType = F("type-of-licence", "operator-type");
    var trafficArea  = F("details", "trafficArea");

    OLCS.cascadeForm({
      form: "form",
      cascade: false,
      rulesets: {
        // operator type only shown when location has been completed and value is great britain
        "operator-type": function() {
          return trafficArea.val() !== "N";
        },

        // licence type is nested; the first rule defines when to show the fieldset
        // (in this case if the licence is NI or the user has chosen an operator type)
        "licence-type": {
          "*": function() {
            return (
                trafficArea.val() === "N" || operatorType.filter(":checked").length
            );
          },

          // this rule relates to an element within the fieldset
          "licence-type=ltyp_sr": function() {
            return operatorType.filter(":checked").val() === "lcat_psv";
          }
        }
      },
    });
  }

  setupCascade();

  OLCS.eventEmitter.on("render", function() {
    setupCascade();
  });

});
