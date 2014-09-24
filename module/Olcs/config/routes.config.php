<?php

list($allRoutes, $controllers, $journeys) = include(
    __DIR__ . '/../../../vendor/olcs/OlcsCommon/Common/config/journeys.config.php'
);

// @NOTE unfortunately because the application routes are generated automagically, we need to add the other child routes
// here
$allRoutes['Application']['child_routes'] = array_merge(
    $allRoutes['Application']['child_routes'],
    array(
        'case' => array(
            'type' => 'segment',
            'options' => array(
                'route' => 'case/',
                'defaults' => array(
                    'controller' => 'ApplicationController',
                    'action' => 'case'
                )
            )
        ),
        'environmental' => array(
            'type' => 'segment',
            'options' => array(
                'route' => 'environmental/',
                'defaults' => array(
                    'controller' => 'ApplicationController',
                    'action' => 'environmental'
                )
            )
        ),
        'document' => array(
            'type' => 'segment',
            'options' => array(
                'route' => 'document/',
                'defaults' => array(
                    'controller' => 'ApplicationController',
                    'action' => 'document'
                )
            )
        ),
        'processing' => array(
            'type' => 'segment',
            'options' => array(
                'route' => 'processing/',
                'defaults' => array(
                    'controller' => 'ApplicationController',
                    'action' => 'processing'
                )
            )
        ),
        'fee' => array(
            'type' => 'segment',
            'options' => array(
                'route' => 'fee/',
                'defaults' => array(
                    'controller' => 'ApplicationController',
                    'action' => 'fee'
                )
            )
        )
    )
);

