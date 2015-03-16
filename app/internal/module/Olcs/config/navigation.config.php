<?php

$sectionConfig = new \Common\Service\Data\SectionConfig();
$sections = $sectionConfig->getAllReferences();
$applicationDetailsPages = array();
$licenceDetailsPages = array();
$variationDetailsPages = array();

foreach ($sections as $section) {
    $applicationDetailsPages[] = array(
        'id' => 'application_' . $section,
        'label' => 'section.name.' . $section,
        'route' => 'lva-application/' . $section,
        'use_route_match' => true
    );

    $licenceDetailsPages[] = array(
        'id' => 'licence_' . $section,
        'label' => 'section.name.' . $section,
        'route' => 'lva-licence/' . $section,
        'use_route_match' => true
    );

    $variationDetailsPages[] = array(
        'id' => 'variation_' . $section,
        'label' => 'section.name.' . $section,
        'route' => 'lva-variation/' . $section,
        'use_route_match' => true
    );
}

$nav = array(
    'label' => 'Home',
    'route' => 'dashboard',
    'use_route_match' => false,
    'pages' => array(
        array(
            'id' => 'case',
            'label' => 'Case',
            'route' => 'case',
            'action' => 'redirect',
            'use_route_match' => true,
            'pages' => array(
                array(
                    'id' => 'case_details',
                    'label' => 'Case details',
                    'route' => 'case',
                    'action' => 'redirect',
                    'use_route_match' => true,
                    'pages' => array(
                        array(
                            'id' => 'case_details_overview',
                            'label' => 'Overview',
                            'route' => 'case',
                            'action' => 'details',
                            'use_route_match' => true,
                        ),
                        array(
                            'id' => 'case_details_convictions',
                            'label' => 'Convictions',
                            'route' => 'conviction',
                            'action' => 'index',
                            'use_route_match' => true,
                        ),
                        array(
                            'id' => 'case_details_serious_infringement',
                            'label' => 'Serious infringement',
                            'route' => 'serious_infringement',
                            'action' => 'index',
                            'use_route_match' => true,
                        ),
                        array(
                            'id' => 'case_details_legacy_offence',
                            'label' => 'Legacy offences',
                            'route' => 'offence',
                            'action' => 'index',
                            'use_route_match' => true,
                        ),
                        array(
                            'id' => 'case_details_annual_test_history',
                            'label' => 'Annual test history',
                            'route' => 'case_annual_test_history',
                            'action' => 'index',
                            'use_route_match' => true,
                        ),
                        array(
                            'id' => 'case_details_penalties',
                            'label' => 'ERRU penalties',
                            'route' => 'case_penalty',
                            'action' => 'index',
                            'use_route_match' => true,
                        ),
                        array(
                            'id' => 'case_details_prohibitions',
                            'label' => 'Prohibitions',
                            'route' => 'case_prohibition',
                            'action' => 'index',
                            'use_route_match' => true,
                        ),
                        array(
                            'id' => 'case_details_statements',
                            'label' => 'Statements',
                            'route' => 'case_statement',
                            'action' => 'index',
                            'use_route_match' => true,
                        ),
                        array(
                            'id' => 'case_details_complaints',
                            'label' => 'Complaints',
                            'route' => 'case_complaint',
                            'action' => 'index',
                            'use_route_match' => true,
                        ),
                        array(
                            'id' => 'case_details_conditions_undertakings',
                            'label' => 'Conditions & Undertakings',
                            'route' => 'case_conditions_undertakings',
                            'action' => 'index',
                            'use_route_match' => true,
                        ),
                    )
                ),
                array(
                    'id' => 'case_opposition',
                    'label' => 'Opposition',
                    'route' => 'case_opposition',
                    'action' => 'index',
                    'use_route_match' => true,
                    'pages' => array(
                        array(
                            'id' => 'case_opposition_add',
                            'label' => 'Add Opposition',
                            'route' => 'case_opposition',
                            'action' => 'add',
                            'use_route_match' => true,
                        ),
                        array(
                            'id' => 'case_opposition_edit',
                            'label' => 'Edit Opposition',
                            'route' => 'case_opposition',
                            'action' => 'edit',
                            'use_route_match' => true,
                        ),
                    )
                ),
                array(
                    'id' => 'case_submissions',
                    'label' => 'Submissions',
                    'route' => 'submission',
                    'action' => 'index',
                    'use_route_match' => true,
                    'pages' => array(
                        array(
                            'id' => 'case_submission_list',
                            'label' => 'Submission List',
                            'route' => 'submission',
                            'action' => 'index',
                            'use_route_match' => true,
                        ),
                        array(
                            'id' => 'case_submission_add',
                            'label' => 'Add Submission',
                            'route' => 'submission',
                            'action' => 'add',
                            'use_route_match' => true,
                        ),
                        array(
                            'id' => 'case_submission_edit',
                            'label' => 'Edit Submission',
                            'route' => 'submission',
                            'action' => 'edit',
                            'use_route_match' => true,
                        ),
                    )
                ),
                array(
                    'id' => 'case_hearings_appeals',
                    'label' => 'Hearings & appeals',
                    'route' => 'case_hearing_appeal',
                    'action' => 'index',
                    'use_route_match' => true,
                    'pages' => array(
                        array(
                            'id' => 'case_hearings_appeals_stays',
                            'label' => 'Appeal and stays',
                            'route' => 'case_hearing_appeal',
                            'action' => 'details',
                            'use_route_match' => true,
                        ),
                        array(
                            'id' => 'case_hearings_appeals_public_inquiry',
                            'label' => 'Public Inquiry',
                            'route' => 'case_pi',
                            'action' => 'details',
                            'use_route_match' => true,
                            'pages' => array(
                                array(
                                    'id' => 'case_hearings_appeals_public_inquiry_add',
                                    'label' => 'internal-pi-hearing-add',
                                    'route' => 'case_pi_hearing',
                                    'action' => 'add'
                                ),
                                array(
                                    'id' => 'case_hearings_appeals_public_inquiry_edit',
                                    'label' => 'internal-pi-hearing-edit',
                                    'route' => 'case_pi_hearing',
                                    'action' => 'edit'
                                ),
                            ),
                        ),
                        array(
                            'id' => 'case_hearings_appeals_non_public_inquiry',
                            'label' => 'Non-Public Inquiry',
                            'route' => 'case_non_pi',
                            'action' => 'details',
                            'use_route_match' => true,
                            'pages' => array(
                                array(
                                    'id' => 'case_hearings_appeals_non_public_inquiry_add',
                                    'label' => 'internal-non-pi-hearing-add',
                                    'route' => 'case_pi_hearing',
                                    'action' => 'add'
                                ),
                                array(
                                    'id' => 'case_hearings_appeals_non_public_inquiry_edit',
                                    'label' => 'internal-non-pi-hearing-edit',
                                    'route' => 'case_pi_hearing',
                                    'action' => 'edit'
                                ),
                            ),
                        ),
                        array(
                            'id' => 'case_details_impounding',
                            'label' => 'Impoundings',
                            'route' => 'case_details_impounding',
                            'action' => 'index',
                            'use_route_match' => true,
                        )
                    )
                ),
                array(
                    'id' => 'case_docs_attachments',
                    'label' => 'Docs & attachments',
                    'route' => 'case_licence_docs_attachments',
                    'action' => 'documents',
                    'use_route_match' => true,
                ),
                array(
                    'id' => 'case_processing',
                    'label' => 'Processing',
                    'route' => 'processing',
                    'action' => 'overview',
                    'use_route_match' => true,
                    'pages' => array(
                        array(
                            'id' => 'case_processing_decisions',
                            'label' => 'Decisions',
                            'route' => 'processing_decisions',
                            'action' => 'details',
                            'use_route_match' => true,
                        ),
                        array(
                            'id' => 'case_processing_in_office_revocation',
                            'label' => 'In-office revocation',
                            'route' => 'processing_in_office_revocation',
                            'action' => 'index',
                            'use_route_match' => true,
                        ),
                        array(
                            'id' => 'case_processing_history',
                            'label' => 'History',
                            'route' => 'processing_history',
                            'action' => 'redirect',
                            'use_route_match' => true,
                        ),
                        array(
                            'id' => 'case_processing_tasks',
                            'label' => 'Tasks',
                            'route' => 'case_processing_tasks',
                            'action' => 'index',
                            'use_route_match' => true,
                        ),
                        array(
                            'id' => 'case_processing_notes',
                            'label' => 'Notes',
                            'route' => 'case_processing_notes',
                            'action' => 'index',
                            'use_route_match' => true,
                            'pages' => array(
                                array(
                                    'id' => 'case_processing_notes_add',
                                    'label' => 'case-note-add-label',
                                    'route' => 'case_processing_notes/add-note',
                                    'action' => 'add',

                                ),
                                array(
                                    'id' => 'case_processing_notes_edit',
                                    'label' => 'case-note-edit-label',
                                    'route' => 'case_processing_notes/modify-note',
                                    'action' => 'edit',

                                )
                            )
                        ),
                    )
                )
            ),
        ),
        array(
            'id' => 'case_add',
            'label' => 'Add Case',
            'route' => 'case',
            'action' => 'add',
            'use_route_match' => true
        ),
        array(
            'id' => 'case_edit',
            'label' => 'Edit Case',
            'route' => 'case',
            'action' => 'edit',
            'use_route_match' => true
        ),
        array(
            'label' => 'Search',
            'route' => 'advancedsearch',
            'use_route_match' => true,
            'pages' => array(
                array(
                    'id' => 'licence',
                    'label' => 'Licence',
                    'route' => 'lva-licence',
                    'use_route_match' => true,
                    'pages' => array(
                        array(
                            'id' => 'licence_details_overview',
                            'label' => 'internal-licence-details-breadcrumb',
                            'route' => 'lva-licence',
                            'use_route_match' => true,
                            'pages' => $licenceDetailsPages
                        ),
                        array(
                            'id' => 'licence_bus',
                            'label' => 'Bus registrations',
                            'route' => 'licence/bus',
                            'use_route_match' => true,
                            'pages' => array (
                                array(
                                    'id' => 'licence_bus_details',
                                    'label' => 'internal-licence-bus-details',
                                    'route' => 'licence/bus-details/service',
                                    'use_route_match' => true,
                                    'pages' => array(
                                        array(
                                            'id' => 'licence_bus_details-service',
                                            'label' => 'internal-licence-bus-details-service',
                                            'route' => 'licence/bus-details/service',
                                            'use_route_match' => true,
                                        ),
                                        array(
                                            'id' => 'licence_bus_details-stop',
                                            'label' => 'internal-licence-bus-details-stop',
                                            'route' => 'licence/bus-details/stop',
                                            'use_route_match' => true,
                                        ),
                                        array(
                                            'id' => 'licence_bus_details-ta',
                                            'label' => 'internal-licence-bus-details-ta',
                                            'route' => 'licence/bus-details/ta',
                                            'use_route_match' => true,
                                        ),
                                        array(
                                            'id' => 'licence_bus_details-quality',
                                            'label' => 'internal-licence-bus-details-quality',
                                            'route' => 'licence/bus-details/quality',
                                            'use_route_match' => true,
                                        )
                                    )
                                ),
                                array(
                                    'id' => 'licence_bus_short',
                                    'label' => 'internal-licence-bus-short',
                                    'route' => 'licence/bus-short',
                                    'use_route_match' => true
                                ),
                                array(
                                    'id' => 'licence_bus_register_service',
                                    'label' => 'internal-licence-register-service',
                                    'route' => 'licence/bus-register-service',
                                    'use_route_match' => true
                                ),
                                array(
                                    'id' => 'licence_bus_docs',
                                    'label' => 'internal-licence-bus-docs',
                                    'route' => 'licence/bus-docs',
                                    'use_route_match' => true,
                                    'pages' => array(
                                        array(
                                            'id' => 'licence_bus_docs',
                                            'label' => 'internal-licence-bus-docs',
                                            'route' => 'licence/bus-docs',
                                            'use_route_match' => true,
                                        ),
                                    )
                                ),
                                array(
                                    'id' => 'licence_bus_processing',
                                    'label' => 'internal-licence-bus-processing',
                                    'route' => 'licence/bus-processing',
                                    'use_route_match' => true,
                                    'pages' => array(
                                        array(
                                            'id' => 'licence_bus_processing_decisions',
                                            'label' => 'internal-licence-bus-processing-decisions',
                                            'route' => 'licence/bus-processing/decisions',
                                            'use_route_match' => true,
                                        ),
                                        array(
                                            'id' => 'licence_bus_processing_notes',
                                            'label' => 'internal-licence-bus-processing-notes',
                                            'route' => 'licence/bus-processing/notes',
                                            'use_route_match' => true,
                                            'pages' => array(
                                                array(
                                                    'id' => 'licence_bus_processing_notes_add',
                                                    'label' => 'internal-licence-bus-processing-notes-add',
                                                    'route' => 'licence/bus-processing/add-note',
                                                    'use_route_match' => true
                                                ),
                                                array(
                                                    'id' => 'licence_bus_processing_notes_modify',
                                                    'label' => 'internal-licence-bus-processing-notes-modify',
                                                    'route' => 'licence/bus-processing/modify-note',
                                                    'use_route_match' => true
                                                )
                                            )
                                        ),
                                        array(
                                            'id' => 'licence_bus_processing_registration_history',
                                            'label' => 'internal-licence-bus-processing-registration-history',
                                            'route' => 'licence/bus-processing/registration-history',
                                            'use_route_match' => true,
                                        ),
                                        array(
                                            'id' => 'licence_bus_processing_tasks',
                                            'label' => 'internal-licence-bus-processing-tasks',
                                            'route' => 'licence/bus-processing/tasks',
                                            'use_route_match' => true,
                                        ),
                                    )
                                ),
                                array(
                                    'id' => 'licence_bus_fees',
                                    'label' => 'internal-licence-bus-fees',
                                    'route' => 'licence/bus-fees',
                                    'use_route_match' => true,
                                    'pages' => array(
                                    )
                                ),
                            )
                        ),
                        array(
                            'id' => 'licence/cases',
                            'label' => 'Cases',
                            'route' => 'licence/cases',
                            'action' => 'cases',
                            'use_route_match' => true
                        ),
                        array(
                            'id' => 'licence_opposition',
                            'label' => 'Opposition',
                            'route' => 'licence/opposition',
                            'use_route_match' => true
                        ),
                        array(
                            'id' => 'licence_documents',
                            'label' => 'Docs & attachments',
                            'route' => 'licence/documents',
                            'use_route_match' => true
                        ),
                        array(
                            'id' => 'licence_processing',
                            'label' => 'Processing',
                            'route' => 'licence/processing',
                            'use_route_match' => true,
                            'pages' => array(
                                array(
                                    'id' => 'licence_processing_publications',
                                    'label' => 'internal-licence-processing-publications',
                                    'route' => 'licence/processing/publications',
                                    'use_route_match' => true,
                                ),
                                array(
                                    'id' => 'licence_processing_tasks',
                                    'label' => 'internal-licence-processing-tasks',
                                    'route' => 'licence/processing/tasks',
                                    'use_route_match' => true,
                                ),
                                array(
                                    'id' => 'licence_processing_notes',
                                    'label' => 'internal-licence-processing-notes',
                                    'route' => 'licence/processing/notes',
                                    'use_route_match' => true,
                                    'pages' => array(
                                        array(
                                            'id' => 'licence_processing_notes_add',
                                            'label' => 'internal-licence-processing-notes-add',
                                            'route' => 'licence/processing/add-note',
                                            'use_route_match' => true
                                        ),
                                        array(
                                            'id' => 'licence_processing_notes_modify',
                                            'label' => 'internal-licence-processing-notes-modify',
                                            'route' => 'licence/processing/modify-note',
                                            'use_route_match' => true
                                        )
                                    )
                                ),
                            )
                        ),
                        array(
                            'id' => 'licence_fees',
                            'label' => 'Fees',
                            'route' => 'licence/fees',
                            'use_route_match' => true
                        ),
                        array(
                            'id' => 'licence_processing_event-history',
                            'label' => 'History',
                            'route' => 'licence/event-history',
                            'use_route_match' => true,
                        ),
                    )
                ),
                array(
                    'id' => 'transport_manager',
                    'label' => 'internal-navigation-transport-manager',
                    'route' => 'transport-manager',
                    'use_route_match' => true,
                    'pages' => array(
                        array(
                            'id' => 'transport_manager_details',
                            'label' => 'internal-navigation-transport-manager-details',
                            'route' => 'transport-manager/details',
                            'use_route_match' => true,
                            'pages' => array (
                                array(
                                    'id' => 'transport_manager_details_details',
                                    'label' => 'internal-navigation-transport-manager-details-details',
                                    'route' => 'transport-manager/details/details',
                                    'use_route_match' => true
                                ),
                                array(
                                    'id' => 'transport_manager_details_competences',
                                    'label' => 'internal-navigation-transport-manager-details-competences',
                                    'route' => 'transport-manager/details/competences',
                                    'use_route_match' => true,
                                    'params' => [
                                        'action' => null,
                                        'id' => null
                                    ]
                                ),
                                array(
                                    'id' => 'transport_manager_details_responsibility',
                                    'label' => 'internal-navigation-transport-manager-details-responsibilities',
                                    'route' => 'transport-manager/details/responsibilities',
                                    'use_route_match' => true,
                                    'params' => [
                                        'action' => null,
                                        'id' => null
                                    ]
                                ),
                                array(
                                    'id' => 'transport_manager_details_employment',
                                    'label' => 'internal-navigation-transport-manager-details-employment',
                                    'route' => 'transport-manager/details/employment',
                                    'use_route_match' => true,
                                    'params' => [
                                        'action' => null,
                                        'id' => null
                                    ]
                                ),
                                array(
                                    'id' => 'transport_manager_details_previous_history',
                                    'label' => 'internal-navigation-transport-manager-previous-history',
                                    'route' => 'transport-manager/details/previous-history',
                                    'use_route_match' => true,
                                    'params' => [
                                        'action' => null,
                                        'id' => null
                                    ]
                                ),
                            )
                        ),
                        array(
                            'id' => 'transport_manager_cases',
                            'label' => 'internal-navigation-transport-manager-cases',
                            'route' => 'transport-manager/cases',
                            'use_route_match' => true,
                        ),
                        array(
                            'id' => 'transport_manager_documents',
                            'label' => 'internal-navigation-transport-manager-documents',
                            'route' => 'transport-manager/documents',
                            'use_route_match' => true,
                        ),
                        array(
                            'id' => 'transport_manager_processing',
                            'label' => 'internal-navigation-transport-manager-processing',
                            'route' => 'transport-manager/processing',
                            'use_route_match' => true,
                            'pages' => array (
                                array(
                                    'id' => 'transport_manager_processing_notes',
                                    'label' => 'internal-navigation-transport-manager-processing-notes',
                                    'route' => 'transport-manager/processing/notes',
                                    'use_route_match' => true,
                                ),
                                array(
                                    'id' => 'transport_manager_processing_tasks',
                                    'label' => 'internal-navigation-transport-manager-processing-tasks',
                                    'route' => 'transport-manager/processing/tasks',
                                    'use_route_match' => true,
                                ),
                                array(
                                    'id' => 'transport_manager_processing_decisions',
                                    'label' => 'internal-navigation-transport-manager-processing-decisions',
                                    'route' => 'transport-manager/processing/decisions',
                                    'use_route_match' => true,
                                ),
                                array(
                                    'id' => 'transport_manager_processing_history',
                                    'label' => 'internal-navigation-transport-manager-processing-history',
                                    'route' => 'transport-manager/processing/history',
                                    'use_route_match' => true,
                                ),
                                array(
                                    'id' => 'transport_manager_processing_publications',
                                    'label' => 'internal-navigation-transport-manager-processing-publications',
                                    'route' => 'transport-manager/processing/publication',
                                    'use_route_match' => true,
                                ),
                            )
                        ),
                    )
                ),
                array(
                    'id' => 'operator',
                    'label' => 'internal-navigation-operator',
                    'route' => 'operator',
                    'use_route_match' => true,
                    'pages' => array(
                        array(
                            'id' => 'operator_business_details',
                            'label' => 'internal-navigation-operator-business_details',
                            'route' => 'operator/business-details',
                            'use_route_match' => true,
                        ),
                        array(
                            'id' => 'operator_people',
                            'label' => 'internal-navigation-operator-people',
                            'route' => 'operator/people',
                            'use_route_match' => true,
                        ),
                        array(
                            'id' => 'operator_licences_applications',
                            'label' => 'internal-navigation-operator-licences_applications',
                            'route' => 'operator/licences-applications',
                            'use_route_match' => true,
                        ),
                    )
                ),
            )
        ),
        array(
            'id' => 'create_application',
            'label' => 'Create application',
            'route' => 'create_application',
            'use_route_match' => true
        ),
        'application' => array(
            'id' => 'application',
            'label' => 'Application',
            'route' => 'lva-application',
            'use_route_match' => true,
            'pages' => array(
                array(
                    'id' => 'application_details',
                    'label' => 'Application details',
                    'route' => 'lva-application',
                    'use_route_match' => true,
                    'pages' => array_merge(
                        $applicationDetailsPages,
                        array(
                            array(
                                'id' => 'grant_application',
                                'label' => 'Grant application',
                                'route' => 'lva-application/grant',
                                'use_route_match' => true
                            ),
                            array(
                                'id' => 'undogrant_application',
                                'label' => 'Undo grant application',
                                'route' => 'lva-application/undo-grant',
                                'use_route_match' => true
                            )
                        )
                    )
                ),
                array(
                    'id' => 'application_case',
                    'label' => 'Cases',
                    'route' => 'lva-application/case',
                    'use_route_match' => true
                ),
                array(
                    'id' => 'application_environmental',
                    'label' => 'Environmental',
                    'route' => 'lva-application/environmental',
                    'use_route_match' => true
                ),
                array(
                    'id' => 'application_document',
                    'label' => 'Docs & attachments',
                    'route' => 'lva-application/documents',
                    'use_route_match' => true
                ),
                array(
                    'id' => 'application_processing',
                    'label' => 'Processing',
                    'route' => 'lva-application/processing',
                    'use_route_match' => true,
                    'pages' => array(
                        array(
                            'id' => 'application_processing_tasks',
                            'label' => 'internal-application-processing-tasks',
                            'route' => 'lva-application/processing/tasks',
                            'use_route_match' => true,
                        ),
                        array(
                            'id' => 'application_processing_notes',
                            'label' => 'internal-application-processing-notes',
                            'route' => 'lva-application/processing/notes',
                            'use_route_match' => true,
                            'pages' => array(
                                array(
                                    'id' => 'application_processing_notes_add',
                                    'label' => 'internal-application-processing-notes-add',
                                    'route' => 'lva-application/processing/add-note',
                                    'use_route_match' => true
                                ),
                                array(
                                    'id' => 'application_processing_notes_modify',
                                    'label' => 'internal-application-processing-notes-modify',
                                    'route' => 'lva-application/processing/modify-note',
                                    'use_route_match' => true
                                )
                            )
                        )
                    )
                ),
                array(
                    'id' => 'application_fee',
                    'label' => 'Fees',
                    'route' => 'lva-application/fees',
                    'use_route_match' => true
                )
            )
        ),
        'variation' => array(
            'id' => 'variation',
            'label' => 'Application',
            'route' => 'lva-variation',
            'use_route_match' => true,
            'pages' => array(
                array(
                    'id' => 'variation_details',
                    'label' => 'Application details',
                    'route' => 'lva-variation',
                    'use_route_match' => true,
                    'pages' => array_merge(
                        $variationDetailsPages,
                        array(
                            array(
                                'id' => 'grant_variation',
                                'label' => 'Grant application',
                                'route' => 'lva-variation/grant',
                                'use_route_match' => true
                            ),
                        )
                    )
                )
            )
        )
    )
);

// @NOTE Here we dynamically attach all application navigation items to the variation node
$applicationPages = $nav['pages']['application']['pages'];
array_shift($applicationPages);
$nav['pages']['variation']['pages'] = array_merge($nav['pages']['variation']['pages'], $applicationPages);

return $nav;
