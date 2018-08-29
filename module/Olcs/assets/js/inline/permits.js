OLCS.ready(function () {
    "use strict";

    var form = "[name=permits-home]";

    OLCS.formHandler({
        form: form,
        hideSubmit: true,
        container: ".js-body",
        filter: ".js-body"
    });

    if (!$(".js-rows").length) $(".filters").hide();

    // Add event handler for Permits Form Back button click. Prevent default on Cancel, allow to continue on OK.
    $("#back").click(function (e) {
        if (!confirm("Going back will lose any unsaved changes. Are you sure? ")) {
            e.preventDefault();
        }
    });

    // Add event handler for Permits Form Back button click. Prevent default on Cancel, allow to continue on OK.
    $("#withdrawPermitApplication").click(function (e) {
        if (!confirm("This will withdraw the application and any fees paid will not be refunded. Are you sure?")) {
            e.preventDefault();
        }
    });

    $("#cancelPermitApplication").click(function (e) {
        if (!confirm("This will cancel the application. Are you sure?")) {
            e.preventDefault();
        }
    });

    if ($.inArray($(".permitApplicationStatus").val(), ["ecmt_permit_uc", "ecmt_permit_awaiting", "ecmt_permit_issued"]) !== -1) {
        $("#withdrawPermitApplication").removeClass("visually-hidden");
    }

    if ($.inArray($(".permitApplicationStatus").val(), ["ecmt_permit_nys"]) !== -1) {
        $("#cancelPermitApplication").removeClass("visually-hidden");
    }

    $(".permitApplicationStatus").click(function (e) {
        if (!confirm("Going back will lose any unsaved changes. Are you sure?")) {
            e.preventDefault();
        }
    });
});