return array_merge(
    $allRoutes,
    [
        'dashboard' => [
            'type' => 'Literal',
            'options' => [
                'route' => '/',
                'defaults' => [
                    'controller' => 'IndexController',
                    'action' => 'index',
                ]
            ]
        ],
        'styleguide' => [
            'type' => 'segment',
            'options' => [
                'route' => '/styleguide[/:action]',
                'defaults' => [
                    'controller' => 'IndexController',
                ]
            ]
        ],
        'operators' => [
            'type' => 'Literal',
            'options' => [
                'route' => '/search/operators',
                'defaults' => [
                    'controller' => 'SearchController',
                    'action' => 'operator'
                ]
            ],
            'may_terminate' => true,
            'child_routes' => [
                'operators-params' => [
                    'type' => 'wildcard',
                    'options' => [
                        'key_value_delimiter' => '/',
                        'param_delimiter' => '/',
                        'defaults' => [
                            'page' => 1,
                            'limit' => 10
                        ]
                    ]
                ]
            ]
        ],
        'search' => [
            'type' => 'segment',
            'options' => [
                'route' => '/search',
                'defaults' => [
                    'controller' => 'SearchController',
                    'action' => 'index'
                ]
            ]
        ],
        'task_action' => [
            'type' => 'segment',
            'options' => [
                'route' => '/task[/:action][/:task][/type/:type/:typeId]',
                'constraints' => [
                    'task' => '[0-9-]+',
                    'type' => '[a-z]+',
                    'typeId' => '[0-9]+'
                ],
                'defaults' => [
                    'controller' => 'TaskController',
                ]
            ],
            'may_terminate' => true,
        ],

        // These routes are for the licence page

        'licence' => [
            'type' => 'segment',
            'options' => [
                'route' => '/licence/:licence',
                'constraints' => [
                    'licence' => '[0-9]+'
                ],
                'defaults' => [
                    'controller' => 'LicenceController',
                    'action' => 'index-jump',
                ]
            ],
            'may_terminate' => true,
            'child_routes' => [
                'details' => [
                    'type' => 'literal',
                    'options' => [
                        'route' => '/details'
                    ],
                    'may_terminate' => false,
                    'child_routes' => [
                        'overview' => [
                            'type' => 'literal',
                            'options' => [
                                'route' => '/overview',
                                'defaults' => [
                                    'controller' => 'LicenceDetailsOverviewController',
                                    'action' => 'index',
                                ]
                            ]
                        ],
                        'type_of_licence' => [
                            'type' => 'literal',
                            'options' => [
                                'route' => '/type_of_licence',
                                'defaults' => [
                                    'controller' => 'LicenceDetailsTypeOfLicenceController',
                                    'action' => 'index',
                                ]
                            ]
                        ],
                        'business_details' => [
                            'type' => 'literal',
                            'options' => [
                                'route' => '/business_details',
                                'defaults' => [
                                    'controller' => 'LicenceDetailsBusinessDetailsController',
                                    'action' => 'index',
                                ]
                            ]
                        ],
                        'address' => [
                            'type' => 'literal',
                            'options' => [
                                'route' => '/addresses',
                                'defaults' => [
                                    'controller' => 'LicenceDetailsAddressController',
                                    'action' => 'index',
                                ]
                            ]
                        ],
                        'people' => [
                            'type' => 'literal',
                            'options' => [
                                'route' => '/people',
                                'defaults' => [
                                    'controller' => 'LicenceDetailsPeopleController',
                                    'action' => 'index',
                                ]
                            ]
                        ],
                        'operating_centre' => [
                            'type' => 'literal',
                            'options' => [
                                'route' => '/operating_centres',
                                'defaults' => [
                                    'controller' => 'LicenceDetailsOperatingCentreController',
                                    'action' => 'index',
                                ]
                            ]
                        ],
                        'transport_manager' => [
                            'type' => 'literal',
                            'options' => [
                                'route' => '/transport_managers',
                                'defaults' => [
                                    'controller' => 'LicenceDetailsTransportManagerController',
                                    'action' => 'index',
                                ]
                            ]
                        ],
                        'vehicle' => [
                            'type' => 'segment',
                            'options' => [
                                'route' => '/vehicles[/:action][/:id]',
                                'contraints' => [
                                    'id' => '[0-9]+'
                                ],
                                'defaults' => [
                                    'controller' => 'LicenceDetailsVehicleController',
                                    'action' => 'index',
                                ]
                            ]
                        ],
                        'vehicle_psv' => [
                            'type' => 'segment',
                            'options' => [
                                'route' => '/vehicles_psv[/:action][/:id]',
                                'contraints' => [
                                    'id' => '[0-9]+'
                                ],
                                'defaults' => [
                                    'controller' => 'LicenceDetailsVehiclePsvController',
                                    'action' => 'index',
                                ]
                            ]
                        ],
                        'safety' => [
                            'type' => 'segment',
                            'options' => [
                                'route' => '/safety[/:action][/:id]',
                                'contraints' => [
                                    'id' => '[0-9]+'
                                ],
                                'defaults' => [
                                    'controller' => 'LicenceDetailsSafetyController',
                                    'action' => 'index',
                                ]
                            ]
                        ],
                        'condition_undertaking' => [
                            'type' => 'literal',
                            'options' => [
                                'route' => '/condition_undertaking',
                                'defaults' => [
                                    'controller' => 'LicenceDetailsConditionUndertakingController',
                                    'action' => 'index',
                                ]
                            ]
                        ],
                        'taxi_phv' => [
                            'type' => 'literal',
                            'options' => [
                                'route' => '/taxi_phv',
                                'defaults' => [
                                    'controller' => 'LicenceDetailsTaxiPhvController',
                                    'action' => 'index',
                                ]
                            ]
                        ]
                    ]
                ],
                'bus' => [
                    'type' => 'literal',
                    'options' => [
                        'route' => '/bus',
                        'defaults' => [
                            'controller' => 'LicenceController',
                            'action' => 'bus',
                        ]
                    ],
                    'may_terminate' => true,
                ],
                'bus-details' => [
                    'type' => 'segment',
                    'options' => [
                        'route' => '/bus/:busRegId/details',
                        'defaults' => [
                            'controller' => 'BusDetailsController',
                            'action' => 'index',
                        ]
                    ],
                    'may_terminate' => true,
                    'child_routes' => [
                        'service' => [
                            'type' => 'literal',
                            'options' => [
                                'route' => '/service',
                                'defaults' => [
                                    'controller' => 'BusDetailsServiceController',
                                    'action' => 'index',
                                ]
                            ],
                        ],
                        'stop' => [
                            'type' => 'literal',
                            'options' => [
                                'route' => '/stop',
                                'defaults' => [
                                    'controller' => 'BusDetailsStopController',
                                    'action' => 'index',
                                ]
                            ],
                        ],
                        'ta' => [
                            'type' => 'literal',
                            'options' => [
                                'route' => '/ta',
                                'defaults' => [
                                    'controller' => 'BusDetailsTaController',
                                    'action' => 'index',
                                ]
                            ],
                        ],
                        'quality' => [
                            'type' => 'literal',
                            'options' => [
                                'route' => '/quality',
                                'defaults' => [
                                    'controller' => 'BusDetailsQualityController',
                                    'action' => 'index',
                                ]
                            ],
                        ]
                    ]
                ],
                'bus-short' => [
                    'type' => 'segment',
                    'options' => [
                        'route' => '/bus/:busRegId/short',
                        'defaults' => [
                            'controller' => 'BusShortController',
                            'action' => 'index',
                        ]
                    ],
                    'may_terminate' => true,
                    'child_routes' => [
                        'placeholder' => [
                            'type' => 'literal',
                            'options' => [
                                'route' => '/placeholder',
                                'defaults' => [
                                    'controller' => 'BusShortPlaceholderController',
                                    'action' => 'index',
                                ]
                            ],
                        ],
                    ]
                ],
                'bus-route' => [
                    'type' => 'segment',
                    'options' => [
                        'route' => '/bus/:busRegId/route',
                        'defaults' => [
                            'controller' => 'BusRouteController',
                            'action' => 'index',
                        ]
                    ],
                    'may_terminate' => true,
                    'child_routes' => [
                        'placeholder' => [
                            'type' => 'literal',
                            'options' => [
                                'route' => '/placeholder',
                                'defaults' => [
                                    'controller' => 'BusRoutePlaceholderController',
                                    'action' => 'index',
                                ]
                            ],
                        ],
                    ]
                ],
                'bus-trc' => [
                    'type' => 'segment',
                    'options' => [
                        'route' => '/bus/:busRegId/trc',
                        'defaults' => [
                            'controller' => 'BusTrcController',
                            'action' => 'index',
                        ]
                    ],
                    'may_terminate' => true,
                    'child_routes' => [
                        'placeholder' => [
                            'type' => 'literal',
                            'options' => [
                                'route' => '/placeholder',
                                'defaults' => [
                                    'controller' => 'BusTrcPlaceholderController',
                                    'action' => 'index',
                                ]
                            ],
                        ],
                    ]
                ],
                'bus-docs' => [
                    'type' => 'segment',
                    'options' => [
                        'route' => '/bus/:busRegId/docs',
                        'defaults' => [
                            'controller' => 'BusDocsController',
                            'action' => 'index',
                        ]
                    ],
                    'may_terminate' => true,
                    'child_routes' => [
                        'placeholder' => [
                            'type' => 'literal',
                            'options' => [
                                'route' => '/placeholder',
                                'defaults' => [
                                    'controller' => 'BusDocsPlaceholderController',
                                    'action' => 'index',
                                ]
                            ],
                        ],
                    ]
                ],
                'bus-processing' => [
                    'type' => 'segment',
                    'options' => [
                        'route' => '/bus/:busRegId/processing',
                        'defaults' => [
                            'controller' => 'BusProcessingController',
                            'action' => 'index',
                        ]
                    ],
                    'may_terminate' => true,
                   'child_routes' => [
                        'notes' => [
                            'type' => 'segment',
                            'options' => [
                                'route' => '/notes',
                                'defaults' => [
                                    'controller' => 'BusProcessingNoteController',
                                    'action' => 'index',
                                    'page' => 1,
                                    'limit' => 10,
                                    'sort' => 'priority',
                                    'order' => 'DESC'
                                ]
                            ],
                        ],
                        'add-note' => [
                            'type' => 'segment',
                            'options' => [
                                'route' => '/notes/:action/:noteType[/:linkedId]',
                                'defaults' => [
                                    'constraints' => [
                                        'noteType' => '[A-Za-z]+',
                                        'linkedId' => '[0-9]+',
                                    ],
                                    'controller' => 'BusProcessingNoteController',
                                    'action' => 'add'
                                ]
                            ]
                        ],
                        'modify-note' => [
                            'type' => 'segment',
                            'options' => [
                                'route' => '/notes/:action[/:id]',
                                'defaults' => [
                                    'constraints' => [
                                        'id' => '[0-9]+',
                                    ],
                                    'controller' => 'BusProcessingNoteController',
                                ]
                            ]
                        ]
                    ]
                ],
                'bus-fees' => [
                    'type' => 'segment',
                    'options' => [
                        'route' => '/bus/:busRegId/fees',
                        'defaults' => [
                            'controller' => 'BusFeesController',
                            'action' => 'index',
                        ]
                    ],
                    'may_terminate' => true,
                    'child_routes' => [
                        'placeholder' => [
                            'type' => 'literal',
                            'options' => [
                                'route' => '/placeholder',
                                'defaults' => [
                                    'controller' => 'BusFeesPlaceholderController',
                                    'action' => 'index',
                                ]
                            ],
                        ],
                    ]
                ],
                'cases' => [
                    'type' => 'segment',
                    'options' => [
                        'route' => '/cases/page/:page/limit/:limit/sort/:sort/order/:order',
                        'defaults' => [
                            'controller' => 'CaseController',
                            'action' => 'index',
                            'page' => 1,
                            'limit' => 10,
                            'sort' => 'createdOn',
                            'order' => 'ASC'
                        ]
                    ],
                    'may_terminate' => true,
                ],
                'opposition' => [
                    'type' => 'segment',
                    'options' => [
                        'route' => '/opposition',
                        'defaults' => [
                            'action' => 'opposition',
                        ]
                    ],
                    'may_terminate' => true,
                ],
                'documents' => [
                    'type' => 'literal',
                    'options' => [
                        'route' => '/documents',
                        'defaults' => [
                            'action' => 'documents',
                        ]
                    ],
                    'may_terminate' => true,
                    'child_routes' => [
                        'generate' => [
                            'type' => 'segment',
                            'options' => [
                                'route' => '/generate[/:tmpId]',
                                'defaults' => [
                                    'type'       => 'licence',
                                    'controller' => 'DocumentGenerationController',
                                    'action'     => 'generate'
                                ]
                            ],
                        ],
                        'finalise' => [
                            'type' => 'segment',
                            'options' => [
                                'route' => '/finalise/:tmpId',
                                'defaults' => [
                                    'type'       => 'licence',
                                    'controller' => 'DocumentUploadController',
                                    'action'     => 'finalise'
                                ]
                            ],
                        ],
                    ],
                ],
                'processing' => [
                    'type' => 'literal',
                    'options' => [
                        'route' => '/processing',
                        'defaults' => [
                            'controller' => 'LicenceProcessingOverviewController',
                            'action' => 'index',
                        ]
                    ],
                    'may_terminate' => true,
                    'child_routes' => [
                        'tasks' => [
                            'type' => 'segment',
                            'options' => [
                                'route' => '/tasks',
                                'defaults' => [
                                    'controller' => 'LicenceProcessingTasksController',
                                    'action' => 'index'
                                ]
                            ]
                        ],
                        'notes' => [
                            'type' => 'segment',
                            'options' => [
                                'route' => '/notes',
                                'defaults' => [
                                    'controller' => 'LicenceProcessingNoteController',
                                    'action' => 'index'
                                ]
                            ],
                        ],
                        'add-note' => [
                            'type' => 'segment',
                            'options' => [
                                'route' => '/notes/:action/:noteType[/:linkedId]',
                                'defaults' => [
                                    'constraints' => [
                                        'noteType' => '[A-Za-z]+',
                                        'linkedId' => '[0-9]+',
                                    ],
                                    'controller' => 'LicenceProcessingNoteController',
                                    'action' => 'add'
                                ]
                            ]
                        ],
                        'modify-note' => [
                            'type' => 'segment',
                            'options' => [
                                'route' => '/notes/:action[/:id]',
                                'defaults' => [
                                    'constraints' => [
                                        'id' => '[0-9]+',
                                    ],
                                    'controller' => 'LicenceProcessingNoteController',
                                ]
                            ]
                        ]
                    ]
                ],
                'fees' => [
                    'type' => 'literal',
                    'options' => [
                        'route' => '/fees',
                        'defaults' => [
                            'action' => 'fees',
                        ]
                    ],
                    'may_terminate' => true,
                ],
            ]
        ],

        // These routes are for the licence page

        'licence_case_action' => [
            'type' => 'segment',
            'options' => [
                'route' => '/licence/:licence/case[/:action][/:case]',
                'constraints' => [
                    'licence' => '[0-9]+',
                    'case' => '[0-9]+'
                ],
                'defaults' => [
                    'controller' => 'CaseController'
                ]
            ]
        ],
        'case_manage' => [
            'type' => 'segment',
            'options' => [
                'route' => '/licence/[:licence]/case/:case/action/manage/:tab',
                'constraints' => [
                    'case' => '[0-9]+'
                ],
                'defaults' => [
                    'controller' => 'CaseController',
                    'action' => 'manage',
                    'tab' => 'overview'
                ]
            ]
        ],
        'case_statement' => [
            'type' => 'segment',
            'options' => [
                'route' => '/licence/:licence/case/:case/statements[/:action][/:id]',
                'constraints' => [
                    'case' => '[0-9]+',
                    'licence' => '[0-9]+',
                    'id' => '[0-9]+'
                ],
                'defaults' => [
                    'controller' => 'CaseStatementController',
                    'action' => 'index'
                ]
            ]
        ],
        'case_appeal' => [
            'type' => 'segment',
            'options' => [
                'route' => '/licence/:licence/case/:case/appeals[/:action][/:id]',
                'constraints' => [
                    'case' => '[0-9]+',
                    'id' => '[0-9]+'
                ],
                'defaults' => [
                    'controller' => 'CaseAppealController',
                    'action' => 'index'
                ]
            ]
        ],
        'case_convictions' => [
            'type' => 'segment',
            'options' => [
                'route' => '/licence/[:licence]/case/:case/action/manage/convictions',
                'constraints' => [
                    'case' => '[0-9]+',
                    'statement' => '[0-9]+'
                ],
                'defaults' => [
                    'controller' => 'CaseConvictionController',
                    'action' => 'index'
                ]
            ]
        ],
        'conviction_ajax' => [
            'type' => 'Literal',
            'options' => [
                'route' => '/ajax/convictions/categories',
                'defaults' => [
                    'controller' => 'CaseConvictionController',
                    'action' => 'categories',
                ]
            ]
        ],
        'case_stay_action' => [
            'type' => 'segment',
            'options' => [
                'route' => '/licence/[:licence]/case/[:case]/action/manage/stays[/:action][/:stayType][/:id]',
                'constraints' => [
                    'licence' => '[0-9]+',
                    'case' => '[0-9]+',
                    'staytype' => '[0-9]',
                    'id' => '[0-9]+'
                ],
                'defaults' => [
                    'controller' => 'CaseStayController',
                    'action' => 'index'
                ]
            ]
        ],
        'case_annual_test_history' => [
            'type' => 'segment',
            'options' => [
                'route' => '/licence/[:licence]/case/[:case]/action/manage/annual-test-history',
                'constraints' => [
                    'licence' => '[0-9]+',
                    'case' => '[0-9]+',
                ],
                'defaults' => [
                    'controller' => 'CaseAnnualTestHistoryController',
                    'action' => 'index'
                ]
            ]
        ],
        'case_prohibition' => [
            'type' => 'segment',
            'options' => [
                'route' => '/licence/[:licence]/case/[:case]/action/manage/prohibitions[/:action][/:id]',
                'constraints' => [
                    'licence' => '[0-9]+',
                    'case' => '[0-9]+',
                    'id' => '[0-9]+'
                ],
                'defaults' => [
                    'controller' => 'CaseProhibitionController',
                    'action' => 'index'
                ]
            ],
            'may_terminate' => true,
            'child_routes' => [
                'defect' => [
                    'type' => 'segment',
                    'options' => [
                        'route' => '/defect[/:defect]',
                        'constraints' => [
                            'defect' => '[0-9]+'
                        ],
                        'defaults' => [
                            'controller' => 'CaseProhibitionDefectController'
                        ]
                    ]
                ]
            ]
        ],
        'conviction' => [
            'type' => 'segment',
            'options' => [
                'route' => '/licence/[:licence]/case/[:case]/conviction[/:action][/][:id]',
                'defaults' => [
                    'controller' => 'CaseConvictionController',
                ]
            ]
        ],
        'offence' => [
            'type' => 'segment',
            'options' => [
                'route' => '/licence/[:licence]/case/[:case]/offence/view[/:offenceId]',
                'constraints' => [
                    'licence' => '[0-9]+',
                    'case' => '[0-9]+',
                    'offenceId' => '[0-9]+',
                ],
                'defaults' => [
                    'controller' => 'CaseConvictionController',
                    'action' => 'viewOffence'
                ]
            ]
        ],
        'case_penalty' => [
            'type' => 'segment',
            'options' => [
                'route' => '/licence/[:licence]/case/[:case]/action/manage/penalties',
                'constraints' => [
                    'licence' => '[0-9]+',
                    'case' => '[0-9]+',
                ],
                'defaults' => [
                    'controller' => 'CasePenaltyController',
                    'action' => 'index'
                ]
            ]
        ],
        'case_complaints' => [
            'type' => 'segment',
            'options' => [
                'route' => '/licence/[:licence]/case/:case/complaints',
                'constraints' => [
                    'case' => '[0-9]+',
                ],
                'defaults' => [
                    'controller' => 'CaseComplaintController',
                    'action' => 'index'
                ]
            ]
        ],
        'complaint' => [
            'type' => 'segment',
            'options' => [
                'route' => '/licence/:licence/case/:case/complaints/:action[/:id]',
                'constraints' => [
                    'case' => '[0-9]+',
                    'licence' => '[0-9]+',
                    'id' => '[0-9]+'
                ],
                'defaults' => [
                    'controller' => 'CaseComplaintController',
                ]
            ]
        ],
        'submission' => [
            'type' => 'segment',
            'options' => [
                'route' => '/licence/[:licence]/case/[:case]/submission[/:action][/][:id]',
                'defaults' => [
                    'controller' => 'SubmissionController',
                ]
            ]
        ],
        'note' => [
            'type' => 'segment',
            'options' => [
                'route' => '/licence/:licence[/case/:case][/:type/:typeId][/:section]/note[/:action][/:id]',
                'defaults' => [
                    'controller' => 'SubmissionNoteController',
                ]
            ]
        ],
        'case_conditions_undertakings' => [
            'type' => 'segment',
            'options' => [
                'route' => '/licence/[:licence]/case/:case/conditions-undertakings',
                'constraints' => [
                    'case' => '[0-9]+',
                ],
                'defaults' => [
                    'controller' => 'CaseConditionUndertakingController',
                    'action' => 'index'
                ]
            ]
        ],
        'conditions' => [
            'type' => 'segment',
            'options' => [
                'route' => '/licence/:licence/case/:case/conditions/:action[/:id]',
                'constraints' => [
                    'case' => '[0-9]+',
                    'id' => '[0-9]+'
                ],
                'defaults' => [
                    'controller' => 'CaseConditionUndertakingController',
                    'type' => 'condition'
                ]
            ]
        ],
        'undertakings' => [
            'type' => 'segment',
            'options' => [
                'route' => '/licence/:licence/case/:case/undertaking/:action[/:id]',
                'constraints' => [
                    'licence' => '[0-9]+',
                    'case' => '[0-9]+',
                    'id' => '[0-9]+'
                ],
                'defaults' => [
                    'controller' => 'CaseConditionUndertakingController',
                    'type' => 'undertaking'
                ]
            ]
        ],
        'case_impounding' => [
            'type' => 'segment',
            'options' => [
                'route' => '/licence/[:licence]/case/[:case]/task/impounding[/:action][/:id]',
                'constraints' => [
                    'licence' => '[0-9]+',
                    'case' => '[0-9]+',
                    'id' => '[0-9]+'
                ],
                'defaults' => [
                    'controller' => 'CaseImpoundingController',
                    'action' => 'index'
                ]
            ]
        ],
        'case_revoke' => [
            'type' => 'segment',
            'options' => [
                'route' => '/licence/:licence/case/:case/task/revoke[/:action][/:id]',
                'constraints' => [
                    'licence' => '[0-9]+',
                    'case' => '[0-9]+',
                    'id' => '[0-9]+'
                ],
                'defaults' => [
                    'controller' => 'CaseRevokeController'
                ]
            ]
        ],
        'case_pi' => [
            'type' => 'segment',
            'options' => [
                'route' => '/licence/:licence/case/:case/task/pi',
                'constraints' => [
                    'licence' => '[0-9]+',
                    'case' => '[0-9]+'
                ],
                'defaults' => [
                    'controller' => 'CasePiController',
                    'action' => 'index'
                ]
            ]
        ],
        'case_pi_action' => [
            'type' => 'segment',
            'options' => [
                'route' => '/licence/:licence/case/:case/task/pi/:action/:section[/:id]',
                'constraints' => [
                    'licence' => '[0-9]+',
                    'case' => '[0-9]+',
                    'id' => '[0-9]+'
                ],
                'defaults' => [
                    'controller' => 'CasePiController',
                    'action' => 'index'
                ]
            ]
        ],
        'case_pi_hearing' => [
            'type' => 'segment',
            'options' => [
                'route' => '/licence/:licence/case/:case/pi/:piId/hearing[/:hearingId]',
                'constraints' => [
                    'licence' => '[0-9]+',
                    'case' => '[0-9]+',
                    'piId' => '[0-9]+',
                    'hearingId' => '[0-9]+'
                ],
                'defaults' => [
                    'controller' => 'CasePiHearingController',
                    'action' => 'index'
                ]
            ]
        ],
        'entity_lists' => [
            'type' => 'segment',
            'options' => [
                'route' => '/list/[:type]/[:value]',
                'defaults' => [
                    'controller' => 'IndexController',
                    'action' => 'entityList'
                ]
            ]
        ],
        'template_lists' => [
            'type' => 'segment',
            'options' => [
                'route' => '/list-template-bookmarks/:id',
                'constraints' => [
                    'id' => '[0-9]+'
                ],
                'defaults' => [
                    'type'       => 'licence',
                    'controller' => 'DocumentController',
                    'action'     => 'listTemplateBookmarks'
                ]
            ]
        ],
        'fetch_document' => [
            'type' => 'segment',
            'options' => [
                'route' => '/documents/:id/:filename',
                'defaults' => [
                    'controller' => 'DocumentController',
                    'action'     => 'download'
                ]
            ]
        ],
        'fetch_tmp_document' => [
            'type' => 'segment',
            'options' => [
                'route' => '/documents/tmp/:id/:filename',
                'defaults' => [
                    'controller' => 'DocumentController',
                    'action'     => 'downloadTmp'
                ]
            ]
        ]
    ]
);
