<?php

use Olcs\Controller\Cases\Hearing\HearingAppealController as CaseHearingAppealController;
use Olcs\Controller\Cases\Hearing\AppealController as CaseAppealController;
use Olcs\Controller\Cases\Hearing\StayController as CaseStayController;

use Olcs\Controller\Cases\Processing\NoteController as CaseNoteController;
use Olcs\Controller\Application\Processing\ApplicationProcessingNoteController as ApplicationProcessingNoteController;
use Olcs\Controller\Bus\Processing\BusProcessingNoteController as BusProcessingNoteController;
use Olcs\Controller\Licence\Processing\LicenceProcessingNoteController as LicenceProcessingNoteController;
use Olcs\Controller\Operator\OperatorProcessingNoteController as OperatorProcessingNoteController;
use Olcs\Controller\TransportManager\Processing\TransportManagerProcessingNoteController as TMProcessingNoteController;

use Olcs\Controller\TransportManager\TransportManagerController;
use Olcs\Controller\TransportManager\Details\TransportManagerDetailsDetailController;

use Olcs\Controller\SearchController as SearchController;

use Olcs\Listener\RouteParam\Application as ApplicationListener;
use Olcs\Listener\RouteParam\ApplicationTitle as ApplicationTitle;
use Olcs\Listener\RouteParam\Licence as LicenceListener;
use Olcs\Listener\RouteParam\LicenceTitle;
use Olcs\Listener\RouteParam\LicenceTitleLink;

