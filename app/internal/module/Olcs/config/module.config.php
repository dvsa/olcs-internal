<?php

return array(
    'router' => [
        'routes' => include __DIR__ . '/routes.config.php'
    ],
    'tables' => array(
        'config' => array(
            __DIR__ . '/../src/Table/Tables/'
        )
    ),
    'controllers' => array(
        'invokables' => array(
            'DefaultController' => 'Olcs\Olcs\Placeholder\Controller\DefaultController',
            'IndexController' => 'Olcs\Controller\IndexController',
            'SearchController' => 'Olcs\Controller\SearchController',
            'CaseController' => 'Olcs\Controller\CaseController',
            'CaseStatementController' => 'Olcs\Controller\CaseStatementController',
            'CaseAppealController' => 'Olcs\Controller\CaseAppealController',
            'CaseComplaintController' => 'Olcs\Controller\CaseComplaintController',
            'CaseConvictionController' => 'Olcs\Controller\CaseConvictionController',
            'SubmissionController' => 'Olcs\Controller\Submission\SubmissionController',
            'CaseStayController' => 'Olcs\Controller\CaseStayController',
            'CasePenaltyController' => 'Olcs\Controller\CasePenaltyController',
            'CaseProhibitionController' => 'Olcs\Controller\CaseProhibitionController',
            'CaseAnnualTestHistoryController' => 'Olcs\Controller\CaseAnnualTestHistoryController',
            'SubmissionNoteController' => 'Olcs\Controller\Submission\SubmissionNoteController',
            'CaseImpoundingController' => 'Olcs\Controller\CaseImpoundingController',
            'CaseConditionUndertakingController' => 'Olcs\Controller\CaseConditionUndertakingController',
            'CaseRevokeController' => 'Olcs\Controller\CaseRevokeController',
            'CasePiController' => 'Olcs\Controller\CasePiController',
            'DocumentController' => 'Olcs\Controller\DocumentController',
            'DefendantSearchController' => 'Olcs\DefendantSearchController'
            'LicenceController' => 'Olcs\Controller\Licence\LicenceController'
        )
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            'olcs/view' => dirname(__DIR__) . '/view',
        )
    ),
    'view_helpers' => array(
        'invokables' => array()
    ),
    'local_forms_path' => array(
        __DIR__ . '/../src/Form/Forms/'
    ),
    //-------- Start navigation -----------------
    'navigation' => array(
        'default' => array(
            include __DIR__ . '/navigation.config.php'
        )
    ),
    //-------- End navigation -----------------
    'submission_config' => include __DIR__ . '/submission/submission.config.php',
    'local_scripts_path' => array(
        __DIR__ . '/../assets/js/inline/'
    ),
    'asset_path' => '//dvsa-static.olcsdv-ap01.olcs.npm'
);
