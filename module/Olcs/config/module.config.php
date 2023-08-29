<?php

use Common\Data\Object\Search\Licence as LicenceSearch;
use Common\Service\Data as CommonDataService;
use Olcs\Auth;
use Olcs\Controller\Application\ApplicationController;
use Olcs\Controller\Application\ApplicationControllerFactory;
use Olcs\Controller\Application\Processing\ApplicationProcessingInspectionRequestController;
use Olcs\Controller\Application\Processing\ApplicationProcessingNoteController;
use Olcs\Controller\Application\Processing\ApplicationProcessingPublicationsController;
use Olcs\Controller\Bus\Processing\BusProcessingDecisionController;
use Olcs\Controller\Bus\Processing\BusProcessingDecisionControllerFactory;
use Olcs\Controller\Bus\Processing\BusProcessingNoteController;
use Olcs\Controller\Bus\Registration\BusRegistrationController;
use Olcs\Controller\Bus\Registration\BusRegistrationControllerFactory;
use Olcs\Controller\Bus\Service\BusServiceController;
use Olcs\Controller\Bus\Service\BusServiceControllerFactory;
use Olcs\Controller\Cases;
use Olcs\Controller\IrhpPermits\IrhpApplicationProcessingHistoryController;
use Olcs\Controller\IrhpPermits\IrhpApplicationProcessingReadHistoryController;
use Olcs\Controller\IrhpPermits\IrhpPermitProcessingReadHistoryController;
use Olcs\Controller\Licence\BusRegistrationController as LicenceBusController;
use Olcs\Controller\Licence\Processing\LicenceProcessingNoteController;
use Olcs\Controller\Operator\OperatorProcessingNoteController;
use Olcs\Controller\SearchController;
use Olcs\Controller\TransportManager as TmCntr;
use Olcs\Controller\TransportManager\Details\TransportManagerDetailsDetailController;
use Olcs\Controller\TransportManager\Processing\TransportManagerProcessingNoteController as TMProcessingNoteController;
use Olcs\Controller\TransportManager\TransportManagerController;
use Olcs\Form\Element\SearchDateRangeFieldsetFactory;
use Olcs\Form\Element\SearchFilterFieldsetFactory;
use Olcs\Form\Element\SubmissionSectionsFactory;
use Olcs\FormService\Form\Lva\AbstractLvaFormFactory;
use Olcs\Listener\RouteParam;
use Olcs\Listener\RouteParam\Application as ApplicationListener;
use Olcs\Listener\RouteParam\ApplicationFurniture;
use Olcs\Listener\RouteParam\BusRegFurniture;
use Olcs\Listener\RouteParam\CasesFurniture;
use Olcs\Listener\RouteParam\IrhpApplicationFurniture;
use Olcs\Listener\RouteParam\Licence as LicenceListener;
use Olcs\Listener\RouteParam\LicenceFurniture;
use Olcs\Listener\RouteParam\OrganisationFurniture;
use Olcs\Listener\RouteParam\SubmissionsFurniture;
use Olcs\Listener\RouteParam\TransportManagerFurniture;
use Olcs\Listener\RouteParam\VariationFurniture;
use Olcs\Service\Marker;
use Olcs\Service\Processing as ProcessingService;
use Olcs\Service\Data as DataService;
use Olcs\Service\Helper as HelperService;
use Olcs\View\Helper\SlaIndicator;
use Olcs\View\Helper\SubmissionSectionMultipleTablesFactory;
use Olcs\View\Helper\SubmissionSectionTableFactory;

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
            'LvaApplication' => Olcs\Controller\Lva\Application\OverviewController::class,
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
            'LvaApplication/DeclarationsInternal' => 'Olcs\Controller\Lva\Application\DeclarationsInternalController',
            'LvaApplication/Publish' => 'Olcs\Controller\Lva\Application\PublishController',
            'LvaApplication/Submit' => Olcs\Controller\Lva\Application\SubmitController::class,
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
            'LvaLicence/Trailers' => 'Olcs\Controller\Lva\Licence\TrailersController',
            'LvaVariation' => Olcs\Controller\Lva\Variation\OverviewController::class,
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
            'LvaVariation/LicenceHistory' => 'Olcs\Controller\Lva\Variation\LicenceHistoryController',
            'LvaVariation/ConvictionsPenalties' => 'Olcs\Controller\Lva\Variation\ConvictionsPenaltiesController',
            'LvaVariation/VehiclesDeclarations' => 'Olcs\Controller\Lva\Variation\VehiclesDeclarationsController',
            'LvaVariation/Review' => \Common\Controller\Lva\ReviewController::class,
            'LvaVariation/Grant' => 'Olcs\Controller\Lva\Variation\GrantController',
            'LvaVariation/Withdraw' => 'Olcs\Controller\Lva\Variation\WithdrawController',
            'LvaVariation/Refuse' => 'Olcs\Controller\Lva\Variation\RefuseController',
            'LvaVariation/Submit' => Olcs\Controller\Lva\Variation\SubmitController::class,
            'LvaVariation/Revive' => 'Olcs\Controller\Lva\Variation\ReviveApplicationController',
            'LvaVariation/DeclarationsInternal' => 'Olcs\Controller\Lva\Variation\DeclarationsInternalController',
            'LvaVariation/Publish' => 'Olcs\Controller\Lva\Variation\PublishController',
        ),
        'invokables' => array(
            Cases\Submission\ProcessSubmissionController::class => Cases\Submission\ProcessSubmissionController::class,
            Cases\Submission\RecommendationController::class => Cases\Submission\RecommendationController::class,
            Cases\Opposition\OppositionController::class => Cases\Opposition\OppositionController::class,
            Cases\Hearing\HearingAppealController::class => Cases\Hearing\HearingAppealController::class,
            Cases\Conviction\ConvictionController::class => Cases\Conviction\ConvictionController::class,
            Cases\Statement\StatementController::class => Cases\Statement\StatementController::class,
            Cases\Processing\NoteController::class => Cases\Processing\NoteController::class,
            Cases\Processing\TaskController::class => Cases\Processing\TaskController::class,
            Cases\Hearing\AppealController::class => Cases\Hearing\AppealController::class,
            Cases\Hearing\StayController::class => Cases\Hearing\StayController::class,


            'CaseDocsController' => 'Olcs\Controller\Cases\Docs\CaseDocsController',
            'CaseComplaintController' => 'Olcs\Controller\Cases\Complaint\ComplaintController',
            'CaseEnvironmentalComplaintController'
                => 'Olcs\Controller\Cases\Complaint\EnvironmentalComplaintController',
            'CaseOffenceController' => 'Olcs\Controller\Cases\Conviction\OffenceController',
            'CaseLegacyOffenceController' => 'Olcs\Controller\Cases\Conviction\LegacyOffenceController',
            Cases\Submission\SubmissionSectionCommentController::class =>
                Cases\Submission\SubmissionSectionCommentController::class,
            'CaseSubmissionDecisionController'
                => 'Olcs\Controller\Cases\Submission\DecisionController',
            'CasePenaltyController' => Olcs\Controller\Cases\Penalty\PenaltyController::class,
            'CaseSiController' => 'Olcs\Controller\Cases\Penalty\SiController',
            'CaseProhibitionController' => 'Olcs\Controller\Cases\Prohibition\ProhibitionController',
            'CaseProhibitionDefectController' => 'Olcs\Controller\Cases\Prohibition\ProhibitionDefectController',
            'CaseAnnualTestHistoryController' => 'Olcs\Controller\Cases\AnnualTestHistory\AnnualTestHistoryController',
            'CaseImpoundingController' => 'Olcs\Controller\Cases\Impounding\ImpoundingController',
            'CaseConditionUndertakingController'
                => 'Olcs\Controller\Cases\ConditionUndertaking\ConditionUndertakingController',
            'CasePublicInquiryController' => 'Olcs\Controller\Cases\PublicInquiry\PublicInquiryController',
            'CaseNonPublicInquiryController' => 'Olcs\Controller\Cases\NonPublicInquiry\NonPublicInquiryController',
            'PublicInquiry\SlaController' => 'Olcs\Controller\Cases\PublicInquiry\SlaController',
            'PublicInquiry\AgreedAndLegislationController'
                => 'Olcs\Controller\Cases\PublicInquiry\AgreedAndLegislationController',
            'PublicInquiry\RegisterDecisionController'
                => 'Olcs\Controller\Cases\PublicInquiry\RegisterDecisionController',
            'CaseDecisionsController' => 'Olcs\Controller\Cases\Processing\DecisionsController',
            'CaseDecisionsReputeNotLostController'
                => 'Olcs\Controller\Cases\Processing\DecisionsReputeNotLostController',
            'CaseDecisionsDeclareUnfitController'
                => 'Olcs\Controller\Cases\Processing\DecisionsDeclareUnfitController',
            'CaseDecisionsNoFurtherActionController'
                => 'Olcs\Controller\Cases\Processing\DecisionsNoFurtherActionController',
            'CaseRevokeController' => 'Olcs\Controller\Cases\Processing\RevokeController',
            \Olcs\Controller\Sla\RevocationsSlaController::class =>
                \Olcs\Controller\Sla\RevocationsSlaController::class,
            'DefaultController' => 'Olcs\Olcs\Placeholder\Controller\DefaultController',
            Olcs\Controller\IndexController::class => Olcs\Controller\IndexController::class,
            SearchController::class => SearchController::class,
            'DocumentController' => 'Olcs\Controller\Document\DocumentController',
            'DocumentGenerationController' => 'Olcs\Controller\Document\DocumentGenerationController',
            'DocumentUploadController' => 'Olcs\Controller\Document\DocumentUploadController',
            'DocumentFinaliseController' => 'Olcs\Controller\Document\DocumentFinaliseController',
            'DocumentRelinkController' => 'Olcs\Controller\Document\DocumentRelinkController',
            'LicenceController' => 'Olcs\Controller\Licence\LicenceController',
            'LicenceDocsController' => 'Olcs\Controller\Licence\Docs\LicenceDocsController',
            'LicenceFeesController' => 'Olcs\Controller\Licence\Fees\LicenceFeesController',
            LicenceBusController::class => BusRegistrationControllerFactory::class,
            'LicenceDecisionsController' => 'Olcs\Controller\Licence\LicenceDecisionsController',
            'LicencePermitsController' => 'Olcs\Controller\Licence\Permits\LicencePermitsController',
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
            'ApplicationDocsController' => 'Olcs\Controller\Application\Docs\ApplicationDocsController',
            'ApplicationFeesController' => 'Olcs\Controller\Application\Fees\ApplicationFeesController',
            'ApplicationProcessingTasksController'
                => 'Olcs\Controller\Application\Processing\ApplicationProcessingTasksController',
            'ApplicationProcessingOverviewController'
                => 'Olcs\Controller\Application\Processing\ApplicationProcessingOverviewController',
            ApplicationProcessingNoteController::class => ApplicationProcessingNoteController::class,
            ApplicationProcessingInspectionRequestController::class
            => Olcs\Controller\Application\Processing\ApplicationProcessingInspectionRequestControllerFactory::class,
            ApplicationProcessingPublicationsController::class
            => Olcs\Controller\Application\Processing\ApplicationProcessingPublicationsControllerFactory::class,
            'LicenceProcessingOverviewController'
                => 'Olcs\Controller\Licence\Processing\LicenceProcessingOverviewController',
            'LicenceProcessingPublicationsController'
                => 'Olcs\Controller\Licence\Processing\LicenceProcessingPublicationsController',
            'LicenceProcessingTasksController' => 'Olcs\Controller\Licence\Processing\LicenceProcessingTasksController',
            LicenceProcessingNoteController::class => LicenceProcessingNoteController::class,
            'LicenceProcessingInspectionRequestController'
                => 'Olcs\Controller\Licence\Processing\LicenceProcessingInspectionRequestController',
            Olcs\Controller\Bus\Registration\BusRegistrationController::class =>
                Olcs\Controller\Bus\Registration\BusRegistrationController::class,
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
            Olcs\Controller\Bus\Processing\BusProcessingDecisionController::class =>
                Olcs\Controller\Bus\Processing\BusProcessingDecisionController::class,
            BusProcessingNoteController::class => BusProcessingNoteController::class,
            'BusProcessingRegistrationHistoryController'
                => 'Olcs\Controller\Bus\Processing\BusProcessingRegistrationHistoryController',
            'BusProcessingTaskController' => 'Olcs\Controller\Bus\Processing\BusProcessingTaskController',
            'BusFeesController' => 'Olcs\Controller\Bus\Fees\BusFeesController',
            'BusFeesPlaceholderController' => 'Olcs\Controller\Bus\Fees\BusFeesPlaceholderController',
            'BusRequestMapController' => 'Olcs\Controller\Bus\BusRequestMapController',
            Olcs\Controller\Operator\OperatorController::class => Olcs\Controller\Operator\OperatorController::class,
            'OperatorDocsController' => 'Olcs\Controller\Operator\Docs\OperatorDocsController',
            'OperatorBusinessDetailsController' => 'Olcs\Controller\Operator\OperatorBusinessDetailsController',
            Olcs\Controller\Operator\UnlicensedBusinessDetailsController::class =>
                Olcs\Controller\Operator\UnlicensedBusinessDetailsController::class,
            'UnlicensedCasesOperatorController' => 'Olcs\Controller\Operator\Cases\UnlicensedCasesOperatorController',
            'UnlicensedOperatorVehiclesController' => 'Olcs\Controller\Operator\UnlicensedOperatorVehiclesController',
            'OperatorPeopleController' => 'Olcs\Controller\Operator\OperatorPeopleController',
            Olcs\Controller\Operator\OperatorLicencesApplicationsController::class =>
                Olcs\Controller\Operator\OperatorLicencesApplicationsController::class,
            'OperatorIrfoDetailsController'
                => 'Olcs\Controller\Operator\OperatorIrfoDetailsController',
            'OperatorIrfoGvPermitsController'
                => 'Olcs\Controller\Operator\OperatorIrfoGvPermitsController',
            'OperatorIrfoPsvAuthorisationsController'
                => 'Olcs\Controller\Operator\OperatorIrfoPsvAuthorisationsController',
            OperatorProcessingNoteController::class => OperatorProcessingNoteController::class,
            'OperatorProcessingTasksController'
                => 'Olcs\Controller\Operator\OperatorProcessingTasksController',
            'OperatorFeesController' => 'Olcs\Controller\Operator\OperatorFeesController',
            Olcs\Controller\Operator\OperatorUsersController::class =>
                Olcs\Controller\Operator\OperatorUsersController::class,
            'TMController' => TransportManagerController::class,
            'HistoricTmController'
                => Olcs\Controller\TransportManager\HistoricTm\HistoricTmController::class,
            'TMDetailsDetailController' => TransportManagerDetailsDetailController::class,
            'TMDetailsCompetenceController'
                => 'Olcs\Controller\TransportManager\Details\TransportManagerDetailsCompetenceController',
            'TMDetailsEmploymentController'
                => 'Olcs\Controller\TransportManager\Details\TransportManagerDetailsEmploymentController',
            'TMDetailsPreviousHistoryController'
                => 'Olcs\Controller\TransportManager\Details\TransportManagerDetailsPreviousHistoryController',
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
            'CaseReadHistoryController' => 'Olcs\Controller\Cases\Processing\ReadHistoryController',
            'BusRegHistoryController' => 'Olcs\Controller\Bus\Processing\HistoryController',
            'BusRegReadHistoryController' => 'Olcs\Controller\Bus\Processing\ReadHistoryController',
            'LicenceHistoryController' => 'Olcs\Controller\Licence\Processing\HistoryController',
            'LicenceReadHistoryController' => 'Olcs\Controller\Licence\Processing\ReadHistoryController',
            'TransportManagerHistoryController' => 'Olcs\Controller\TransportManager\Processing\HistoryController',
            IrhpApplicationProcessingReadHistoryController::class => IrhpApplicationProcessingReadHistoryController::class,
            'TransportManagerReadHistoryController'
                => 'Olcs\Controller\TransportManager\Processing\ReadHistoryController',
            'ApplicationHistoryController' => 'Olcs\Controller\Application\Processing\HistoryController',
            'ApplicationReadHistoryController' => 'Olcs\Controller\Application\Processing\ReadHistoryController',
            'OperatorHistoryController' => 'Olcs\Controller\Operator\HistoryController',
            'OperatorReadHistoryController' => 'Olcs\Controller\Operator\Processing\ReadHistoryController',
            \Olcs\Controller\Licence\ContinuationController::class =>
                \Olcs\Controller\Licence\ContinuationController::class,
           'CaseDocumentSlaTargetDateController' => 'Olcs\Controller\Sla\CaseDocumentSlaTargetDateController',
            'LicenceDocumentSlaTargetDateController' => 'Olcs\Controller\Sla\LicenceDocumentSlaTargetDateController',
            \Olcs\Controller\IrhpPermits\ApplicationController::class => \Olcs\Controller\IrhpPermits\ApplicationController::class,
            \Olcs\Controller\IrhpPermits\PermitController::class => \Olcs\Controller\IrhpPermits\PermitController::class,
            \Olcs\Controller\IrhpPermits\IrhpApplicationFeesController::class => \Olcs\Controller\IrhpPermits\IrhpApplicationFeesController::class,
            'IrhpPermitController' => 'Olcs\Controller\IrhpPermits\IrhpPermitController',
            'IrhpDocsController' => 'Olcs\Controller\IrhpPermits\IrhpDocsController',
            'IrhpApplicationDocsController' => 'Olcs\Controller\IrhpPermits\IrhpApplicationDocsController',
            IrhpApplicationProcessingHistoryController::class => IrhpApplicationProcessingHistoryController::class,
            \Olcs\Controller\IrhpPermits\ChangeHistoryController::class =>
                \Olcs\Controller\IrhpPermits\ChangeHistoryController::class,
            \Olcs\Controller\IrhpPermits\IrhpApplicationProcessingOverviewController::class =>
                \Olcs\Controller\IrhpPermits\IrhpApplicationProcessingOverviewController::class,
            \Olcs\Controller\IrhpPermits\IrhpApplicationProcessingNoteController::class =>
                \Olcs\Controller\IrhpPermits\IrhpApplicationProcessingNoteController::class,
            \Olcs\Controller\IrhpPermits\IrhpApplicationProcessingTasksController::class =>
                \Olcs\Controller\IrhpPermits\IrhpApplicationProcessingTasksController::class,
            Olcs\Controller\Licence\SurrenderController::class => Olcs\Controller\Licence\SurrenderController::class,
        ),
        'factories' => [
            TmCntr\Details\TransportManagerDetailsResponsibilityController::class =>
                TmCntr\Details\TransportManagerDetailsResponsibilityController::class,
            \Olcs\Controller\Auth\LoginController::class => \Olcs\Controller\Auth\LoginControllerFactory::class,
            ApplicationController::class => ApplicationControllerFactory::class,
            BusProcessingDecisionController::class => BusProcessingDecisionControllerFactory::class,
            BusRegistrationController::class => BusRegistrationControllerFactory::class,
            BusServiceController::class => BusServiceControllerFactory::class,
            Olcs\Controller\Bus\Details\BusDetailsController::class =>
                Olcs\Controller\Bus\Details\BusDetailsControllerFactory::class,
            Olcs\Controller\DisqualifyController::class => Olcs\Controller\DisqualifyControllerFactory::class,
            Cases\Submission\SubmissionController::class => Cases\Submission\SubmissionControllerFactory::class,
            Olcs\Controller\Cases\Penalty\PenaltyController::class => Olcs\Controller\Cases\Penalty\PenaltyControllerFactory::class,
            Cases\Overview\OverviewController::class => Cases\Overview\OverviewControllerfactory::class,
            Cases\PublicInquiry\PiController::class => Cases\PublicInquiry\PiControllerFactory::class,
            Cases\PublicInquiry\HearingController::class => Cases\PublicInquiry\HearingController::class,
            Olcs\Controller\Application\Processing\ApplicationProcessingPublicationsController::class
            => Olcs\Controller\Application\Processing\ApplicationProcessingPublicationsControllerFactory::class,
            Olcs\Controller\IrhpPermits\IrhpApplicationController::class => Olcs\Controller\IrhpPermits\IrhpApplicationController::class,
        ],
        'aliases' => [
            'ApplicationProcessingInspectionRequestController' => ApplicationProcessingInspectionRequestController::class,
            'ApplicationProcessingPublicationsController' => ApplicationProcessingPublicationsController::class,
            'ApplicationController' => ApplicationController::class,
        ]
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
        'not_found_template' => 'error/404',
        'exception_template' => 'error/index',
        'template_map' => array(
            'layout/layout' => __DIR__ . '/../view/layout/base.phtml',
            'auth/layout' => __DIR__ . '/../view/layout/signin.phtml',
            'pages/lva-details' => __DIR__ . '/../view/sections/lva/lva-details.phtml',
            'error/404' => __DIR__ . '/../view/error/404.phtml',
            'error/403' => __DIR__ . '/../view/error/404.phtml',
            'error/index' => __DIR__ . '/../view/error/index.phtml'
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
        'invokables' => [
            'piListData' => Olcs\View\Helper\PiListData::class,
            'formSubmissionSections' => Olcs\Form\View\Helper\SubmissionSections::class,
            'submissionSectionDetails' => Olcs\View\Helper\SubmissionSectionDetails::class,
            'submissionSectionOverview' => Olcs\View\Helper\SubmissionSectionOverview::class,
            'surrenderDetails' => Olcs\View\Helper\SurrenderDetails::class,
        ],
        'factories' => [
            'addressFormat' => Olcs\View\Helper\AddressFactory::class,
            'SubmissionSectionTable' => SubmissionSectionTableFactory::class,
            'SubmissionSectionMultipleTables' => SubmissionSectionMultipleTablesFactory::class,
            'Olcs\View\Helper\SlaIndicator' => SlaIndicator::class,
            'showMarkers' => Olcs\View\Helper\MarkersFactory::class,
            'showVersion' => Olcs\View\Helper\Factory\VersionFactory::class,
        ],
        'aliases' => [
            'slaIndicator' => 'Olcs\View\Helper\SlaIndicator'
        ]
    ),
    'form' => [
        'element' => [
            'renderers' => [
                \Olcs\Form\Element\SubmissionSections::class => 'formSubmissionSections',
            ],
        ],
    ],
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
            'HeaderSearchListener' => 'Olcs\Listener\HeaderSearch',
            'NavigationToggleListener' => 'Olcs\Listener\NavigationToggle',
            'Helper\ApplicationOverview' => HelperService\ApplicationOverviewHelperService::class,
            'Helper\LicenceOverview' => HelperService\LicenceOverviewHelperService::class,
        ],
        'invokables' => [
            'ApplicationUtility' => 'Olcs\Service\Utility\ApplicationUtility',
            'Olcs\Listener\RouteParams' => 'Olcs\Listener\RouteParams',
            Olcs\Service\Permits\Bilateral\MoroccoFieldsetPopulator::class =>
                Olcs\Service\Permits\Bilateral\MoroccoFieldsetPopulator::class,
        ],
        'abstract_factories' => [
            \Laminas\Cache\Service\StorageCacheAbstractServiceFactory::class,
        ],
        'factories' => array(
            DataService\ActionToBeTaken::class => CommonDataService\RefDataFactory::class,
            DataService\ApplicationStatus::class => CommonDataService\AbstractListDataServiceFactory::class,
            DataService\AssignedToList::class => CommonDataService\AbstractListDataServiceFactory::class,
            DataService\BusNoticePeriod::class => CommonDataService\AbstractDataServiceFactory::class,
            DataService\BusServiceType::class => CommonDataService\AbstractDataServiceFactory::class,
            DataService\Cases::class => CommonDataService\AbstractDataServiceFactory::class,
            DataService\Category::class => CommonDataService\AbstractListDataServiceFactory::class,
            DataService\DocumentCategory::class => CommonDataService\AbstractListDataServiceFactory::class,
            DataService\DocumentCategoryWithDocs::class => CommonDataService\AbstractListDataServiceFactory::class,
            DataService\DocumentSubCategory::class => CommonDataService\AbstractListDataServiceFactory::class,
            DataService\DocumentSubCategoryWithDocs::class => CommonDataService\AbstractListDataServiceFactory::class,
            DataService\EmailTemplateCategory::class => CommonDataService\AbstractListDataServiceFactory::class,
            DataService\IrfoCountry::class => CommonDataService\AbstractDataServiceFactory::class,
            DataService\IrfoGvPermitType::class => CommonDataService\AbstractDataServiceFactory::class,
            DataService\IrfoPsvAuthType::class => CommonDataService\AbstractDataServiceFactory::class,
            DataService\IrhpPermitPrintCountry::class => CommonDataService\AbstractDataServiceFactory::class,
            DataService\IrhpPermitPrintRangeType::class => DataService\IrhpPermitPrintRangeTypeFactory::class,
            DataService\IrhpPermitPrintStock::class => DataService\IrhpPermitPrintStockFactory::class,
            DataService\IrhpPermitPrintType::class => CommonDataService\AbstractDataServiceFactory::class,
            DataService\Licence::class => DataService\LicenceFactory::class,
            DataService\OperatingCentresForInspectionRequest::class => DataService\OperatingCentresForInspectionRequestFactory::class,
            DataService\PaymentType::class => CommonDataService\RefDataFactory::class,
            DataService\PresidingTc::class => CommonDataService\AbstractDataServiceFactory::class,
            DataService\Printer::class => CommonDataService\AbstractDataServiceFactory::class,
            DataService\ReportEmailTemplate::class => CommonDataService\AbstractDataServiceFactory::class,
            DataService\ReportLetterTemplate::class => CommonDataService\AbstractDataServiceFactory::class,
            DataService\ScannerCategory::class => CommonDataService\AbstractListDataServiceFactory::class,
            DataService\ScannerSubCategory::class => CommonDataService\AbstractListDataServiceFactory::class,
            DataService\SiPenaltyType::class => CommonDataService\AbstractDataServiceFactory::class,
            DataService\SubCategory::class => CommonDataService\AbstractListDataServiceFactory::class,
            DataService\SubCategoryDescription::class => CommonDataService\AbstractListDataServiceFactory::class,
            DataService\Submission::class => DataService\SubmissionFactory::class,
            DataService\SubmissionActionTypes::class => DataService\SubmissionActionTypesFactory::class,
            DataService\TaskCategory::class => CommonDataService\AbstractListDataServiceFactory::class,
            DataService\TaskSubCategory::class => CommonDataService\AbstractListDataServiceFactory::class,
            DataService\Team::class => DataService\TeamFactory::class,
            DataService\User::class => CommonDataService\AbstractDataServiceFactory::class,
            DataService\UserInternalTeamList::class => CommonDataService\AbstractListDataServiceFactory::class,
            DataService\UserListInternal::class => CommonDataService\AbstractListDataServiceFactory::class,
            DataService\UserListInternalExcludingLimitedReadOnlyUsers::class => CommonDataService\AbstractListDataServiceFactory::class,
            DataService\UserListInternalExcludingLimitedReadOnlyUsersSorted::class => CommonDataService\AbstractListDataServiceFactory::class,
            DataService\UserWithName::class => CommonDataService\AbstractDataServiceFactory::class,

            HelperService\ApplicationOverviewHelperService::class => HelperService\ApplicationOverviewHelperServiceFactory::class,
            HelperService\LicenceOverviewHelperService::class => HelperService\LicenceOverviewHelperServiceFactory::class,

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
            ApplicationFurniture::class => ApplicationFurniture::class,
            LicenceFurniture::class => LicenceFurniture::class,
            OrganisationFurniture::class => OrganisationFurniture::class,
            VariationFurniture::class => VariationFurniture::class,
            BusRegFurniture::class => BusRegFurniture::class,
            CasesFurniture::class => CasesFurniture::class,
            SubmissionsFurniture::class => SubmissionsFurniture::class,
            TransportManagerFurniture::class => TransportManagerFurniture::class,
            IrhpApplicationFurniture::class => IrhpApplicationFurniture::class,
            'Olcs\Listener\RouteParam\Cases' => 'Olcs\Listener\RouteParam\Cases',
            LicenceListener::class => LicenceListener::class,
            'Olcs\Listener\RouteParam\CaseMarker' => 'Olcs\Listener\RouteParam\CaseMarker',
            'Olcs\Listener\RouteParam\Organisation' => 'Olcs\Listener\RouteParam\Organisation',
            'Olcs\Navigation\RightHandNavigation' => 'Olcs\Navigation\RightHandNavigationFactory',
            'Olcs\Listener\HeaderSearch' => 'Olcs\Listener\HeaderSearch',
            'Olcs\Listener\NavigationToggle' => 'Olcs\Listener\NavigationToggle',

            Olcs\Data\Mapper\BilateralApplicationValidationModifier::class =>
                Olcs\Data\Mapper\BilateralApplicationValidationModifierFactory::class,
            Olcs\Data\Mapper\IrhpApplication::class =>
                Olcs\Data\Mapper\IrhpApplicationFactory::class,

            Olcs\Service\Permits\Bilateral\ApplicationFormPopulator::class =>
                Olcs\Service\Permits\Bilateral\ApplicationFormPopulatorFactory::class,
            Olcs\Service\Permits\Bilateral\CountryFieldsetGenerator::class =>
                Olcs\Service\Permits\Bilateral\CountryFieldsetGeneratorFactory::class,
            Olcs\Service\Permits\Bilateral\PeriodFieldsetGenerator::class =>
                Olcs\Service\Permits\Bilateral\PeriodFieldsetGeneratorFactory::class,
            Olcs\Service\Permits\Bilateral\StandardFieldsetPopulator::class =>
                Olcs\Service\Permits\Bilateral\StandardFieldsetPopulatorFactory::class,
            Olcs\Service\Permits\Bilateral\NoOfPermitsElementGenerator::class =>
                Olcs\Service\Permits\Bilateral\NoOfPermitsElementGeneratorFactory::class,

            \Olcs\Service\Helper\WebDavJsonWebTokenGenerationService::class =>
                \Olcs\Service\Helper\WebDavJsonWebTokenGenerationServiceFactory::class,

            Auth\Adapter\InternalCommandAdapter::class => Auth\Adapter\InternalCommandAdapterFactory::class,
            'Processing\CreateVariation' => ProcessingService\CreateVariationProcessingServiceFactory::class,
        )
    ),
    'form_elements' => [
        'factories' => [
            'SubmissionSections' => SubmissionSectionsFactory::class,
            'Olcs\Form\Element\SearchFilterFieldset' => SearchFilterFieldsetFactory::class,
            'Olcs\Form\Element\SearchDateRangeFieldset' => SearchDateRangeFieldsetFactory::class,
            Olcs\Form\Element\SearchOrderFieldset::class => Olcs\Form\Element\SearchOrderFieldsetFactory::class,
        ],
        'aliases' => [
            'SlaDateSelect' => 'Olcs\Form\Element\SlaDateSelect',
            'SlaDateTimeSelect' => 'Olcs\Form\Element\SlaDateTimeSelect',
            'SearchFilterFieldset' => 'Olcs\Form\Element\SearchFilterFieldset',
            'SearchDateRangeFieldset' => 'Olcs\Form\Element\SearchDateRangeFieldset'
        ]
    ],
    'route_param_listeners' => [
        \Olcs\Controller\Interfaces\CaseControllerInterface::class => [
            RouteParam\CasesFurniture::class,
            RouteParam\Cases::class,
            RouteParam\Licence::class,
            RouteParam\CaseMarker::class,
            RouteParam\Application::class,
            RouteParam\TransportManager::class,
            RouteParam\Action::class,
            \Olcs\Listener\HeaderSearch::class
        ],
        \Olcs\Controller\Interfaces\SubmissionControllerInterface::class => [
            RouteParam\SubmissionsFurniture::class,
            RouteParam\Cases::class,
            RouteParam\Licence::class,
            RouteParam\CaseMarker::class,
            RouteParam\Application::class,
            RouteParam\TransportManager::class,
            RouteParam\Action::class,
            \Olcs\Listener\HeaderSearch::class
        ],
        \Olcs\Controller\Interfaces\ApplicationControllerInterface::class => [
            RouteParam\ApplicationFurniture::class,
            RouteParam\Application::class,
            RouteParam\Cases::class,
            RouteParam\Licence::class,
            RouteParam\CaseMarker::class,
            RouteParam\TransportManager::class,
            RouteParam\Action::class,
            \Olcs\Listener\HeaderSearch::class
        ],
        // @NOTE This needs to be mostly the same as ApplicationControllerInterface except for the furniture
        \Olcs\Controller\Interfaces\VariationControllerInterface::class => [
            RouteParam\VariationFurniture::class,
            RouteParam\Application::class,
            RouteParam\Cases::class,
            RouteParam\Licence::class,
            RouteParam\CaseMarker::class,
            RouteParam\TransportManager::class,
            RouteParam\Action::class,
            \Olcs\Listener\HeaderSearch::class
        ],
        \Olcs\Controller\Interfaces\BusRegControllerInterface::class => [
            RouteParam\BusRegFurniture::class,
            RouteParam\CaseMarker::class,
            RouteParam\Application::class,
            RouteParam\BusRegId::class,
            RouteParam\BusRegAction::class,
            RouteParam\BusRegMarker::class,
            RouteParam\Licence::class,
            \Olcs\Listener\HeaderSearch::class
        ],
        \Olcs\Controller\Interfaces\TransportManagerControllerInterface::class => [
            RouteParam\TransportManagerFurniture::class,
            RouteParam\TransportManager::class,
            RouteParam\CaseMarker::class,
            RouteParam\TransportManagerMarker::class,
            \Olcs\Listener\HeaderSearch::class
        ],
        \Olcs\Controller\Interfaces\LicenceControllerInterface::class => [
            RouteParam\LicenceFurniture::class,
            RouteParam\Licence::class,
            \Olcs\Listener\HeaderSearch::class
        ],
        \Olcs\Controller\Interfaces\OperatorControllerInterface::class => [
            RouteParam\Organisation::class,
            RouteParam\OrganisationFurniture::class,
        ],
        \Olcs\Controller\Interfaces\IrhpApplicationControllerInterface::class => [
            RouteParam\IrhpApplicationFurniture::class,
            RouteParam\LicenceFurniture::class,
            RouteParam\Licence::class,
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
            'irfo'        => \Common\Data\Object\Search\IrfoOrganisation::class,
        ]
    ],
    'data_services' => [
        'invokables' => [
        ],
        'factories' => [
            DataService\AbstractPublicInquiryDataServices::class => DataService\AbstractPublicInquiryDataServicesFactory::class,
            DataService\ImpoundingLegislation::class => DataService\ImpoundingLegislationFactory::class,
            DataService\LicenceDecisionLegislation::class => DataService\LicenceDecisionLegislationFactory::class,
            DataService\PublicInquiryDecision::class => DataService\AbstractPublicInquiryDataFactory::class,
            DataService\PublicInquiryDefinition::class => DataService\AbstractPublicInquiryDataFactory::class,
            DataService\PublicInquiryReason::class => DataService\AbstractPublicInquiryDataFactory::class,
            DataService\SubmissionLegislation::class => DataService\AbstractPublicInquiryDataFactory::class,
        ]
    ],
    'form_service_manager' => [
        'abstract_factories' => [AbstractLvaFormFactory::class],
        'aliases' => AbstractLvaFormFactory::FORM_SERVICE_CLASS_ALIASES
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
    'date_settings' => array(
        'date_format' => 'd/m/Y',
        'datetime_format' => 'd/m/Y H:i',
        'datetimesec_format' => 'd/m/Y H:i:s'
    )
);