use Common\Data\Object\Search\Licence as LicenceSearch;
use Olcs\Service\Marker;

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
        'initializers' => array(
            'Olcs\Controller\RouteParamInitializer'
        ),
        'delegators' => array(
            'LvaApplication/ConditionsUndertakings' => array(
                'Common\Controller\Lva\Delegators\ApplicationConditionsUndertakingsDelegator'
            ),
            'LvaVariation/ConditionsUndertakings' => array(
                'Common\Controller\Lva\Delegators\VariationConditionsUndertakingsDelegator'
            ),
            'LvaLicence/ConditionsUndertakings' => array(
                'Common\Controller\Lva\Delegators\LicenceConditionsUndertakingsDelegator'
            ),
        ),
        'lva_controllers' => array(
            'LvaApplication' => 'Olcs\Controller\Lva\Application\OverviewController',
            'LvaApplication/TypeOfLicence' => 'Olcs\Controller\Lva\Application\TypeOfLicenceController',
            'LvaApplication/BusinessType' => 'Olcs\Controller\Lva\Application\BusinessTypeController',
            'LvaApplication/BusinessDetails' => 'Olcs\Controller\Lva\Application\BusinessDetailsController',
            'LvaApplication/Addresses' => 'Olcs\Controller\Lva\Application\AddressesController',
            'LvaApplication/People' => 'Olcs\Controller\Lva\Application\PeopleController',
            'LvaApplication/OperatingCentres' => 'Olcs\Controller\Lva\Application\OperatingCentresController',
            'LvaApplication/FinancialEvidence' => 'Olcs\Controller\Lva\Application\FinancialEvidenceController',
            'LvaApplication/TransportManagers' => 'Olcs\Controller\Lva\Application\TransportManagersController',
            'LvaApplication/Vehicles' => 'Olcs\Controller\Lva\Application\VehiclesController',
            'LvaApplication/VehiclesPsv' => 'Olcs\Controller\Lva\Application\VehiclesPsvController',
            'LvaApplication/Safety' => 'Olcs\Controller\Lva\Application\SafetyController',
            'LvaApplication/CommunityLicences' => 'Olcs\Controller\Lva\Application\CommunityLicencesController',
            'LvaApplication/FinancialHistory' => 'Olcs\Controller\Lva\Application\FinancialHistoryController',
            'LvaApplication/LicenceHistory' => 'Olcs\Controller\Lva\Application\LicenceHistoryController',
            'LvaApplication/ConvictionsPenalties' => 'Olcs\Controller\Lva\Application\ConvictionsPenaltiesController',
            'LvaApplication/TaxiPhv' => 'Olcs\Controller\Lva\Application\TaxiPhvController',
            'LvaApplication/ConditionsUndertakings'
                => 'Olcs\Controller\Lva\Application\ConditionsUndertakingsController',
            'LvaApplication/VehiclesDeclarations' => 'Olcs\Controller\Lva\Application\VehiclesDeclarationsController',
            'LvaApplication/Review' => \Common\Controller\Lva\ReviewController::class,
            'LvaApplication/Grant' => 'Olcs\Controller\Lva\Application\GrantController',
            'LvaApplication/Withdraw' => 'Olcs\Controller\Lva\Application\WithdrawController',
            'LvaApplication/Refuse' => 'Olcs\Controller\Lva\Application\RefuseController',
            'LvaApplication/NotTakenUp' => 'Olcs\Controller\Lva\Application\NotTakenUpController',
            'LvaApplication/ReviveApplication' => 'Olcs\Controller\Lva\Application\ReviveApplicationController',
            'LvaApplication/Undertakings' => 'Olcs\Controller\Lva\Application\UndertakingsController',
            'LvaApplication/DeclarationsInternal' => 'Olcs\Controller\Lva\Application\DeclarationsInternalController',
            'ApplicationSchedule41Controller' => 'Olcs\Controller\Application\ApplicationSchedule41Controller',
            'VariationSchedule41Controller' => 'Olcs\Controller\Variation\VariationSchedule41Controller',
            'LvaLicence' => 'Olcs\Controller\Lva\Licence\OverviewController',
            'LvaLicence/TypeOfLicence' => 'Olcs\Controller\Lva\Licence\TypeOfLicenceController',
            'LvaLicence/BusinessType' => 'Olcs\Controller\Lva\Licence\BusinessTypeController',
            'LvaLicence/BusinessDetails' => 'Olcs\Controller\Lva\Licence\BusinessDetailsController',
            'LvaLicence/Addresses' => 'Olcs\Controller\Lva\Licence\AddressesController',
            'LvaLicence/People' => 'Olcs\Controller\Lva\Licence\PeopleController',
            'LvaLicence/OperatingCentres' => 'Olcs\Controller\Lva\Licence\OperatingCentresController',
            'LvaLicence/TransportManagers' => 'Olcs\Controller\Lva\Licence\TransportManagersController',
            'LvaLicence/Vehicles' => 'Olcs\Controller\Lva\Licence\VehiclesController',
            'LvaLicence/VehiclesPsv' => 'Olcs\Controller\Lva\Licence\VehiclesPsvController',
            'LvaLicence/Safety' => 'Olcs\Controller\Lva\Licence\SafetyController',
            'LvaLicence/CommunityLicences' => 'Olcs\Controller\Lva\Licence\CommunityLicencesController',
            'LvaLicence/TaxiPhv' => 'Olcs\Controller\Lva\Licence\TaxiPhvController',
            'LvaLicence/Discs' => 'Olcs\Controller\Lva\Licence\DiscsController',
            'LvaLicence/ConditionsUndertakings' => 'Olcs\Controller\Lva\Licence\ConditionsUndertakingsController',
            'LvaLicence/Variation' => 'Olcs\Controller\Lva\Licence\VariationController',
            'LvaVariation' => 'Olcs\Controller\Lva\Variation\OverviewController',
            'LvaVariation/TypeOfLicence' => 'Olcs\Controller\Lva\Variation\TypeOfLicenceController',
            'LvaVariation/BusinessType' => 'Olcs\Controller\Lva\Variation\BusinessTypeController',
            'LvaVariation/BusinessDetails' => 'Olcs\Controller\Lva\Variation\BusinessDetailsController',
            'LvaVariation/Addresses' => 'Olcs\Controller\Lva\Variation\AddressesController',
            'LvaVariation/People' => 'Olcs\Controller\Lva\Variation\PeopleController',
            'LvaVariation/OperatingCentres' => 'Olcs\Controller\Lva\Variation\OperatingCentresController',
            'LvaVariation/TransportManagers' => 'Olcs\Controller\Lva\Variation\TransportManagersController',
            'LvaVariation/Vehicles' => 'Olcs\Controller\Lva\Variation\VehiclesController',
            'LvaVariation/VehiclesPsv' => 'Olcs\Controller\Lva\Variation\VehiclesPsvController',
            'LvaVariation/Safety' => 'Olcs\Controller\Lva\Variation\SafetyController',
            'LvaVariation/CommunityLicences' => 'Olcs\Controller\Lva\Variation\CommunityLicencesController',
            'LvaVariation/TaxiPhv' => 'Olcs\Controller\Lva\Variation\TaxiPhvController',
            'LvaVariation/Discs' => 'Olcs\Controller\Lva\Variation\DiscsController',
            'LvaVariation/ConditionsUndertakings' => 'Olcs\Controller\Lva\Variation\ConditionsUndertakingsController',
            'LvaVariation/FinancialEvidence' => 'Olcs\Controller\Lva\Variation\FinancialEvidenceController',
            'LvaVariation/FinancialHistory' => 'Olcs\Controller\Lva\Variation\FinancialHistoryController',
            'LvaVariation/ConvictionsPenalties' => 'Olcs\Controller\Lva\Variation\ConvictionsPenaltiesController',
            'LvaVariation/VehiclesDeclarations' => 'Olcs\Controller\Lva\Variation\VehiclesDeclarationsController',
            'LvaVariation/Review' => \Common\Controller\Lva\ReviewController::class,
            'LvaVariation/Grant' => 'Olcs\Controller\Lva\Variation\GrantController',
            'LvaVariation/Undertakings' => 'Olcs\Controller\Lva\Variation\UndertakingsController',
            'LvaVariation/Withdraw' => 'Olcs\Controller\Lva\Variation\WithdrawController',
            'LvaVariation/Refuse' => 'Olcs\Controller\Lva\Variation\RefuseController',
            'LvaVariation/Revive' => 'Olcs\Controller\Lva\Variation\ReviveApplicationController',
            'LvaVariation/DeclarationsInternal' => 'Olcs\Controller\Lva\Variation\DeclarationsInternalController',
        ),
        'invokables' => array(
            \Olcs\Controller\Cases\PublicInquiry\PiController::class
                => \Olcs\Controller\Cases\PublicInquiry\PiController::class,
            \Olcs\Controller\Cases\Overview\OverviewController::class
                => \Olcs\Controller\Cases\Overview\OverviewController::class,
            'CaseController' => 'Olcs\Controller\Cases\CaseController',
            'CaseOppositionController' => 'Olcs\Controller\Cases\Opposition\OppositionController',
            'CaseStatementController' => 'Olcs\Controller\Cases\Statement\StatementController',
            CaseHearingAppealController::class => CaseHearingAppealController::class,
            CaseAppealController::class => CaseAppealController::class,
            CaseStayController::class => CaseStayController::class,
            'CaseComplaintController' => 'Olcs\Controller\Cases\Complaint\ComplaintController',
            'CaseEnvironmentalComplaintController'
                => 'Olcs\Controller\Cases\Complaint\EnvironmentalComplaintController',
            'CaseConvictionController' => 'Olcs\Controller\Cases\Conviction\ConvictionController',
            'CaseSeriousInfringementController'
                => 'Olcs\Controller\Cases\SeriousInfringement\SeriousInfringementController',
            'CaseOffenceController' => 'Olcs\Controller\Cases\Conviction\OffenceController',
            'CaseLegacyOffenceController' => 'Olcs\Controller\Cases\Conviction\LegacyOffenceController',
            'CaseSubmissionController' => 'Olcs\Controller\Cases\Submission\SubmissionController',
            'CaseProcessSubmissionController' => 'Olcs\Controller\Cases\Submission\ProcessSubmissionController',
            'CaseSubmissionSectionCommentController'
                => 'Olcs\Controller\Cases\Submission\SubmissionSectionCommentController',
            'CaseSubmissionRecommendationController'
                => 'Olcs\Controller\Cases\Submission\RecommendationController',
            'CaseSubmissionDecisionController'
                => 'Olcs\Controller\Cases\Submission\DecisionController',
            'CasePenaltyController' => 'Olcs\Controller\Cases\Penalty\PenaltyController',
            'CaseAppliedPenaltyController' => 'Olcs\Controller\Cases\Penalty\AppliedPenaltyController',
            'CaseProhibitionController' => 'Olcs\Controller\Cases\Prohibition\ProhibitionController',
            'CaseProhibitionDefectController' => 'Olcs\Controller\Cases\Prohibition\ProhibitionDefectController',
            'CaseAnnualTestHistoryController' => 'Olcs\Controller\Cases\AnnualTestHistory\AnnualTestHistoryController',
            'CaseImpoundingController' => 'Olcs\Controller\Cases\Impounding\ImpoundingController',
            'CaseConditionUndertakingController'
                => 'Olcs\Controller\Cases\ConditionUndertaking\ConditionUndertakingController',
            'CasePublicInquiryController' => 'Olcs\Controller\Cases\PublicInquiry\PublicInquiryController',
            'CaseNonPublicInquiryController' => 'Olcs\Controller\Cases\NonPublicInquiry\NonPublicInquiryController',
            'PublicInquiry\SlaController' => 'Olcs\Controller\Cases\PublicInquiry\SlaController',
            'PublicInquiry\HearingController' => 'Olcs\Controller\Cases\PublicInquiry\HearingController',
            'PublicInquiry\AgreedAndLegislationController'
                => 'Olcs\Controller\Cases\PublicInquiry\AgreedAndLegislationController',
            'PublicInquiry\RegisterDecisionController'
                => 'Olcs\Controller\Cases\PublicInquiry\RegisterDecisionController',
            CaseNoteController::class => CaseNoteController::class,
            'CaseTaskController' => 'Olcs\Controller\Cases\Processing\TaskController',
            'CaseDecisionsController' => 'Olcs\Controller\Cases\Processing\DecisionsController',
            'CaseDecisionsReputeNotLostController'
                => 'Olcs\Controller\Cases\Processing\DecisionsReputeNotLostController',
            'CaseDecisionsDeclareUnfitController'
                => 'Olcs\Controller\Cases\Processing\DecisionsDeclareUnfitController',
            'CaseDecisionsNoFurtherActionController'
                => 'Olcs\Controller\Cases\Processing\DecisionsNoFurtherActionController',
            'CaseRevokeController' => 'Olcs\Controller\Cases\Processing\RevokeController',
            'DefaultController' => 'Olcs\Olcs\Placeholder\Controller\DefaultController',
            'IndexController' => 'Olcs\Controller\IndexController',
            SearchController::class => SearchController::class,
            'DocumentController' => 'Olcs\Controller\Document\DocumentController',
            'DocumentGenerationController' => 'Olcs\Controller\Document\DocumentGenerationController',
            'DocumentUploadController' => 'Olcs\Controller\Document\DocumentUploadController',
            'DocumentFinaliseController' => 'Olcs\Controller\Document\DocumentFinaliseController',
            'DocumentRelinkController' => 'Olcs\Controller\Document\DocumentRelinkController',
            'LicenceController' => 'Olcs\Controller\Licence\LicenceController',
            'LicenceDecisionsController' => 'Olcs\Controller\Licence\LicenceDecisionsController',
            'LicenceGracePeriodsController' => 'Olcs\Controller\Licence\LicenceGracePeriodsController',
            'TaskController' => 'Olcs\Controller\TaskController',
            'LicenceDetailsOverviewController' => 'Olcs\Controller\Licence\Details\OverviewController',
            'LicenceDetailsTypeOfLicenceController' => 'Olcs\Controller\Licence\Details\TypeOfLicenceController',
            'LicenceDetailsBusinessDetailsController' => 'Olcs\Controller\Licence\Details\BusinessDetailsController',
            'LicenceDetailsAddressController' => 'Olcs\Controller\Licence\Details\AddressController',
            'LicenceDetailsPeopleController' => 'Olcs\Controller\Licence\Details\PeopleController',
            'LicenceDetailsOperatingCentreController' => 'Olcs\Controller\Licence\Details\OperatingCentreController',
            'LicenceDetailsTransportManagerController' => 'Olcs\Controller\Licence\Details\TransportManagerController',
            'LicenceDetailsVehicleController' => 'Olcs\Controller\Licence\Details\VehicleController',
            'LicenceDetailsVehiclePsvController' => 'Olcs\Controller\Licence\Details\VehiclePsvController',
            'LicenceDetailsDiscsPsvController' => 'Olcs\Controller\Licence\Details\DiscsPsvController',
            'LicenceDetailsSafetyController' => 'Olcs\Controller\Licence\Details\SafetyController',
            'LicenceDetailsConditionUndertakingController' =>
            'Olcs\Controller\Licence\Details\ConditionUndertakingController',
            'LicenceDetailsTaxiPhvController' => 'Olcs\Controller\Licence\Details\TaxiPhvController',
            'ApplicationController' => 'Olcs\Controller\Application\ApplicationController',
            'ApplicationProcessingTasksController'
                => 'Olcs\Controller\Application\Processing\ApplicationProcessingTasksController',
            'ApplicationProcessingOverviewController'
                => 'Olcs\Controller\Application\Processing\ApplicationProcessingOverviewController',
            ApplicationProcessingNoteController::class => ApplicationProcessingNoteController::class,
            'ApplicationProcessingInspectionRequestController'
                => 'Olcs\Controller\Application\Processing\ApplicationProcessingInspectionRequestController',
            'LicenceProcessingOverviewController'
                => 'Olcs\Controller\Licence\Processing\LicenceProcessingOverviewController',
            'LicenceProcessingPublicationsController'
                => 'Olcs\Controller\Licence\Processing\LicenceProcessingPublicationsController',
            'LicenceProcessingTasksController' => 'Olcs\Controller\Licence\Processing\LicenceProcessingTasksController',
            LicenceProcessingNoteController::class => LicenceProcessingNoteController::class,
            'LicenceProcessingInspectionRequestController'
                => 'Olcs\Controller\Licence\Processing\LicenceProcessingInspectionRequestController',
            'BusController' => 'Olcs\Controller\Bus\BusController',
            'BusRegistrationController' => 'Olcs\Controller\Bus\Registration\BusRegistrationController',
            'BusDetailsController' => 'Olcs\Controller\Bus\Details\BusDetailsController',
            'BusDetailsServiceController' => 'Olcs\Controller\Bus\Details\BusDetailsServiceController',
            'BusDetailsStopController' => 'Olcs\Controller\Bus\Details\BusDetailsStopController',
            'BusDetailsTaController' => 'Olcs\Controller\Bus\Details\BusDetailsTaController',
            'BusDetailsQualityController' => 'Olcs\Controller\Bus\Details\BusDetailsQualityController',
            'BusShortController' => 'Olcs\Controller\Bus\Short\BusShortController',
            'BusShortPlaceholderController' => 'Olcs\Controller\Bus\Short\BusShortPlaceholderController',
            'BusRouteController' => 'Olcs\Controller\Bus\Route\BusRouteController',
            'BusRoutePlaceholderController' => 'Olcs\Controller\Bus\Route\BusRoutePlaceholderController',
            'BusTrcController' => 'Olcs\Controller\Bus\Trc\BusTrcController',
            'BusTrcPlaceholderController' => 'Olcs\Controller\Bus\Trc\BusTrcPlaceholderController',
            'BusDocsController' => 'Olcs\Controller\Bus\Docs\BusDocsController',
            'BusDocsPlaceholderController' => 'Olcs\Controller\Bus\Docs\BusDocsPlaceholderController',
            'BusProcessingDecisionController' => 'Olcs\Controller\Bus\Processing\BusProcessingDecisionController',
            BusProcessingNoteController::class => BusProcessingNoteController::class,
            'BusProcessingRegistrationHistoryController'
                => 'Olcs\Controller\Bus\Processing\BusProcessingRegistrationHistoryController',
            'BusProcessingTaskController' => 'Olcs\Controller\Bus\Processing\BusProcessingTaskController',
            'BusFeesController' => 'Olcs\Controller\Bus\Fees\BusFeesController',
            'BusFeesPlaceholderController' => 'Olcs\Controller\Bus\Fees\BusFeesPlaceholderController',
            'BusServiceController' => 'Olcs\Controller\Bus\Service\BusServiceController',
            'BusRequestMapController' => 'Olcs\Controller\Bus\BusRequestMapController',
            'OperatorController' => 'Olcs\Controller\Operator\OperatorController',
            'OperatorBusinessDetailsController' => 'Olcs\Controller\Operator\OperatorBusinessDetailsController',
            'UnlicensedBusinessDetailsController' => 'Olcs\Controller\Operator\UnlicensedBusinessDetailsController',
            'UnlicensedOperatorController' => 'Olcs\Controller\Operator\UnlicensedOperatorController',
            'UnlicensedOperatorVehiclesController' => 'Olcs\Controller\Operator\UnlicensedOperatorVehiclesController',
            'OperatorPeopleController' => 'Olcs\Controller\Operator\OperatorPeopleController',
            'OperatorLicencesApplicationsController'
                => 'Olcs\Controller\Operator\OperatorLicencesApplicationsController',
            'OperatorIrfoDetailsController'
                => 'Olcs\Controller\Operator\OperatorIrfoDetailsController',
            'OperatorIrfoGvPermitsController'
                => 'Olcs\Controller\Operator\OperatorIrfoGvPermitsController',
            'OperatorIrfoPsvAuthorisationsController'
                => 'Olcs\Controller\Operator\OperatorIrfoPsvAuthorisationsController',
            OperatorProcessingNoteController::class => OperatorProcessingNoteController::class,
            'OperatorProcessingTasksController'
                => 'Olcs\Controller\Operator\OperatorProcessingTasksController',
            'OperatorFeesController'
                => 'Olcs\Controller\Operator\OperatorFeesController',
            'TMController' => TransportManagerController::class,
            'TMDetailsDetailController' => TransportManagerDetailsDetailController::class,
            'TMDetailsCompetenceController'
                => 'Olcs\Controller\TransportManager\Details\TransportManagerDetailsCompetenceController',
            'TMDetailsResponsibilityController'
                => 'Olcs\Controller\TransportManager\Details\TransportManagerDetailsResponsibilityController',
            'TMDetailsEmploymentController'
                => 'Olcs\Controller\TransportManager\Details\TransportManagerDetailsEmploymentController',
            'TMDetailsPreviousHistoryController'
                => 'Olcs\Controller\TransportManager\Details\TransportManagerDetailsPreviousHistoryController',
            'TMProcessingDecisionController'
                => \Olcs\Controller\TransportManager\Processing\TransportManagerProcessingDecisionController::class,
            'TMProcessingPublicationController'
                => 'Olcs\Controller\TransportManager\Processing\PublicationController',
            TMProcessingNoteController::class => TMProcessingNoteController::class,
            'TMProcessingTaskController'
                => 'Olcs\Controller\TransportManager\Processing\TransportManagerProcessingTaskController',
            'TMCaseController'
                => 'Olcs\Controller\TransportManager\TransportManagerCaseController',
            'TMDocumentController' => 'Olcs\Controller\TransportManager\TransportManagerDocumentController',
            'InterimApplicationController' => 'Olcs\Controller\Lva\Application\InterimController',
            'InterimVariationController' => 'Olcs\Controller\Lva\Variation\InterimController',
            'SplitScreenController' => 'Olcs\Controller\SplitScreenController',

            // Event History Controllers
            'CaseHistoryController' => 'Olcs\Controller\Cases\Processing\HistoryController',
            'BusRegHistoryController' => 'Olcs\Controller\Bus\Processing\HistoryController',
            'LicenceHistoryController' => 'Olcs\Controller\Licence\Processing\HistoryController',
            'TransportManagerHistoryController' => 'Olcs\Controller\TransportManager\Processing\HistoryController',
            'ApplicationHistoryController' => 'Olcs\Controller\Application\Processing\HistoryController',
            'OperatorHistoryController' => 'Olcs\Controller\Operator\HistoryController',
            'ContinuationController' => 'Olcs\Controller\Licence\ContinuationController',
            Olcs\Controller\DisqualifyController::class => Olcs\Controller\DisqualifyController::class,
        ),
    ),
    'controller_plugins' => array(
        'invokables' => array(
            'Olcs\Mvc\Controller\Plugin\Confirm' => 'Olcs\Mvc\Controller\Plugin\Confirm',
            \Olcs\Mvc\Controller\Plugin\ViewBuilder::class => \Olcs\Mvc\Controller\Plugin\ViewBuilder::class,
        ),
        'factories' => [
            \Olcs\Mvc\Controller\Plugin\Script::class => \Olcs\Mvc\Controller\Plugin\ScriptFactory::class,
            \Olcs\Mvc\Controller\Plugin\Placeholder::class => \Olcs\Mvc\Controller\Plugin\PlaceholderFactory::class,
            \Olcs\Mvc\Controller\Plugin\Table::class => \Olcs\Mvc\Controller\Plugin\TableFactory::class,
        ],
        'aliases' => array(
            'confirm' => 'Olcs\Mvc\Controller\Plugin\Confirm',
            'viewBuilder' => \Olcs\Mvc\Controller\Plugin\ViewBuilder::class,
            'script' => \Olcs\Mvc\Controller\Plugin\Script::class,
            'placeholder' => \Olcs\Mvc\Controller\Plugin\Placeholder::class,
            'table' => \Olcs\Mvc\Controller\Plugin\Table::class,
        )
    ),
    'view_manager' => array(
        'display_not_found_reason' => true,
        'display_exceptions' => true,
        'doctype' => 'HTML5',
        'not_found_template' => 'pages/404',
        'exception_template' => 'pages/500',
        'template_map' => array(
            'layout/layout' => __DIR__ . '/../view/layout/base.phtml',
            'pages/404' => __DIR__ . '/../view/pages/404.phtml',
            'pages/500' => __DIR__ . '/../view/pages/500.phtml'
        ),
        'template_path_stack' => array(
            'olcs' => dirname(__DIR__) . '/view',
            //'olcs/view' => dirname(__DIR__) . '/view',
        ),
        'strategies' => array(
            'ViewJsonStrategy'
        )
    ),
    'view_helpers' => array(
        'invokables' => array(
            'addressFormat' => 'Olcs\View\Helper\Address',
            'pageTitle' => 'Olcs\View\Helper\PageTitle',
            'pageSubtitle' => 'Olcs\View\Helper\PageSubtitle',
            'tableFilters' => 'Olcs\View\Helper\TableFilters',
            'piListData' => 'Olcs\View\Helper\PiListData',
            'formSubmissionSections' => 'Olcs\Form\View\Helper\SubmissionSections',
            'submissionSectionDetails' => 'Olcs\View\Helper\SubmissionSectionDetails',
            'submissionSectionOverview' => 'Olcs\View\Helper\SubmissionSectionOverview',
        ),
        'delegators' => array(
            'formElement' => array('Olcs\Form\View\Helper\FormElementDelegatorFactory')
        ),
        'factories' => array(
            'SubmissionSectionTable' => 'Olcs\View\Helper\SubmissionSectionTableFactory',
            'SubmissionSectionMultipleTables' => 'Olcs\View\Helper\SubmissionSectionMultipleTablesFactory',
            'Olcs\View\Helper\SlaIndicator' => 'Olcs\View\Helper\SlaIndicator',
            'showMarkers' => Olcs\View\Helper\MarkersFactory::class,
        ),
        'aliases' => [
            'slaIndicator' => 'Olcs\View\Helper\SlaIndicator'
        ]
    ),
    'local_forms_path' => array(
        __DIR__ . '/../src/Form/Forms/'
    ),
    //-------- Start navigation -----------------
    'navigation' => array(
        'default' => array(
            include __DIR__ . '/navigation.config.php'
        ),
        'right-sidebar' => array(
            include __DIR__ . '/navigation-right-sidebar.config.php'
        )
    ),
    //-------- End navigation -----------------
    'submission_config' => include __DIR__ . '/submission/submission.config.php',
    'local_scripts_path' => array(
        __DIR__ . '/../assets/js/inline/'
    ),
    'asset_path' => '//dev_dvsa-static.web01.olcs.mgt.mtpdvsa',
    'service_manager' => array(
        'aliases' => [
            'RouteParamsListener' => 'Olcs\Listener\RouteParams',
            'right-sidebar' => 'Olcs\Navigation\RightHandNavigation',
            'Zend\Authentication\AuthenticationService' => 'zfcuser_auth_service',
            'HeaderSearchListener' => 'Olcs\Listener\HeaderSearch'
        ],
        'invokables' => [
            'ApplicationUtility' => 'Olcs\Service\Utility\ApplicationUtility',
            'Olcs\Listener\RouteParams' => 'Olcs\Listener\RouteParams',
        ],
        'factories' => array(
            \Olcs\Service\Marker\MarkerService::class => \Olcs\Service\Marker\MarkerService::class,
            \Olcs\Service\Marker\MarkerPluginManager::class =>
                \Olcs\Service\Marker\MarkerPluginManagerFactory::class,
            'Olcs\Listener\RouteParam\BusRegId' => 'Olcs\Listener\RouteParam\BusRegId',
            'Olcs\Listener\RouteParam\BusRegAction' => 'Olcs\Listener\RouteParam\BusRegAction',
            'Olcs\Listener\RouteParam\BusRegMarker' => 'Olcs\Listener\RouteParam\BusRegMarker',
            'Olcs\Listener\RouteParam\TransportManagerMarker' => 'Olcs\Listener\RouteParam\TransportManagerMarker',
            'Olcs\Listener\RouteParam\Action' => 'Olcs\Listener\RouteParam\Action',
            'Olcs\Listener\RouteParam\TransportManager' => 'Olcs\Listener\RouteParam\TransportManager',
            ApplicationListener::class => ApplicationListener::class,
            ApplicationTitle::class => ApplicationTitle::class,
            'Olcs\Listener\RouteParam\Cases' => 'Olcs\Listener\RouteParam\Cases',
            LicenceListener::class => LicenceListener::class,
            'Olcs\Listener\RouteParam\CaseMarker' => 'Olcs\Listener\RouteParam\CaseMarker',
            LicenceTitle::class => LicenceTitle::class,
            LicenceTitleLink::class => LicenceTitleLink::class,
            'Olcs\Listener\RouteParam\Organisation' => 'Olcs\Listener\RouteParam\Organisation',
            'Olcs\Service\Data\BusNoticePeriod' => 'Olcs\Service\Data\BusNoticePeriod',
            'Olcs\Service\Data\BusServiceType' => 'Olcs\Service\Data\BusServiceType',
            'Olcs\Service\Data\User' => 'Olcs\Service\Data\User',
            'Olcs\Service\Data\Team' => 'Olcs\Service\Data\Team',
            'Olcs\Service\Data\PresidingTc' => 'Olcs\Service\Data\PresidingTc',
            'Olcs\Service\Data\SiPenaltyType' => 'Olcs\Service\Data\SiPenaltyType',
            'Olcs\Service\Data\Submission' => 'Olcs\Service\Data\Submission',
            'Olcs\Service\Data\SubmissionSectionComment' => 'Olcs\Service\Data\SubmissionSectionComment',
            'Olcs\Service\Data\Fee' => 'Olcs\Service\Data\Fee',
            'Olcs\Service\Data\Cases' => 'Olcs\Service\Data\Cases',
            'Olcs\Service\Data\Pi' => 'Olcs\Service\Data\Pi',
            'Olcs\Service\Data\TaskSubCategory' => 'Olcs\Service\Data\TaskSubCategory',
            'Olcs\Service\Data\OperatingCentresForInspectionRequest'
                => 'Olcs\Service\Data\OperatingCentresForInspectionRequest',
            'Olcs\Service\Data\IrfoGvPermitType' => 'Olcs\Service\Data\IrfoGvPermitType',
            'Olcs\Service\Data\IrfoCountry' => 'Olcs\Service\Data\IrfoCountry',
            'Olcs\Navigation\RightHandNavigation' => 'Olcs\Navigation\RightHandNavigationFactory',
            'Olcs\Service\Utility\DateUtility' => 'Olcs\Service\Utility\DateUtilityFactory',
            'Olcs\Listener\HeaderSearch' => 'Olcs\Listener\HeaderSearch',
            'Olcs\Service\Utility\PublicationHelper' => 'Olcs\Service\Utility\PublicationHelperFactory',
            'Olcs\Service\Nr\RestHelper' => 'Olcs\Service\Nr\RestHelper',
            'Olcs\Service\Data\SubmissionActionTypes' => 'Olcs\Service\Data\SubmissionActionTypes'
        )
    ),
    'form_elements' => [
        'factories' => [
            'PublicInquiryReason' => 'Olcs\Form\Element\PublicInquiryReasonFactory',
            'SubmissionSections' => 'Olcs\Form\Element\SubmissionSectionsFactory',
            'Olcs\Form\Element\SlaDateSelect' => 'Olcs\Form\Element\SlaDateSelectFactory',
            'Olcs\Form\Element\SlaDateTimeSelect' => 'Olcs\Form\Element\SlaDateTimeSelectFactory',
            'Olcs\Form\Element\SearchFilterFieldset' => 'Olcs\Form\Element\SearchFilterFieldsetFactory',
            'Olcs\Form\Element\SearchDateRangeFieldset' => 'Olcs\Form\Element\SearchDateRangeFieldsetFactory'
        ],
        'aliases' => [
            'SlaDateSelect' => 'Olcs\Form\Element\SlaDateSelect',
            'SlaDateTimeSelect' => 'Olcs\Form\Element\SlaDateTimeSelect',
            'SearchFilterFieldset' => 'Olcs\Form\Element\SearchFilterFieldset',
            'SearchDateRangeFieldset' => 'Olcs\Form\Element\SearchDateRangeFieldset'
        ]
    ],
    'route_param_listeners' => [
        'Olcs\Controller\Interfaces\CaseControllerInterface' => [
            'Olcs\Listener\RouteParam\Cases',
            LicenceListener::class,
            LicenceTitleLink::class,
            'Olcs\Listener\RouteParam\CaseMarker',
            ApplicationListener::class,
            'Olcs\Listener\RouteParam\TransportManager',
            'Olcs\Listener\RouteParam\Action',
            'Olcs\Listener\HeaderSearch'
        ],
        'Olcs\Controller\Interfaces\ApplicationControllerInterface' => [
            ApplicationListener::class,
            ApplicationTitle::class,
            'Olcs\Listener\RouteParam\Cases',
            LicenceListener::class,
            LicenceTitleLink::class,
            'Olcs\Listener\RouteParam\CaseMarker',
            'Olcs\Listener\RouteParam\TransportManager',
            'Olcs\Listener\RouteParam\Action',
            'Olcs\Listener\HeaderSearch'
        ],
        'Olcs\Controller\Interfaces\BusRegControllerInterface' => [
            'Olcs\Listener\RouteParam\CaseMarker',
            ApplicationListener::class,
            'Olcs\Listener\RouteParam\BusRegId',
            'Olcs\Listener\RouteParam\BusRegAction',
            'Olcs\Listener\RouteParam\BusRegMarker',
            LicenceListener::class,
            'Olcs\Listener\HeaderSearch'
        ],
        'Olcs\Controller\Interfaces\TransportManagerControllerInterface' => [
            'Olcs\Listener\RouteParam\TransportManager',
            'Olcs\Listener\RouteParam\CaseMarker',
            'Olcs\Listener\RouteParam\TransportManagerMarker',
            'Olcs\Listener\HeaderSearch'
        ],
        'Olcs\Controller\Interfaces\LicenceControllerInterface' => [
            LicenceListener::class,
            LicenceTitle::class,
            'Olcs\Listener\HeaderSearch'
        ],
        'Olcs\Controller\Interfaces\OperatorControllerInterface' => [
            'Olcs\Listener\RouteParam\Organisation'
        ],
    ],
    'search' => [
        'invokables' => [
            'licence'     => LicenceSearch::class,
            'application' => \Common\Data\Object\Search\Application::class,
            'case'        => \Common\Data\Object\Search\Cases::class,
            'psv_disc'    => \Common\Data\Object\Search\PsvDisc::class,
            'vehicle'     => \Common\Data\Object\Search\Vehicle::class,
            'address'     => \Common\Data\Object\Search\Address::class,
            'bus_reg'     => \Common\Data\Object\Search\BusReg::class,
            'people'      => \Common\Data\Object\Search\People::class,
            'user'        => \Common\Data\Object\Search\User::class,
            'publication' => \Common\Data\Object\Search\Publication::class,
            'organisation'     => \Common\Data\Object\Search\Organisation::class,
        ]
    ],
    'data_services' => [
        'invokables' => [
            \Olcs\Service\Data\RequestMap::class => \Olcs\Service\Data\RequestMap::class,
        ],
        'factories' => [
            'Olcs\Service\Data\SubmissionLegislation' => 'Olcs\Service\Data\SubmissionLegislation',
            'Olcs\Service\Data\PublicInquiryReason' => 'Olcs\Service\Data\PublicInquiryReason',
            'Olcs\Service\Data\PublicInquiryDecision' => 'Olcs\Service\Data\PublicInquiryDecision',
            'Olcs\Service\Data\PublicInquiryDefinition' => 'Olcs\Service\Data\PublicInquiryDefinition',
            'Olcs\Service\Data\ImpoundingLegislation' => 'Olcs\Service\Data\ImpoundingLegislation'
        ]
    ],
    'filters' => [
        'invokables' => [
            'Olcs\Filter\SubmissionSection\ComplianceComplaints' =>
                'Olcs\Filter\SubmissionSection\ComplianceComplaints',
            'Olcs\Filter\SubmissionSection\ConditionsAndUndertakings' =>
                'Olcs\Filter\SubmissionSection\ConditionsAndUndertakings',
            'Olcs\Filter\SubmissionSection\ConvictionFpnOffenceHistory' =>
                'Olcs\Filter\SubmissionSection\ConvictionFpnOffenceHistory',
            'Olcs\Filter\SubmissionSection\CaseSummary' => 'Olcs\Filter\SubmissionSection\CaseSummary',
            'Olcs\Filter\SubmissionSection\CaseOutline' => 'Olcs\Filter\SubmissionSection\CaseOutline',
            'Olcs\Filter\SubmissionSection\Persons' => 'Olcs\Filter\SubmissionSection\Persons',
            'Olcs\Filter\SubmissionSection\Oppositions' => 'Olcs\Filter\SubmissionSection\Oppositions',
            'Olcs\Filter\SubmissionSection\LinkedLicencesAppNumbers' =>
                'Olcs\Filter\SubmissionSection\LinkedLicencesAppNumbers',
            'Olcs\Filter\SubmissionSection\LeadTcArea' => 'Olcs\Filter\SubmissionSection\LeadTcArea',
            'Olcs\Filter\SubmissionSection\ProhibitionHistory' => 'Olcs\Filter\SubmissionSection\ProhibitionHistory',
            'Olcs\Filter\SubmissionSection\Penalties' => 'Olcs\Filter\SubmissionSection\Penalties',
            'Olcs\Filter\SubmissionSection\AnnualTestHistory' => 'Olcs\Filter\SubmissionSection\AnnualTestHistory',
            'Olcs\Filter\SubmissionSection\AuthRequestedAppliedFor' =>
                'Olcs\Filter\SubmissionSection\AuthRequestedAppliedFor',
            'Olcs\Filter\SubmissionSection\EnvironmentalComplaints' =>
                'Olcs\Filter\SubmissionSection\EnvironmentalComplaints',
            'Olcs\Filter\SubmissionSection\OutstandingApplications' =>
                'Olcs\Filter\SubmissionSection\OutstandingApplications',
            'Olcs\Filter\SubmissionSection\Statements' => 'Olcs\Filter\SubmissionSection\Statements',
            'Olcs\Filter\SubmissionSection\TransportManagers' => 'Olcs\Filter\SubmissionSection\TransportManagers',
            'Olcs\Filter\SubmissionSection\OperatingCentres' => 'Olcs\Filter\SubmissionSection\OperatingCentres',
            'Olcs\Filter\SubmissionSection\MostSeriousInfringement' =>
                'Olcs\Filter\SubmissionSection\MostSeriousInfringement',
            'Olcs\Filter\SubmissionSection\TmDetails' => 'Olcs\Filter\SubmissionSection\TmDetails',
            'Olcs\Filter\SubmissionSection\TmQualifications' => 'Olcs\Filter\SubmissionSection\TmQualifications',
            'Olcs\Filter\SubmissionSection\TmResponsibilities' => 'Olcs\Filter\SubmissionSection\TmResponsibilities',
            'Olcs\Filter\SubmissionSection\TmOtherEmployment' => 'Olcs\Filter\SubmissionSection\TmOtherEmployment',
            'Olcs\Filter\SubmissionSection\TmPreviousHistory' => 'Olcs\Filter\SubmissionSection\TmPreviousHistory'

        ],
        'aliases' => [
            'ComplianceComplaints' => 'Olcs\Filter\SubmissionSection\ComplianceComplaints',
            'ConditionsAndUndertakings' => 'Olcs\Filter\SubmissionSection\ConditionsAndUndertakings',
            'ConvictionFpnOffenceHistory' => 'Olcs\Filter\SubmissionSection\ConvictionFpnOffenceHistory',
            'CaseSummary' => 'Olcs\Filter\SubmissionSection\CaseSummary',
            'CaseOutline' => 'Olcs\Filter\SubmissionSection\CaseOutline',
            'Persons' => 'Olcs\Filter\SubmissionSection\Persons',
            'Oppositions' => 'Olcs\Filter\SubmissionSection\Oppositions',
            'LinkedLicencesAppNumbers' => 'Olcs\Filter\SubmissionSection\LinkedLicencesAppNumbers',
            'LeadTcArea' => 'Olcs\Filter\SubmissionSection\LeadTcArea',
            'ProhibitionHistory' => 'Olcs\Filter\SubmissionSection\ProhibitionHistory',
            'Penalties' => 'Olcs\Filter\SubmissionSection\Penalties',
            'AnnualTestHistory' => 'Olcs\Filter\SubmissionSection\AnnualTestHistory',
            'AuthRequestedAppliedFor' => 'Olcs\Filter\SubmissionSection\AuthRequestedAppliedFor',
            'EnvironmentalComplaints' => 'Olcs\Filter\SubmissionSection\EnvironmentalComplaints',
            'Statements' => 'Olcs\Filter\SubmissionSection\Statements',
            'TransportManagers' => 'Olcs\Filter\SubmissionSection\TransportManagers',
            'OperatingCentres' => 'Olcs\Filter\SubmissionSection\OperatingCentres',
            'MostSeriousInfringement' => 'Olcs\Filter\SubmissionSection\MostSeriousInfringement',
            'TmDetails' => 'Olcs\Filter\SubmissionSection\TmDetails',
            'TmQualifications' => 'Olcs\Filter\SubmissionSection\TmQualifications',
            'TmResponsibilities' => 'Olcs\Filter\SubmissionSection\TmResponsibilities',
            'TmOtherEmployment' => 'Olcs\Filter\SubmissionSection\TmOtherEmployment',
            'TmPreviousHistory' => 'Olcs\Filter\SubmissionSection\TmPreviousHistory',
        ]
    ],
    'zfc_rbac' => [
        'guards' => [
            'ZfcRbac\Guard\RoutePermissionsGuard' =>[
                'zfcuser/login'    => ['*'],
                'zfcuser/logout'    => ['*'],
                'case_processing_notes' => ['internal-notes'],
                '*case*' => ['internal-case'],
                '*documents*' => ['internal-documents'],
                '*docs*' => ['internal-documents'],
                'fetch_tmp_document' => ['internal-documents'],
                'note' => ['internal-notes'],
                // cli module route
                'batch-licence-status' => ['*'],
                'batch-cns' => ['*'],
                'process-queue' => ['*'],
                'inspection-request-email' => ['*'],
                'process-inbox' => ['*'],
                'enqueue-ch-compare' => ['*'],
                // Global route rule needs to be last
                '*' => ['internal-view'],
            ]
        ]
    ],
    'form_service_manager' => [
        'invokables' => [
            // Operating Centres
            'lva-application-operating_centres'
                => 'Olcs\FormService\Form\Lva\OperatingCentres\ApplicationOperatingCentres',
            // Operating Centre
            'lva-application-operating_centre'
            => 'Olcs\FormService\Form\Lva\OperatingCentre\ApplicationOperatingCentre',
            'lva-licence-operating_centre'
                => 'Olcs\FormService\Form\Lva\OperatingCentre\LicenceOperatingCentre',
            'lva-variation-operating_centre'
                => 'Olcs\FormService\Form\Lva\OperatingCentre\VariationOperatingCentre',
            // Goods Vehicles
            'lva-application-goods-vehicles-add-vehicle' => \Olcs\FormService\Form\Lva\GoodsVehicles\AddVehicle::class,
            'lva-licence-goods-vehicles-add-vehicle'
                => \Olcs\FormService\Form\Lva\GoodsVehicles\AddVehicleLicence::class,
            'lva-variation-goods-vehicles-add-vehicle' => \Olcs\FormService\Form\Lva\GoodsVehicles\AddVehicle::class,
            'lva-application-goods-vehicles-edit-vehicle'
                => \Olcs\FormService\Form\Lva\GoodsVehicles\EditVehicle::class,
            'lva-licence-goods-vehicles-edit-vehicle'
                => \Olcs\FormService\Form\Lva\GoodsVehicles\EditVehicleLicence::class,
            'lva-variation-goods-vehicles-edit-vehicle' => \Olcs\FormService\Form\Lva\GoodsVehicles\EditVehicle::class,

            'lva-licence' => \Olcs\FormService\Form\Lva\Licence::class,
            'lva-variation' => \Olcs\FormService\Form\Lva\Variation::class,
            'lva-application' => \Olcs\FormService\Form\Lva\Application::class,

            // Internal common psv vehicles vehicle form service
            'lva-psv-vehicles-vehicle' => 'Olcs\FormService\Form\Lva\PsvVehiclesVehicle',

            // Addresses form services
            'lva-licence-addresses' => \Olcs\FormService\Form\Lva\Addresses::class,
            'lva-variation-addresses' => \Olcs\FormService\Form\Lva\Addresses::class,
            'lva-application-addresses' => \Olcs\FormService\Form\Lva\Addresses::class,

            'lva-licence-people' => \Olcs\FormService\Form\Lva\People::class,
            'lva-variation-people' => \Olcs\FormService\Form\Lva\People::class,
            'lva-application-people' => \Olcs\FormService\Form\Lva\People::class,

            'lva-licence-community_licences' => \Olcs\FormService\Form\Lva\CommunityLicences::class,
            'lva-variation-community_licences' => \Olcs\FormService\Form\Lva\CommunityLicences::class,
            'lva-application-community_licences' => \Olcs\FormService\Form\Lva\CommunityLicences::class,

            'lva-licence-safety' => \Olcs\FormService\Form\Lva\Safety::class,
            'lva-variation-safety' => \Olcs\FormService\Form\Lva\Safety::class,
            'lva-application-safety' => \Olcs\FormService\Form\Lva\Safety::class,

            'lva-licence-conditions_undertakings' => \Olcs\FormService\Form\Lva\ConditionsUndertakings::class,
            'lva-variation-conditions_undertakings' => \Olcs\FormService\Form\Lva\ConditionsUndertakings::class,
            'lva-application-conditions_undertakings' => \Olcs\FormService\Form\Lva\ConditionsUndertakings::class,

            'lva-licence-financial_history' => \Olcs\FormService\Form\Lva\FinancialHistory::class,
            'lva-variation-financial_history' => \Olcs\FormService\Form\Lva\FinancialHistory::class,
            'lva-application-financial_history' => \Olcs\FormService\Form\Lva\FinancialHistory::class,

            'lva-licence-financial_evidence' => \Olcs\FormService\Form\Lva\FinancialEvidence::class,
            'lva-variation-financial_evidence' => \Olcs\FormService\Form\Lva\FinancialEvidence::class,
            'lva-application-financial_evidence' => \Olcs\FormService\Form\Lva\FinancialEvidence::class,

            'lva-variation-undertakings' => \Olcs\FormService\Form\Lva\Undertakings::class,
            'lva-application-undertakings' => \Olcs\FormService\Form\Lva\Undertakings::class,

            'lva-licence-taxi_phv' => \Olcs\FormService\Form\Lva\TaxiPhv::class,
            'lva-variation-taxi_phv' => \Olcs\FormService\Form\Lva\TaxiPhv::class,
            'lva-application-taxi_phv' => \Olcs\FormService\Form\Lva\TaxiPhv::class,

            'lva-application-licence_history' => \Olcs\FormService\Form\Lva\LicenceHistory::class,

            'lva-variation-convictions_penalties' => \Olcs\FormService\Form\Lva\ConvictionsPenalties::class,
            'lva-application-convictions_penalties' => \Olcs\FormService\Form\Lva\ConvictionsPenalties::class,

            'lva-variation-vehicles_declarations' => \Olcs\FormService\Form\Lva\VehiclesDeclarations::class,
            'lva-application-vehicles_declarations' => \Olcs\FormService\Form\Lva\VehiclesDeclarations::class,

            'lva-licence-vehicles_psv' => \Olcs\FormService\Form\Lva\PsvVehicles::class,
            'lva-variation-vehicles_psv' => \Olcs\FormService\Form\Lva\PsvVehicles::class,
            'lva-application-vehicles_psv' => \Olcs\FormService\Form\Lva\PsvVehicles::class,

            'lva-licence-discs' => \Olcs\FormService\Form\Lva\PsvDiscs::class,
            'lva-variation-discs' => \Olcs\FormService\Form\Lva\PsvDiscs::class,
        ]
    ],
    'business_service_manager' => [
        'invokables' => [
            'Lva\GracePeriod' => 'Olcs\BusinessService\Service\Lva\GracePeriod',
            'Lva\Schedule41' => 'Olcs\BusinessService\Service\Lva\Schedule41',
            'InspectionRequest' => 'Olcs\BusinessService\Service\InspectionRequest',
            'InspectionRequestUpdate' => 'Olcs\BusinessService\Service\InspectionRequestUpdate',
            'Cases\Penalty\ErruAppliedPenaltyResponse'
                => 'Olcs\BusinessService\Service\Cases\Penalty\ErruAppliedPenaltyResponse',
        ]
    ],
    'business_rule_manager' => [
        'invokables' => [
        ]
    ],
    'service_api_mapping' => array(
        'endpoints' => array(
            'nr' => 'http://olcs-nr/',
        )
    ),
    'hostnames' => array(),
    'marker_plugins' => array(
        'invokables' => array(
            Marker\ContinuationDetailMarker::class => Marker\ContinuationDetailMarker::class,
            Marker\LicenceStatusMarker::class => Marker\LicenceStatusMarker::class,
            Marker\LicenceStatusRuleMarker::class => Marker\LicenceStatusRuleMarker::class,
            Marker\DisqualificationMarker::class => Marker\DisqualificationMarker::class,
            Marker\CaseAppealMarker::class => Marker\CaseAppealMarker::class,
            Marker\CaseStayMarker::class => Marker\CaseStayMarker::class,
            Marker\BusRegShortNoticeRefused::class => Marker\BusRegShortNoticeRefused::class,
            Marker\BusRegEbsrMarker::class => Marker\BusRegEbsrMarker::class,
            Marker\TransportManager\SiQualificationMarker::class =>
                Marker\TransportManager\SiQualificationMarker::class,
            Marker\TransportManager\Rule450Marker::class => Marker\TransportManager\Rule450Marker::class,
            Marker\TransportManager\IsRemovedMarker::class => Marker\TransportManager\IsRemovedMarker::class,
            Marker\SoleTraderDisqualificationMarker::class => Marker\SoleTraderDisqualificationMarker::class,
        ),
    ),
);
