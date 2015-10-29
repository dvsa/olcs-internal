OLCS.ready(function() {
  "use strict";

  var form = "form[name=fee]";
  var path = document.location.pathname;
  var urlPrefix = path.substring(0, path.lastIndexOf('/fees'));

  // create a hidden element to hold the combined DateSelect field values and
  // bind change events to populate it. We can then use this to hook in to the
  // cascadeInput behaviour
  var createdDateSelector = $(form).find("[name^='fee-details[createdDate]']");
  var jsDate = $("<input>").attr({type: "hidden", id: "js-created-date", }).appendTo(form);
  $(createdDateSelector).on("change", function(e) {
    var dateParts = [];
    $.each(createdDateSelector, function(i, select) {
        dateParts.push(select.value);
    });
    // elements are in d/m/Y order, we want Y-m-d
    jsDate.val(dateParts.reverse().join('-'));
    jsDate.change(); // trigger change event on hidden field for cascadeInput
  });

  // populate Amount field when FeeType is selected
  OLCS.cascadeInput({
    source: form + " #feeType",
    dest: form + " #amount",
    url: urlPrefix + "/fees/ajax/fee-type",
    clearWhenEmpty: true
  });

  // refresh FeeType list with appropriate effectiveFrom date according to
  // the fee's created date
  OLCS.cascadeInput({
    source: form + " #js-created-date",
    dest: form + " #feeType",
    url: urlPrefix + "/fees/ajax/fee-type-list",
    emptyLabel: 'Please select'
  });

});
