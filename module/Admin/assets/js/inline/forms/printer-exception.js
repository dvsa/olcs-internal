OLCS.ready(function() {
  "use strict";

  function showTeamFieldset() {
    return OLCS.formHelper.isChecked("exception-details", "teamOrUser", "team");
  }

  function showUserFieldset() {
    return OLCS.formHelper.isChecked("exception-details", "teamOrUser", "user");
  }

  OLCS.cascadeForm({
    cascade: false,
    rulesets: {
      "data": '*',
      "team-printer": showTeamFieldset,
      "user-printer": showUserFieldset,
    }
  });

  OLCS.cascadeInput({
    source: "#categoryTeam",
    dest: "#subCategoryTeam",
    url: "/list/document-sub-categories-no-first-option"
  });

  OLCS.cascadeInput({
    source: "#categoryUser",
    dest: "#subCategoryUser",
    url: "/list/document-sub-categories",
    emptyLabel: "Default setting"
  });
});
