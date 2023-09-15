<?php

use Common\Data\Object\Search\Licence as LicenceSearch;
use Common\Service\Data as CommonDataService;
use Olcs\Auth;
use Olcs\Controller\Application\Processing\ApplicationProcessingNoteController;
use Olcs\Controller\Bus\Processing\BusProcessingNoteController;
use Olcs\Controller\Cases;
use Olcs\Controller\Factory\Operator\HistoryControllerFactory;
use Olcs\Controller\IrhpPermits\IrhpApplicationProcessingHistoryController;
use Olcs\Controller\IrhpPermits\IrhpApplicationProcessingReadHistoryController;
use Olcs\Controller\IrhpPermits\IrhpPermitProcessingReadHistoryController;
use Olcs\Controller\Licence\BusRegistrationController as LicenceBusController;
use Olcs\Controller\Licence\Processing\LicenceProcessingNoteController;
use Olcs\Controller\Operator\HistoryController;
use Olcs\Controller\Operator\OperatorBusinessDetailsController;
use Olcs\Controller\Operator\OperatorFeesController;
use Olcs\Controller\Operator\OperatorProcessingNoteController;
use Olcs\Controller\Operator\OperatorProcessingTasksController;
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
use Olcs\Controller\Lva\Application as LvaApplicationControllers;
use Olcs\Controller\Lva\Factory\Controller\Application as LvaApplicationControllerFactories;
use Olcs\Controller\Lva\Licence as LvaLicenceControllers;
use Olcs\Controller\Lva\Factory\Controller\Licence as LvaLicenceControllerFactories;
use Olcs\Controller\Lva\Variation as LvaVariationControllers;
use Olcs\Controller\Lva\Factory\Controller\Variation as LvaVariationControllerFactories;
use Olcs\Controller\Operator as OperatorControllers;
use Olcs\Controller\Factory\Operator as OperatorControllerFactories;
use Olcs\Controller\Application as ApplicationControllers;
use Olcs\Controller\Factory\Application as ApplicationControllerFactories;
use Olcs\Controller\Bus as BusControllers;
use Olcs\Controller\Factory\Bus as BusControllerFactories;
use Olcs\Controller\Cases as CaseControllers;
use Olcs\Controller\Factory\Cases as CaseControllerFactories;
use Olcs\Controller\Document as DocumentControllers;
use Olcs\Controller\Factory\Document as DocumentControllerFactories;

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
            Cases\Submission\SubmissionController::class => Cases\Submission\SubmissionController::class,
            Cases\Statement\StatementController::class => Cases\Statement\StatementController::class,
            Cases\Overview\OverviewController::class => Cases\Overview\OverviewController::class,
            Cases\PublicInquiry\PiController::class => Cases\PublicInquiry\PiController::class,
            Cases\Processing\NoteController::class => Cases\Processing\NoteController::class,
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
            'CasePenaltyController' => 'Olcs\Controller\Cases\Penalty\PenaltyController',
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
            Cases\PublicInquiry\HearingController::class => Cases\PublicInquiry\HearingController::class,
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

            SearchController::class => SearchController::class,
            'DocumentController' => 'Olcs\Controller\Document\DocumentController',
            'LicenceController' => 'Olcs\Controller\Licence\LicenceController',
            'LicenceDocsController' => 'Olcs\Controller\Licence\Docs\LicenceDocsController',
            'LicenceFeesController' => 'Olcs\Controller\Licence\Fees\LicenceFeesController',
            LicenceBusController::class => LicenceBusController::class,
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

            'ApplicationFeesController' => 'Olcs\Controller\Application\Fees\ApplicationFeesController',
            'ApplicationProcessingTasksController'
                => 'Olcs\Controller\Application\Processing\ApplicationProcessingTasksController',
            ApplicationProcessingNoteController::class => ApplicationProcessingNoteController::class,
            'ApplicationProcessingInspectionRequestController'
                => 'Olcs\Controller\Application\Processing\ApplicationProcessingInspectionRequestController',
            'LicenceProcessingOverviewController'
                => 'Olcs\Controller\Licence\Processing\LicenceProcessingOverviewController',
            'LicenceProcessingPublicationsController'
                => 'Olcs\Controller\Licence\Processing\LicenceProcessingPublicationsController',
            'ApplicationProcessingPublicationsController'
                => Olcs\Controller\Application\Processing\ApplicationProcessingPublicationsController::class,
            'LicenceProcessingTasksController' => 'Olcs\Controller\Licence\Processing\LicenceProcessingTasksController',
            LicenceProcessingNoteController::class => LicenceProcessingNoteController::class,
            'LicenceProcessingInspectionRequestController'
                => 'Olcs\Controller\Licence\Processing\LicenceProcessingInspectionRequestController',
            Olcs\Controller\Bus\Registration\BusRegistrationController::class =>
                Olcs\Controller\Bus\Registration\BusRegistrationController::class,
            Olcs\Controller\Bus\Details\BusDetailsController::class =>
                Olcs\Controller\Bus\Details\BusDetailsController::class,
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
            'BusDocsPlaceholderController' => 'Olcs\Controller\Bus\Docs\BusDocsPlaceholderController',
            Olcs\Controller\Bus\Processing\BusProcessingDecisionController::class =>
                Olcs\Controller\Bus\Processing\BusProcessingDecisionController::class,
            BusProcessingNoteController::class => BusProcessingNoteController::class,
            'BusProcessingRegistrationHistoryController'
                => 'Olcs\Controller\Bus\Processing\BusProcessingRegistrationHistoryController',
            'BusProcessingTaskController' => 'Olcs\Controller\Bus\Processing\BusProcessingTaskController',
            'BusFeesController' => 'Olcs\Controller\Bus\Fees\BusFeesController',
            'BusFeesPlaceholderController' => 'Olcs\Controller\Bus\Fees\BusFeesPlaceholderController',
            Olcs\Controller\Bus\Service\BusServiceController::class =>
                Olcs\Controller\Bus\Service\BusServiceController::class,
            'BusRequestMapController' => 'Olcs\Controller\Bus\BusRequestMapController',
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
            'CaseHistoryController' => Olcs\Controller\Cases\Processing\HistoryController::class,
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
            'OperatorReadHistoryController' => 'Olcs\Controller\Operator\Processing\ReadHistoryController',
            \Olcs\Controller\Licence\ContinuationController::class =>
                \Olcs\Controller\Licence\ContinuationController::class,
            Olcs\Controller\DisqualifyController::class => Olcs\Controller\DisqualifyController::class,
            'CaseDocumentSlaTargetDateController' => 'Olcs\Controller\Sla\CaseDocumentSlaTargetDateController',
            'LicenceDocumentSlaTargetDateController' => 'Olcs\Controller\Sla\LicenceDocumentSlaTargetDateController',
            \Olcs\Controller\IrhpPermits\ApplicationController::class => \Olcs\Controller\IrhpPermits\ApplicationController::class,
            \Olcs\Controller\IrhpPermits\PermitController::class => \Olcs\Controller\IrhpPermits\PermitController::class,
            \Olcs\Controller\IrhpPermits\IrhpApplicationController::class => \Olcs\Controller\IrhpPermits\IrhpApplicationController::class,
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
            LvaApplicationControllers\AddressesController::class => LvaApplicationControllerFactories\AddressesControllerFactory::class,
            LvaApplicationControllers\BusinessDetailsController::class => LvaApplicationControllerFactories\BusinessDetailsControllerFactory::class,
            LvaApplicationControllers\BusinessTypeController::class => LvaApplicationControllerFactories\BusinessTypeControllerFactory::class,
            LvaApplicationControllers\CommunityLicencesController::class => LvaApplicationControllerFactories\CommunityLicencesControllerFactory::class,
            LvaApplicationControllers\ConditionsUndertakingsController::class => LvaApplicationControllerFactories\ConditionsUndertakingsControllerFactory::class,
            LvaApplicationControllers\ConvictionsPenaltiesController::class => LvaApplicationControllerFactories\ConvictionsPenaltiesControllerFactory::class,
            LvaApplicationControllers\DeclarationsInternalController::class => LvaApplicationControllerFactories\DeclarationsInternalControllerFactory::class,
            LvaApplicationControllers\FinancialEvidenceController::class => LvaApplicationControllerFactories\FinancialEvidenceControllerFactory::class,
            LvaApplicationControllers\FinancialHistoryController::class => LvaApplicationControllerFactories\FinancialHistoryControllerFactory::class,
            LvaApplicationControllers\GrantController::class => LvaApplicationControllerFactories\GrantControllerFactory::class,
            LvaApplicationControllers\InterimController::class => LvaApplicationControllerFactories\InterimControllerFactory::class,
            LvaApplicationControllers\LicenceHistoryController::class => LvaApplicationControllerFactories\LicenceHistoryControllerFactory::class,
            LvaApplicationControllers\NotTakenUpController::class => LvaApplicationControllerFactories\NotTakenUpControllerFactory::class,
            LvaApplicationControllers\OperatingCentresController::class => LvaApplicationControllerFactories\OperatingCentresControllerFactory::class,
            LvaApplicationControllers\OverviewController::class => LvaApplicationControllerFactories\OverviewControllerFactory::class,
            LvaApplicationControllers\PeopleController::class => LvaApplicationControllerFactories\PeopleControllerFactory::class,
            LvaApplicationControllers\PublishController::class => LvaApplicationControllerFactories\PublishControllerFactory::class,
            LvaApplicationControllers\RefuseController::class => LvaApplicationControllerFactories\RefuseControllerFactory::class,
            LvaApplicationControllers\ReviveApplicationController::class => LvaApplicationControllerFactories\ReviveApplicationControllerFactory::class,
            LvaApplicationControllers\SafetyController::class => LvaApplicationControllerFactories\SafetyControllerFactory::class,
            LvaApplicationControllers\SubmitController::class => LvaApplicationControllerFactories\SubmitControllerFactory::class,
            LvaApplicationControllers\TaxiPhvController::class => LvaApplicationControllerFactories\TaxiPhvControllerFactory::class,
            LvaApplicationControllers\TransportManagersController::class => LvaApplicationControllerFactories\TransportManagersControllerFactory::class,
            LvaApplicationControllers\TypeOfLicenceController::class => LvaApplicationControllerFactories\TypeOfLicenceControllerFactory::class,
            LvaApplicationControllers\VehiclesController::class => LvaApplicationControllerFactories\VehiclesControllerFactory::class,
            LvaApplicationControllers\VehiclesDeclarationsController::class => LvaApplicationControllerFactories\VehiclesDeclarationsControllerFactory::class,
            LvaApplicationControllers\VehiclesPsvController::class => LvaApplicationControllerFactories\VehiclesPsvControllerFactory::class,
            LvaApplicationControllers\WithdrawController::class => LvaApplicationControllerFactories\WithdrawControllerFactory::class,
            LvaLicenceControllers\AddressesController::class => LvaLicenceControllerFactories\AddressesControllerFactory::class,
            LvaLicenceControllers\BusinessDetailsController::class => LvaLicenceControllerFactories\BusinessDetailsControllerFactory::class,
            LvaLicenceControllers\BusinessTypeController::class => LvaLicenceControllerFactories\BusinessTypeControllerFactory::class,
            LvaLicenceControllers\CommunityLicencesController::class => LvaLicenceControllerFactories\CommunityLicencesControllerFactory::class,
            LvaLicenceControllers\ConditionsUndertakingsController::class => LvaLicenceControllerFactories\ConditionsUndertakingsControllerFactory::class,
            LvaLicenceControllers\DiscsController::class => LvaLicenceControllerFactories\DiscsControllerFactory::class,
            LvaLicenceControllers\OperatingCentresController::class => LvaLicenceControllerFactories\OperatingCentresControllerFactory::class,
            LvaLicenceControllers\OverviewController::class => LvaLicenceControllerFactories\OverviewControllerFactory::class,
            LvaLicenceControllers\PeopleController::class => LvaLicenceControllerFactories\PeopleControllerFactory::class,
            LvaLicenceControllers\SafetyController::class => LvaLicenceControllerFactories\SafetyControllerFactory::class,
            LvaLicenceControllers\TaxiPhvController::class => LvaLicenceControllerFactories\TaxiPhvControllerFactory::class,
            LvaLicenceControllers\TrailersController::class => LvaLicenceControllerFactories\TrailersControllerFactory::class,
            LvaLicenceControllers\TransportManagersController::class => LvaLicenceControllerFactories\TransportManagersControllerFactory::class,
            LvaLicenceControllers\TypeOfLicenceController::class => LvaLicenceControllerFactories\TypeOfLicenceControllerFactory::class,
            LvaLicenceControllers\VariationController::class => LvaLicenceControllerFactories\VariationControllerFactory::class,
            LvaLicenceControllers\VehiclesController::class => LvaLicenceControllerFactories\VehiclesControllerFactory::class,
            LvaLicenceControllers\VehiclesPsvController::class => LvaLicenceControllerFactories\VehiclesPsvControllerFactory::class,
            LvaVariationControllers\AddressesController::class => LvaVariationControllerFactories\AddressesControllerFactory::class,
            LvaVariationControllers\BusinessDetailsController::class => LvaVariationControllerFactories\BusinessDetailsControllerFactory::class,
            LvaVariationControllers\BusinessTypeController::class => LvaVariationControllerFactories\BusinessTypeControllerFactory::class,
            LvaVariationControllers\CommunityLicencesController::class => LvaVariationControllerFactories\CommunityLicencesControllerFactory::class,
            LvaVariationControllers\ConditionsUndertakingsController::class => LvaVariationControllerFactories\ConditionsUndertakingsControllerFactory::class,
            LvaVariationControllers\ConvictionsPenaltiesController::class => LvaVariationControllerFactories\ConvictionsPenaltiesControllerFactory::class,
            LvaVariationControllers\DeclarationsInternalController::class => LvaVariationControllerFactories\DeclarationsInternalControllerFactory::class,
            LvaVariationControllers\DiscsController::class => LvaVariationControllerFactories\DiscsControllerFactory::class,
            LvaVariationControllers\FinancialEvidenceController::class => LvaVariationControllerFactories\FinancialEvidenceControllerFactory::class,
            LvaVariationControllers\FinancialHistoryController::class => LvaVariationControllerFactories\FinancialHistoryControllerFactory::class,
            LvaVariationControllers\GrantController::class => LvaVariationControllerFactories\GrantControllerFactory::class,
            LvaVariationControllers\InterimController::class => LvaVariationControllerFactories\InterimControllerFactory::class,
            LvaVariationControllers\LicenceHistoryController::class => LvaVariationControllerFactories\LicenceHistoryControllerFactory::class,
            LvaVariationControllers\OperatingCentresController::class => LvaVariationControllerFactories\OperatingCentresControllerFactory::class,
            LvaVariationControllers\OverviewController::class => LvaVariationControllerFactories\OverviewControllerFactory::class,
            LvaVariationControllers\PeopleController::class => LvaVariationControllerFactories\PeopleControllerFactory::class,
            LvaVariationControllers\PublishController::class => LvaVariationControllerFactories\PublishControllerFactory::class,
            LvaVariationControllers\RefuseController::class => LvaVariationControllerFactories\RefuseControllerFactory::class,
            LvaVariationControllers\ReviveApplicationController::class => LvaVariationControllerFactories\ReviveApplicationControllerFactory::class,
            LvaVariationControllers\SafetyController::class => LvaVariationControllerFactories\SafetyControllerFactory::class,
            LvaVariationControllers\SubmitController::class => LvaVariationControllerFactories\SubmitControllerFactory::class,
            LvaVariationControllers\TaxiPhvController::class => LvaVariationControllerFactories\TaxiPhvControllerFactory::class,
            LvaVariationControllers\TransportManagersController::class => LvaVariationControllerFactories\TransportManagersControllerFactory::class,
            LvaVariationControllers\TypeOfLicenceController::class => LvaVariationControllerFactories\TypeOfLicenceControllerFactory::class,
            LvaVariationControllers\VehiclesController::class => LvaVariationControllerFactories\VehiclesControllerFactory::class,
            LvaVariationControllers\VehiclesDeclarationsController::class => LvaVariationControllerFactories\VehiclesDeclarationsControllerFactory::class,
            LvaVariationControllers\VehiclesPsvController::class => LvaVariationControllerFactories\VehiclesPsvControllerFactory::class,
            LvaVariationControllers\WithdrawController::class => LvaVariationControllerFactories\WithdrawControllerFactory::class,
            Olcs\Controller\IndexController::class => Olcs\Controller\Factory\IndexControllerFactory::class,
            OperatorControllers\OperatorBusinessDetailsController::class => OperatorControllerFactories\OperatorBusinessDetailsControllerFactory::class,
            OperatorControllers\OperatorFeesController::class => OperatorControllerFactories\OperatorFeesControllerFactory::class,
            OperatorControllers\OperatorProcessingTasksController::class => OperatorControllerFactories\OperatorProcessingTasksControllerFactory::class,
            OperatorControllers\UnlicensedBusinessDetailsController::class => OperatorControllerFactories\UnlicensedBusinessDetailsControllerFactory::class,
            OperatorControllers\HistoryController::class => OperatorControllerFactories\HistoryControllerFactory::class,
            OperatorControllers\Cases\UnlicensedCasesOperatorController::class => OperatorControllerFactories\Cases\UnlicensedCasesOperatorControllerFactory::class,
            OperatorControllers\Docs\OperatorDocsController::class => OperatorControllerFactories\Docs\OperatorDocsControllerFactory::class,
            OperatorControllers\OperatorController::class => OperatorControllerFactories\OperatorControllerFactory::class,
            ApplicationControllers\ApplicationController::class => ApplicationControllerFactories\ApplicationControllerFactory::class,
            ApplicationControllers\Docs\ApplicationDocsController::class => ApplicationControllerFactories\Docs\ApplicationDocsControllerFactory::class,
            ApplicationControllers\Fees\ApplicationFeesController::class => ApplicationControllerFactories\Fees\ApplicationFeesControllerFactory::class,
            ApplicationControllers\Processing\ApplicationProcessingOverviewController::class => ApplicationControllerFactories\Processing\ApplicationProcessingOverviewControllerFactory::class,
            ApplicationControllers\Processing\ApplicationProcessingTasksController::class => ApplicationControllerFactories\Processing\ApplicationProcessingTasksControllerFactory::class,
            ApplicationControllers\ApplicationSchedule41Controller::class => ApplicationControllerFactories\ApplicationSchedule41ControllerFactory::class,
            BusControllers\Docs\BusDocsController::class => BusControllerFactories\Docs\BusDocsControllerFactory::class,
            BusControllers\Fees\BusFeesController::class => BusControllerFactories\Fees\BusFeesControllerFactory::class,
            BusControllers\Processing\BusProcessingTaskController::class => BusControllerFactories\Processing\BusProcessingTaskControllerFactory::class,
            CaseControllers\Docs\CaseDocsController::class => CaseControllerFactories\Docs\CaseDocsControllerFactory::class,
            CaseControllers\Processing\TaskController::class => CaseControllerFactories\Processing\TaskControllerFactory::class,
            DocumentControllers\DocumentFinaliseController::class => DocumentControllerFactories\DocumentFinaliseControllerFactory::class,
            DocumentControllers\DocumentGenerationController::class => DocumentControllerFactories\DocumentGenerationControllerFactory::class,
            DocumentControllers\DocumentRelinkController::class => DocumentControllerFactories\DocumentRelinkControllerFactory::class,
            DocumentControllers\DocumentUploadController::class => DocumentControllerFactories\DocumentUploadControllerFactory::class,
        ],
        'aliases' => array(
            'LvaApplication' => Olcs\Controller\Lva\Application\OverviewController::class,
            'LvaApplication/TypeOfLicence' => LvaApplicationControllers\TypeOfLicenceController::class,
            'LvaApplication/BusinessType' => LvaApplicationControllers\BusinessTypeController::class,
            'LvaApplication/BusinessDetails' => LvaApplicationControllers\BusinessDetailsController::class,
            'LvaApplication/Addresses' => LvaApplicationControllers\AddressesController::class,
            'LvaApplication/People' => LvaApplicationControllers\PeopleController::class,
            'LvaApplication/OperatingCentres' => LvaApplicationControllers\OperatingCentresController::class,
            'LvaApplication/FinancialEvidence' => LvaApplicationControllers\FinancialEvidenceController::class,
            'LvaApplication/TransportManagers' => LvaApplicationControllers\TransportManagersController::class,
            'LvaApplication/Vehicles' => LvaApplicationControllers\VehiclesController::class,
            'LvaApplication/VehiclesPsv' => LvaApplicationControllers\VehiclesPsvController::class,
            'LvaApplication/Safety' => LvaApplicationControllers\SafetyController::class,
            'LvaApplication/CommunityLicences' => LvaApplicationControllers\CommunityLicencesController::class,
            'LvaApplication/FinancialHistory' => LvaApplicationControllers\FinancialHistoryController::class,
            'LvaApplication/LicenceHistory' => LvaApplicationControllers\LicenceHistoryController::class,
            'LvaApplication/ConvictionsPenalties' => LvaApplicationControllers\ConvictionsPenaltiesController::class,
            'LvaApplication/TaxiPhv' => LvaApplicationControllers\TaxiPhvController::class,
            'LvaApplication/ConditionsUndertakings' => LvaApplicationControllers\ConditionsUndertakingsController::class,
            'LvaApplication/VehiclesDeclarations' => LvaApplicationControllers\VehiclesDeclarationsController::class,
            'LvaApplication/Review' => \Common\Controller\Lva\ReviewController::class,
            'LvaApplication/Grant' => LvaApplicationControllers\GrantController::class,
            'LvaApplication/Withdraw' => LvaApplicationControllers\WithdrawController::class,
            'LvaApplication/Refuse' => LvaApplicationControllers\RefuseController::class,
            'LvaApplication/NotTakenUp' => LvaApplicationControllers\NotTakenUpController::class,
            'LvaApplication/ReviveApplication' => LvaApplicationControllers\ReviveApplicationController::class,
            'LvaApplication/DeclarationsInternal' => LvaApplicationControllers\DeclarationsInternalController::class,
            'LvaApplication/Publish' => LvaApplicationControllers\PublishController::class,
            'LvaApplication/Submit' => LvaApplicationControllers\SubmitController::class,
            'VariationSchedule41Controller' => 'Olcs\Controller\Variation\VariationSchedule41Controller',
            'LvaLicence' => LvaLicenceControllers\OverviewController::class,
            'LvaLicence/TypeOfLicence' => LvaLicenceControllers\TypeOfLicenceController::class,
            'LvaLicence/BusinessType' => LvaLicenceControllers\BusinessTypeController::class,
            'LvaLicence/BusinessDetails' => LvaLicenceControllers\BusinessDetailsController::class,
            'LvaLicence/Addresses' => LvaLicenceControllers\AddressesController::class,
            'LvaLicence/People' => LvaLicenceControllers\PeopleController::class,
            'LvaLicence/OperatingCentres' => LvaLicenceControllers\OperatingCentresController::class,
            'LvaLicence/TransportManagers' => LvaLicenceControllers\TransportManagersController::class,
            'LvaLicence/Vehicles' => LvaLicenceControllers\VehiclesController::class,
            'LvaLicence/VehiclesPsv' => LvaLicenceControllers\VehiclesPsvController::class,
            'LvaLicence/Safety' => LvaLicenceControllers\SafetyController::class,
            'LvaLicence/CommunityLicences' => LvaLicenceControllers\CommunityLicencesController::class,
            'LvaLicence/TaxiPhv' => LvaLicenceControllers\TaxiPhvController::class,
            'LvaLicence/Discs' => LvaLicenceControllers\DiscsController::class,
            'LvaLicence/ConditionsUndertakings' => LvaLicenceControllers\ConditionsUndertakingsController::class,
            'LvaLicence/Variation' => LvaLicenceControllers\VariationController::class,
            'LvaLicence/Trailers' => LvaLicenceControllers\TrailersController::class,
            'LvaVariation' => Olcs\Controller\Lva\Variation\OverviewController::class,
            'LvaVariation/TypeOfLicence' => LvaVariationControllers\TypeOfLicenceController::class,
            'LvaVariation/BusinessType' => LvaVariationControllers\BusinessTypeController::class,
            'LvaVariation/BusinessDetails' => LvaVariationControllers\BusinessDetailsController::class,
            'LvaVariation/Addresses' => LvaVariationControllers\AddressesController::class,
            'LvaVariation/People' => LvaVariationControllers\PeopleController::class,
            'LvaVariation/OperatingCentres' => LvaVariationControllers\OperatingCentresController::class,
            'LvaVariation/TransportManagers' => LvaVariationControllers\TransportManagersController::class,
            'LvaVariation/Vehicles' => LvaVariationControllers\VehiclesController::class,
            'LvaVariation/VehiclesPsv' => LvaVariationControllers\VehiclesPsvController::class,
            'LvaVariation/Safety' => LvaVariationControllers\SafetyController::class,
            'LvaVariation/CommunityLicences' => LvaVariationControllers\CommunityLicencesController::class,
            'LvaVariation/TaxiPhv' => LvaVariationControllers\TaxiPhvController::class,
            'LvaVariation/Discs' => LvaVariationControllers\DiscsController::class,
            'LvaVariation/ConditionsUndertakings' => LvaVariationControllers\ConditionsUndertakingsController::class,
            'LvaVariation/FinancialEvidence' => LvaVariationControllers\FinancialEvidenceController::class,
            'LvaVariation/FinancialHistory' => LvaVariationControllers\FinancialHistoryController::class,
            'LvaVariation/LicenceHistory' => LvaVariationControllers\LicenceHistoryController::class,
            'LvaVariation/ConvictionsPenalties' => LvaVariationControllers\ConvictionsPenaltiesController::class,
            'LvaVariation/VehiclesDeclarations' => LvaVariationControllers\VehiclesDeclarationsController::class,
            'LvaVariation/Review' => \Common\Controller\Lva\ReviewController::class,
            'LvaVariation/Grant' => LvaVariationControllers\GrantController::class,
            'LvaVariation/Withdraw' => LvaVariationControllers\WithdrawController::class,
            'LvaVariation/Refuse' => LvaVariationControllers\RefuseController::class,
            'LvaVariation/Submit' => Olcs\Controller\Lva\Variation\SubmitController::class,
            'LvaVariation/Revive' => LvaVariationControllers\ReviveApplicationController::class,
            'LvaVariation/DeclarationsInternal' => LvaVariationControllers\DeclarationsInternalController::class,
            'LvaVariation/Publish' => LvaVariationControllers\PublishController::class,
            'OperatorHistoryController' => HistoryController::class,
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
