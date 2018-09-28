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
    $("#backToPermitList").click(function (e) {
        if (!confirm("Going back will lose any unsaved changes. Are you sure? ")) {
            e.preventDefault();
        }
    });

    if ($("#canBeWithdrawn").val()) {
        $("#withdrawPermitApplication").removeClass("visually-hidden");
    }

    if ($("#canBeSubmitted").val()) {
        $("#submitPermitApplication").removeClass("visually-hidden");
    }

    if ($("#canBeCancelled").val()) {
        $("#cancelPermitApplication").removeClass("visually-hidden");
    }

    if ($.inArray($(".permitApplicationStatus").val(), ["permit_app_uc", "permit_app_awaiting", "permit_app_issued"]) !== -1) {
        $("#withdrawPermitApplication").removeClass("visually-hidden");
    }

    if ($.inArray($(".permitApplicationStatus").val(), ["permit_app_nys"]) !== -1) {
        $("#cancelPermitApplication").removeClass("visually-hidden");
    }

    $(".permitApplicationStatus").click(function (e) {
        if (!confirm("Going back will lose any unsaved changes. Are you sure?")) {
            e.preventDefault();
        }
    });

    $("#submitPermitApplication").click(function (e) {
        if (!confirm("Are you sure you wish to submit this application?")) {
            e.preventDefault();
        }
    });

    // Add event handlers for Permits buttons
    $("#withdrawPermitApplication").click(function (e) {
        if (!confirm("This will withdraw the application and any fees paid will not be refunded. Are you sure?")) {
            e.preventDefault();
        }
    });

    $("#cancelPermitApplication").click(function (e) {
        if (!confirm("Are you sure you wish to cancel this application?")) {
            e.preventDefault();
        }
    });
});
