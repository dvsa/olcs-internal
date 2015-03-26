<?php
return array(
    'id'    => 'root',
    'label' => 'Right sidebar',
    'route' => 'dashboard',
    'use_route_match' => false,
    'pages' => array(
        array(
            'id' => 'licence',
            'label' => 'Licence',
            'route' => 'dashboard',
            'use_route_match' => true,
            'pages' => array(
                array(
                    'id' => 'licence-quick-actions',
                    'label' => 'Quick actions',
                    'route' => 'dashboard',
                    'use_route_match' => true,
                    'pages' => array(
                        array(
                            'id' => 'licence-quick-actions-create-variation',
                            'label' => 'Create variation',
                            'route' => 'lva-licence/variation',
                            'use_route_match' => true,
                            'class' => 'action--secondary js-modal-ajax'
                        ),
                        array(
                            'id' => 'licence-quick-actions-print-licence',
                            'label' => 'Print licence',
                            'route' => 'print_licence',
                            'use_route_match' => true
                        )
                    ),
                ),
                array(
                    'id' => 'licence-decisions',
                    'label' => 'Decisions',
                    'route' => 'dashboard',
                    'use_route_match' => true,
                    'pages' => array(
                        array(
                            'id' => 'licence-decisions-curtail',
                            'label' => 'Curtail',
                            'route' => 'licence/active-licence-check',
                            'use_route_match' => true,
                            'params' => [
                                'decision' => 'curtail',
                            ],
                            'class' => 'action--secondary js-modal-ajax'
                        ),
                        array(
                            'id' => 'licence-decisions-revoke',
                            'label' => 'Revoke',
                            'route' => 'licence/active-licence-check',
                            'use_route_match' => true,
                            'params' => [
                                'decision' => 'revoke',
                            ],
                            'class' => 'action--secondary js-modal-ajax'
                        ),
                        array(
                            'id' => 'licence-decisions-suspend',
                            'label' => 'Suspend',
                            'route' => 'licence/active-licence-check',
                            'use_route_match' => true,
                            'params' => [
                                'decision' => 'suspend',
                            ],
                            'class' => 'action--secondary js-modal-ajax'
                        ),
                        array(
                            'id' => 'licence-decisions-surrender',
                            'label' => 'Surrender',
                            'route' => 'licence/active-licence-check',
                            'use_route_match' => true,
                            'params' => [
                                'decision' => 'surrender',
                            ],
                            'class' => 'action--secondary js-modal-ajax'
                        ),
                        array(
                            'id' => 'licence-decisions-terminate',
                            'label' => 'Terminate',
                            'route' => 'licence/active-licence-check',
                            'use_route_match' => true,
                            'params' => [
                                'decision' => 'terminate',
                            ],
                            'class' => 'action--secondary js-modal-ajax'
                        ),
                    ),
                ),
            ),
        ),
        array(
            'id' => 'case',
            'label' => 'Case',
            'route' => 'dashboard',
            'use_route_match' => true,
            'pages' => array(
                array(
                    'id' => 'case-quick-actions',
                    'label' => 'Quick actions',
                    'route' => 'dashboard',
                    'use_route_match' => true,
                    'pages' => array(
                        array(
                            'id' => 'case-quick-actions-create-statement',
                            'label' => 'Create statement',
                            'route' => 'case_statement',
                            'action' => 'add',
                            'use_route_match' => true
                        )
                    ),
                ),
                array(
                    'id' => 'case-decisions-licence',
                    'label' => 'Decisions',
                    'route' => 'dashboard',
                    'use_route_match' => true,
                    'pages' => array(
                        array(
                            'id' => 'case-decisions-licence-surrender',
                            'label' => 'Surrender',
                            'route' => 'dashboard',
                            'use_route_match' => true,
                            'caseType' => 'licence',
                        ),
                        array(
                            'id' => 'case-decisions-licence-curtail',
                            'label' => 'Curtail',
                            'route' => 'dashboard',
                            'use_route_match' => true,
                            'caseType' => 'licence',
                        ),
                        array(
                            'id' => 'case-decisions-licence-revoke',
                            'label' => 'Revoke',
                            'route' => 'dashboard',
                            'use_route_match' => true,
                            'caseType' => 'licence',
                        )
                    ),
                ),
                array(
                    'id' => 'case-decisions-transport-manager',
                    'label' => 'Decisions',
                    'route' => 'dashboard',
                    'use_route_match' => true,
                    'pages' => array(
                        array(
                            'id' => 'case-decisions-transport-manager-repute-not-lost',
                            'label' => 'Repute not lost',
                            'route' => 'processing_decisions',
                            'use_route_match' => true,
                            'params' => [
                                'action' => 'add',
                                'decision' => 'tm_decision_rnl',
                            ],
                            'caseType' => 'tm',
                            'class' => 'action--secondary js-modal-ajax'
                        ),
                        array(
                            'id' => 'case-decisions-transport-manager-declare-unfit',
                            'label' => 'Declare unfit',
                            'route' => 'processing_decisions',
                            'use_route_match' => true,
                            'params' => [
                                'action' => 'add',
                                'decision' => 'tm_decision_rl',
                            ],
                            'caseType' => 'tm',
                            'class' => 'action--secondary js-modal-ajax'
                        ),
                        array(
                            'id' => 'case-decisions-transport-manager-no-further-action',
                            'label' => 'No further action',
                            'route' => 'processing_decisions',
                            'use_route_match' => true,
                            'params' => [
                                'action' => 'add',
                                'decision' => 'tm_decision_noa',
                            ],
                            'caseType' => 'tm',
                            'class' => 'action--secondary js-modal-ajax'
                        ),
                    ),
                ),
            ),
        ),
        array(
            'id' => 'bus-registration',
            'label' => 'Bus Registration',
            'route' => 'dashboard',
            'use_route_match' => true,
            'pages' => array(
                array(
                    'id' => 'bus-registration-quick-actions',
                    'label' => 'Quick actions',
                    'route' => 'dashboard',
                    'use_route_match' => true,
                    'pages' => array(
                        array(
                            'id' => 'bus-registration-quick-actions-create-variation',
                            'label' => 'Create variation',
                            'route' => 'licence/bus/create_variation',
                            'use_route_match' => true,
                            'class' => 'action--secondary js-modal-ajax'
                        ),
                        array(
                            'id' => 'bus-registration-quick-actions-create-cancellation',
                            'label' => 'Create cancellation',
                            'route' => 'licence/bus/create_cancellation',
                            'use_route_match' => true,
                            'class' => 'action--secondary js-modal-ajax'
                        ),
                        array(
                            'id' => 'bus-registration-quick-actions-request-new-route-map',
                            'label' => 'Request new route map',
                            'route' => 'dashboard',
                            'use_route_match' => true,
                            'class' => 'action--secondary js-modal-ajax'
                        ),
                        array(
                            'id' => 'bus-registration-quick-actions-republish',
                            'label' => 'Republish',
                            'route' => 'licence/bus-processing/decisions',
                            'use_route_match' => true,
                            'params' => [
                                'action' => 'republish'
                            ],
                            'class' => 'action--secondary js-modal-ajax'
                        ),
                        array(
                            'id' => 'bus-registration-quick-actions-request-withdrawn',
                            'label' => 'Withdraw',
                            'route' => 'licence/bus-processing/decisions',
                            'use_route_match' => true,
                            'params' => [
                                'action' => 'status',
                                'status' => 'breg_s_withdrawn'
                            ],
                            'class' => 'action--secondary js-modal-ajax'
                        )
                    ),
                ),
                array(
                    'id' => 'bus-registration-decisions',
                    'label' => 'Decisions',
                    'route' => 'dashboard',
                    'use_route_match' => true,
                    'pages' => array(
                        array(
                            'id' => 'bus-registration-decisions-grant',
                            'label' => 'Grant',
                            'route' => 'licence/bus-processing/decisions',
                            'use_route_match' => true,
                            'params' => [
                                'action' => 'grant'
                            ]
                        ),
                        array(
                            'id' => 'bus-registration-decisions-refuse-by-short-notice',
                            'label' => 'Refuse by short notice',
                            'route' => 'licence/bus-processing/decisions',
                            'use_route_match' => true,
                            'params' => [
                                'action' => 'status',
                                'status' => 'sn_refused'
                            ],
                            'class' => 'action--secondary js-modal-ajax'
                        ),
                        array(
                            'id' => 'bus-registration-decisions-refuse',
                            'label' => 'Refuse',
                            'route' => 'licence/bus-processing/decisions',
                            'use_route_match' => true,
                            'params' => [
                                'action' => 'status',
                                'status' => 'breg_s_refused'
                            ],
                            'class' => 'action--secondary js-modal-ajax'
                        ),
                        array(
                            'id' => 'bus-registration-decisions-admin-cancel',
                            'label' => 'Admin cancel',
                            'route' => 'licence/bus-processing/decisions',
                            'use_route_match' => true,
                            'params' => [
                                'action' => 'status',
                                'status' => 'breg_s_admin'
                            ],
                            'class' => 'action--secondary js-modal-ajax'
                        ),
                        array(
                            'id' => 'bus-registration-decisions-reset-registration',
                            'label' => 'Reset',
                            'route' => 'licence/bus-processing/decisions',
                            'use_route_match' => true,
                            'params' => [
                                'action' => 'reset'
                            ],
                            'class' => 'action--secondary js-modal-ajax'
                        ),
                    ),
                ),
            ),
        ),
        array(
            'id' => 'application',
            'label' => 'Application',
            'route' => 'dashboard',
            'use_route_match' => true,
            'pages' => array(
                array(
                    'id' => 'application-quick-actions',
                    'label' => 'Quick actions',
                    'route' => 'dashboard',
                    'use_route_match' => true,
                    'pages' => array(
                        array(
                            'id' => 'application-quick-actions-view-full-application',
                            'label' => 'View full application',
                            'route' => 'lva-application/review',
                            'use_route_match' => true,
                            'class' => 'action--secondary',
                            'target' => '_blank',
                        ),
                        array(
                            'id' => 'application-quick-actions-generate-publication',
                            'label' => 'Generate publication',
                            'route' => 'dashboard',
                        )
                    ),
                ),
                array(
                    'id' => 'application-decisions',
                    'label' => 'Decisions',
                    'route' => 'dashboard',
                    'use_route_match' => true,
                    'pages' => array(
                        array(
                            'id' => 'application-decisions-grant',
                            'label' => 'Grant application',
                            'route' => 'lva-application/grant',
                            'use_route_match' => true,
                            'class' => 'action--secondary js-modal-ajax'
                        ),
                        array(
                            'id' => 'application-decisions-undo-grant',
                            'label' => 'Undo grant application',
                            'route' => 'lva-application/undo-grant',
                            'use_route_match' => true,
                            'class' => 'action--secondary js-modal-ajax'
                        ),
                        array(
                            'id' => 'application-decisions-not-taken-up',
                            'label' => 'Not taken up',
                            'route' => 'lva-application/not-taken-up',
                            'use_route_match' => true,
                            'class' => 'action--secondary js-modal-ajax'
                        ),
                        array(
                            'id' => 'application-decisions-undo-not-taken-up',
                            'label' => 'Undo not taken up',
                            'route' => 'lva-application/undo-not-taken-up',
                            'use_route_match' => true,
                            'class' => 'action--secondary js-modal-ajax'
                        ),
                        array(
                            'id' => 'application-decisions-withdraw',
                            'label' => 'Withdraw application',
                            'route' => 'lva-application/withdraw',
                            'use_route_match' => true,
                            'class' => 'action--secondary js-modal-ajax'
                        ),
                        array(
                            'id' => 'application-decisions-refuse',
                            'label' => 'Refuse application',
                            'route' => 'lva-application/refuse',
                            'use_route_match' => true,
                            'class' => 'action--secondary js-modal-ajax'
                        ),
                    ),
                ),
            ),
        ),
    ),
);
