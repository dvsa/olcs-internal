<?php

use Admin\Controller\PublishedPublicationController;
use Laminas\Mvc\Router\Http\Segment;

use Admin\Listener\RouteParam\IrhpPermitAdminFurniture;
use Admin\Listener\RouteParam;

return [
    'router' => [
        'routes' => [
            'admin-dashboard' => [
                'type' => 'segment',
                'options' => [
                    'route' => '/admin[/]',
                    'defaults' => [
                        'controller' => Admin\Controller\IndexController::class,
                        'action' => 'index',
                    ]
                ],
                'may_terminate' => true,
                'child_routes' => [
                    'admin-data-retention' => [
                        'type' => 'segment',
                        'options' => [
                            'route' => 'data-retention[/]',
                            'defaults' => [
                                'controller' => Admin\Controller\DataRetentionReviewController::class,
                                'action' => 'index',
                            ]
                        ],
                        'may_terminate' => true,
                        'child_routes' => [
                            'review' => [
                                'type' => 'segment',
                                'options' => [
                                    'route' => 'review[/]',
                                    'defaults' => [
                                        'controller' => Admin\Controller\DataRetentionReviewController::class,
                                        'action' => 'index'
                                    ],
                                ],
                                'may_terminate' => true,
                                'child_routes' => [
                                    'records' => [
                                        'type' => 'segment',
                                        'options' => [
                                            'route' => 'records/:dataRetentionRuleId[/:action[/:id]][/]',
                                            'constraints' => [
                                                'dataRetentionRuleId' => '[0-9]+',
                                                'id' => '[0-9\,]+',
                                                'action' => '(review|index|delete|delay|assign)',
                                            ],
                                            'defaults' => [
                                                'controller' => Admin\Controller\DataRetentionController::class,
                                                'action' => 'index'
                                            ],
                                        ],
                                    ],
                                ]
                            ],
                            'export' => [
                                'type' => 'segment',
                                'options' => [
                                    'route' => 'export[/]',
                                    'defaults' => [
                                        'controller' => Admin\Controller\DataRetention\ExportController::class,
                                        'action' => 'index'
                                    ]
                                ]
                            ],
                            'rule-admin' => [
                                'type' => 'segment',
                                'options' => [
                                    'route' => 'rule-admin[/:action][/:id][/]',
                                    'defaults' => [
                                        'controller' => Admin\Controller\DataRetention\RuleAdminController::class,
                                        'action' => 'index'
                                    ],
                                    'constraints' => [
                                        'id' => '[0-9\,]+',
                                        'action' => '(index|edit)',
                                    ],
                                ]
                            ],
                        ],
                    ],
                    'task-allocation-rules' => [
                        'type' => 'Segment',
                        'options' => [
                            'route' =>
                                'task-allocation-rules[/:action][/:id][/alpha-split/:alpha-split][/team/:team][/]',
                            'constraints' => [
                                'action' => '(add|edit|delete|AddAlphasplit|EditAlphasplit|DeleteAlphasplit)',
                                'id' => '[0-9\,]+',
                                'alpha-split' => '[0-9]+',
                                'team' => '[0-9]+',
                            ],
                            'defaults' => [
                                'controller' => 'Admin\TaskAllocationRulesController',
                                'action' => 'index',
                            ]
                        ],
                    ],
                    'document-templates' => [
                        'type' => 'Segment',
                        'options' => [
                            'route' =>
                                'document-templates[/:action][/:id][/]',
                            'constraints' => [
                                'action' => '(add|edit|delete)',
                                'id' => '[0-9\,]+',
                            ],
                            'defaults' => [
                                'controller' => Admin\Controller\DocumentTemplateController::class,
                                'action' => 'index',
                            ]
                        ],
                    ],
                    'admin-scanning' => [
                        'type' => 'segment',
                        'options' => [
                            'route' => 'scanning[/]',
                            'defaults' => [
                                'controller' => Admin\Controller\ScanningController::class,
                                'action' => 'index',
                            ]
                        ],
                    ],
                    'admin-printing' => [
                        'type' => 'segment',
                        'options' => [
                            'route' => 'printing[/]',
                            'defaults' => [
                                'controller' => 'Admin\PrintingController',
                                'action' => 'jump',
                            ]
                        ],
                        'may_terminate' => true,
                        'child_routes' => [
                            'irhp-permits' => [
                                'type' => 'segment',
                                'options' => [
                                    'route' => 'irhp-permits[/:action][/:id][/]',
                                    'constraints' => [
                                        'id' => '([0-9]+,?)+',
                                        'action' => '(list|confirm|cancel|print)'
                                    ],
                                    'defaults' => [
                                        'controller' => Admin\Controller\IrhpPermitPrintController::class,
                                        'action' => 'index'
                                    ]
                                ]
                            ],
                            'irfo-stock-control' => [
                                'type' => 'segment',
                                'options' => [
                                    'route' => 'irfo-stock-control[/:action][/:id][/]',
                                    'constraints' => [
                                        'id' => '([0-9]+,?)+',
                                        'action' => '(index|add|in-stock|issued|void|returned)'
                                    ],
                                    'defaults' => [
                                        'controller' => 'Admin\IrfoStockControlController',
                                        'action' => 'index'
                                    ]
                                ]
                            ],
                            'admin-printer-management' => [
                                'type' => 'Segment',
                                'options' => [
                                    'route' => 'printers[/:action][/:printer][/]',
                                    'constraints' => [
                                        'user' => '[0-9]+',
                                        'action' => '(index|add|edit|delete)'
                                    ],
                                    'defaults' => [
                                        'controller' => 'Admin\PrintingController',
                                        'action' => 'index'
                                    ]
                                ]
                            ],
                        ]
                    ],
                    'admin-publication' => [
                        'type' => 'segment',
                        'options' => [
                            'route' => 'publication[/]',
                            'defaults' => [
                                'controller' => 'Admin\PublicationController',
                                'action' => 'jump',
                            ]
                        ],
                        'may_terminate' => true,
                        'child_routes' => [
                            'pending' => [
                                'type' => 'segment',
                                'options' => [
                                    'route' => 'pending[/:action][/:id][/]',
                                    'constraints' => [
                                        'publication' => '[0-9]+',
                                        'action' => '(index|generate|publish)'
                                    ],
                                    'defaults' => [
                                        'controller' => 'Admin\PublicationController',
                                        'action' => 'index'
                                    ]
                                ]
                            ],
                            'recipient' => [
                                'type' => 'segment',
                                'options' => [
                                    'route' => 'recipient[/:action][/:id][/]',
                                    'constraints' => [
                                        'id' => '[0-9]+',
                                        'action' => '(index|add|edit|delete)'
                                    ],
                                    'defaults' => [
                                        'controller' => 'Admin\RecipientController',
                                        'action' => 'index'
                                    ]
                                ]
                            ],
                            'published' => [
                                'type' => 'segment',
                                'options' => [
                                    'route' => 'published[/]',
                                    'defaults' => [
                                        'controller' => PublishedPublicationController::class,
                                        'action' => 'index'
                                    ]
                                ]
                            ]
                        ]
                    ],
                    'admin-your-account' => [
                        'type' => 'segment',
                        'options' => [
                            'route' => 'your-account[/]',
                            'defaults' => [
                                'controller' => 'Admin\MyDetailsController',
                                'action' => 'index',
                            ]
                        ],
                        'may_terminate' => true,
                        'child_routes' => [
                            'details' => [
                                'type' => 'segment',
                                'options' => [
                                    'route' => 'details[/]',
                                    'defaults' => [
                                        'controller' => 'Admin\MyDetailsController',
                                        'action' => 'edit'
                                    ]
                                ]
                            ],
                        ]
                    ],
                    'admin-continuation' => [
                        'type' => 'Segment',
                        'options' => [
                            'route' => 'continuation[/]',
                            'defaults' => [
                                'controller' => 'Admin\ContinuationController',
                                'action' => 'index',
                            ],
                        ],
                        'may_terminate' => true,
                        'child_routes' => [
                            'detail' => [
                                'type' => 'Segment',
                                'options' => [
                                    'route' => 'detail/:id[/:action[/:child_id]][/]',
                                    'defaults' => [
                                        'controller' => 'Admin\ContinuationController',
                                        'action' => 'detail',
                                    ],
                                ],
                            ],
                            'checklist-reminder' => [
                                'type' => 'Segment',
                                'options' => [
                                    'route' => 'checklist-reminder[/:action[/:child_id]][/]',
                                    'defaults' => [
                                        'controller' => 'Admin\ContinuationChecklistReminderController',
                                        'action' => 'index',
                                    ],
                                ],
                            ],
                            'irfo-psv-auth' => [
                                'type' => 'Segment',
                                'options' => [
                                    'route' => 'irfo-psv-auth/:year/:month[/:action/:id][/]',
                                    'constraints' => [
                                        'year' => '([0-9]{4})',
                                        'month' => '([0-9]{1,2})',
                                        'action' => '(renew|print)',
                                        'id' => '([0-9]+,?)+',
                                    ],
                                    'defaults' => [
                                        'controller' => 'Admin\IrfoPsvAuthContinuationController',
                                        'action' => 'index',
                                    ],
                                ],
                            ],
                        ]
                    ],
                    'admin-report' => [
                        'type' => 'Segment',
                        'options' => [
                            'route' => 'report[/]',
                            'defaults' => [
                                'controller' => 'Admin\ReportController',
                                'action' => 'index',
                            ]
                        ],
                        'may_terminate' => true,
                        'child_routes' => [
                            'ch-alerts' => [
                                'type' => 'Segment',
                                'options' => [
                                    'route' => 'ch-alerts[/:action][/:id][/]',
                                    'constraints' => [
                                        'id' => '[0-9\,]+'
                                    ],
                                    'defaults' => [
                                        'controller' => Admin\Controller\CompaniesHouseAlertController::class,
                                        'action' => 'index',
                                    ]
                                ],
                            ],
                            'cpms' => [
                                'type' => 'Segment',
                                'options' => [
                                    'route' => 'cpms[/:action][/:id][/]',
                                    'constraints' => [
                                        'id' => '[0-9\,]+'
                                    ],
                                    'defaults' => [
                                        'controller' => Admin\Controller\CpmsReportController::class,
                                        'action' => 'index',
                                    ]
                                ],
                            ],
                            'interim-refunds' => [
                                'type' => 'Segment',
                                'options' => [
                                    'route' => 'interim-refunds[/:action][/:id][/]',
                                    'constraints' => [
                                        'action' => '(index)',
                                        'id' => '[0-9]+',
                                    ],
                                    'defaults' => [
                                        'controller' => Admin\Controller\InterimRefundsController::class,
                                        'action' => 'index'
                                    ]
                                ]
                            ],
                            'cpid-class' => [
                                'type' => 'segment',
                                'options' => [
                                    'route' => 'cpid-classification[/:status][/]',
                                    'defaults' => [
                                        'controller' => 'Admin\ReportController',
                                        'action' => 'cpidClassification',
                                        'status' => null
                                    ]
                                ]
                            ],
                            'exported-reports' => [
                                'type' => 'segment',
                                'options' => [
                                    'route' => 'exported-reports[/]',
                                    'defaults' => [
                                        'controller' => 'Admin\ReportController',
                                        'action' => 'exportedReports'
                                    ]
                                ]
                            ],
                            'pi' => [
                                'type' => 'segment',
                                'options' => [
                                    'route' => 'pi[/]',
                                    'defaults' => [
                                        'controller' => 'Admin\PiReportController',
                                        'action' => 'index'
                                    ]
                                ]
                            ],
                            'cases' => [
                                'type' => Segment::class,
                                'options' => [
                                    'route' => 'cases/',
                                ],
                                'may_terminate' => false,
                                'child_routes' => [
                                    'open' => [
                                        'type' => Segment::class,
                                        'options' => [
                                            'route' => 'open[/]',
                                            'defaults' => [
                                                'controller' => Admin\Controller\ReportCasesOpenController::class,
                                                'action' => 'index',
                                            ],
                                        ],
                                    ],
                                ],
                            ],
                            'upload' => [
                                'type' => 'Segment',
                                'options' => [
                                    'route' => 'upload[/]',
                                    'defaults' => [
                                        'controller' => Admin\Controller\ReportUploadController::class,
                                        'action' => 'index',
                                    ]
                                ],
                            ],
                        ],
                    ],
                    'admin-user-management' => [
                        'type' => 'Segment',
                        'options' => [
                            'route' => 'user-management/users[/:action][/:user][/]',
                            'constraints' => [
                                'user' => '[0-9]+',
                                'action' => '(index|add|edit|delete)'
                            ],
                            'defaults' => [
                                'controller' => 'Admin\UserManagementController',
                                'action' => 'index'
                            ]
                        ]
                    ],
                    'admin-team-management' => [
                        'type' => 'Segment',
                        'options' => [
                            'route' => 'user-management/teams[/:action][/:team][/rule/:rule][/]',
                            'action' => '(index|add|edit|delete|addRule|editRule|deleteRule)',
                            'constraints' => [
                                'team' => '[0-9]+',
                                'rule' => '[0-9]+',
                            ],
                            'defaults' => [
                                'controller' => Admin\Controller\TeamController::class,
                                'action' => 'index'
                            ],
                        ]
                    ],
                    'admin-partner-management' => [
                        'type' => 'Segment',
                        'options' => [
                            'route' => 'partner[/:action][/:id][/]',
                            'constraints' => [
                                'id' => '[0-9]+',
                                'action' => '(index|add|edit|delete)'
                            ],
                            'defaults' => [
                                'controller' => 'Admin\PartnerController',
                                'action' => 'index'
                            ]
                        ]
                    ],
                    'admin-financial-standing' => [
                        'type' => 'Segment',
                        'options' => [
                            'route' => 'financial-standing[/:action][/:id][/]',
                            'constraints' => [
                                'id' => '[0-9\,]+'
                            ],
                            'defaults' => [
                                'controller' => 'Admin\FinancialStandingRateController',
                                'action' => 'index',
                            ]
                        ],
                    ],
                    'admin-public-holiday' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => 'public-holiday[/:action][/:holidayId][/]',
                            'constraints' => [
                                'action' => '(index|add|edit|delete)',
                            ],
                            'defaults' => [
                                'controller' => Admin\Controller\PublicHolidayController::class,
                                'action' => 'index',
                            ]
                        ],
                    ],
                    'admin-disc-printing' => [
                        'type' => 'Segment',
                        'options' => [
                            'route' => 'disc-printing[/success[/:success]][/:action][/]',
                            'defaults' => [
                                'controller' => 'Admin\DiscPrintingController',
                                'action' => 'index',
                            ]
                        ],
                    ],
                    'admin-system-parameters' => [
                        'type' => 'Segment',
                        'options' => [
                            'route' => 'system-parameters[/:action][/:sp][/]',
                            'constraints' => [
                                'action' => '(index|add|edit|delete)'
                            ],
                            'defaults' => [
                                'controller' => 'Admin\SystemParametersController',
                                'action' => 'index'
                            ]
                        ]
                    ],
                    'admin-feature-toggle' => [
                        'type' => 'Segment',
                        'options' => [
                            'route' => 'feature-toggle[/:action][/:id][/]',
                            'constraints' => [
                                'action' => '(index|add|edit|delete)',
                                'id' => '[0-9]+',
                            ],
                            'defaults' => [
                                'controller' => \Admin\Controller\FeatureToggleController::class,
                                'action' => 'index'
                            ]
                        ]
                    ],
                    // Admin IRHP Permits
                    'admin-permits' => [
                        'type' => 'Segment',
                        'options' => [
                            'route' => 'permits[/]',
                            'defaults' => [
                                'controller' => \Admin\Controller\PermitsController::class,
                                'action' => 'index'
                            ]
                        ],
                        'may_terminate' => true,
                        'child_routes' => [
                            // All IRHP Permit Stocks
                            'stocks' => [
                                'type' => 'Segment',
                                'options' => [
                                    'route' => 'stocks[/:action][/:id][/]',
                                    'constraints' => [
                                        'action' => '(index|add|edit|delete)',
                                        'id' => '[0-9\,]+'
                                    ],
                                    'defaults' => [
                                        'controller' => \Admin\Controller\IrhpPermitStockController::class,
                                        'action' => 'index'
                                    ],
                                ],
                                'may_terminate' => true,
                            ],
                            // IRHP Permit Stock Ranges
                            'ranges' => [
                                'type' => 'Segment',
                                'options' => [
                                    'route' => 'stocks/:stockId/ranges[/:action][/:id][/]',
                                    'constraints' => [
                                        'stockId' => '[0-9\,]+',
                                        'action' => '(index|add|edit|delete)',
                                        'id' => '[0-9\,]+'
                                    ],
                                    'defaults' => [
                                        'controller' => \Admin\Controller\IrhpPermitRangeController::class,
                                        'action' => 'index',
                                    ],
                                ],
                                'may_terminate' => true
                            ],
                            // IRHP Permit Stock Windows
                            'windows' => [
                                'type' => 'Segment',
                                'options' => [
                                    'route' => 'stocks/:stockId/windows[/:action][/:id][/]',
                                    'constraints' => [
                                        'stockId' => '[0-9\,]+',
                                        'action' => '(index|add|edit|delete)',
                                        'id' => '[0-9\,]+',
                                    ],
                                    'defaults' => [
                                        'controller' => \Admin\Controller\IrhpPermitWindowController::class,
                                        'action' => 'index',
                                    ],
                                ],
                                'may_terminate' => true
                            ],
                            // IRHP Permit Stock Sectors
                            'sectors' => [
                                'type' => 'Segment',
                                'options' => [
                                    'route' => 'stocks/:stockId/sectors[/:action][/:id][/]',
                                    'constraints' => [
                                        'stockId' => '[0-9\,]+',
                                        'action' => '(index|add|edit|delete)',
                                        'id' => '[0-9\,]+',
                                    ],
                                    'defaults' => [
                                        'controller' => \Admin\Controller\IrhpPermitSectorController::class,
                                        'action' => 'index',
                                    ],
                                ],
                                'may_terminate' => true,
                            ],
                            // IRHP Permit Stock Devolved Administrations
                            'jurisdiction' => [
                                'type' => 'Segment',
                                'options' => [
                                    'route' => 'stocks/:stockId/jurisdiction[/:action][/:id][/]',
                                    'constraints' => [
                                        'stockId' => '[0-9\,]+',
                                        'id' => '[0-9\,]+',
                                        'action' => '(index|add|edit|delete)'
                                    ],
                                    'defaults' => [
                                        'controller' => \Admin\Controller\IrhpPermitJurisdictionController::class,
                                        'action' => 'index',
                                    ],
                                ],
                                'may_terminate' => true,
                            ],
                            // Permit Scoring
                            'scoring' => [
                                'type' => 'Segment',
                                'options' => [
                                    'route' => 'stocks/:stockId/scoring[/:action][/:deviation]',
                                    'constraints' => [
                                        'stockId' =>'[0-9\,]+',
                                        'action' => '(index|accept|runStandard|runWithMeanDeviation|postScoringReport|alignStock|status)',
                                        'deviation' =>'[0-9\.]+'
                                    ],
                                    'defaults' => [
                                        'controller' => \Admin\Controller\IrhpPermitScoringController::class,
                                        'action' => 'index',
                                    ],
                                ],
                            ],
                            'exported-reports' => [
                                'type' => 'Segment',
                                'options' => [
                                    'route' => 'stocks/:stockId/exported-reports[/:action][/]',
                                    'constraints' => [
                                        'id' => '[0-9\,]+',
                                        'action' => '(index|add|edit|delete)'
                                    ],
                                    'defaults' => [
                                        'controller' => \Admin\Controller\IrhpPermitReportingController::class,
                                        'action' => 'index',
                                    ],
                                ],
                            ],
                        ],
                    ],
                    'admin-system-info-message' => [
                        'type' => 'Segment',
                        'options' => [
                            'route' => 'system-info-message[/:action][/:msgId][/]',
                            'constraints' => [
                                'action' => '(index|add|edit|delete)',
                            ],
                            'defaults' => [
                                'controller' => Admin\Controller\SystemInfoMessageController::class,
                                'action' => 'index',
                            ],
                        ],
                    ],
                    'admin-payment-processing' => [
                        'type' => 'segment',
                        'options' => [
                            'route' => 'payment-processing[/]',
                            'defaults' => [
                                'controller' => Admin\Controller\PaymentProcessingFeesController::class,
                                'action' => 'redirect',
                            ]
                        ],
                        'may_terminate' => true,
                        'child_routes' => [
                            'misc-fees' => [
                                'type' => 'segment',
                                'options' => [
                                    'route' => 'fees[/]',
                                    'defaults' => [
                                        'action' => 'index'
                                    ]
                                ],
                                'may_terminate' => true,
                                'child_routes' => [
                                    'fee_action' => [
                                        'type' => 'segment',
                                        'options' => [
                                            'route' => ':action[/:fee][/]',
                                            'constraints' => [
                                                'fee' => '([0-9]+,?)+',
                                            ],
                                        ],
                                        'may_terminate' => true,
                                        'child_routes' => [
                                            'transaction' => [
                                                'type' => 'segment',
                                                'options' => [
                                                    'route' => 'transaction/:transaction[/]',
                                                    'constraints' => [
                                                        'transaction' => '([0-9]+,?)+',
                                                    ],
                                                    'defaults' => [
                                                        'action' => 'transaction',
                                                    ]
                                                ],
                                                'may_terminate' => true,
                                                'child_routes' => [
                                                    'reverse' => [
                                                        'type' => 'segment',
                                                        'options' => [
                                                            'route' => 'reverse[/]',
                                                            'defaults' => [
                                                                'action' => 'reverseTransaction',
                                                            ]
                                                        ],
                                                        'may_terminate' => true,
                                                    ],
                                                ],
                                            ],
                                        ],
                                    ],
                                    'print-receipt' => [
                                        'type' => 'segment',
                                        'options' => [
                                            'route' => 'print-receipt/:reference[/]',
                                            'constraints' => [
                                                'reference' => 'OLCS-[0-9A-F\-]+',
                                            ],
                                            'defaults' => [
                                                'action' => 'print',
                                            ],
                                        ],
                                    ],
                                    'fee_type_ajax' => [
                                        'type' => 'segment',
                                        'options' => [
                                            'route' => 'ajax/',
                                        ],
                                        'may_terminate' => false,
                                        'child_routes' => [
                                            'single' => [
                                                'type' => 'segment',
                                                'options' => [
                                                    'route' => 'fee-type/:id',
                                                    'constraints' => [
                                                        'id' => '([0-9]+,?)+',
                                                    ],
                                                    'defaults' => [
                                                        'action' => 'feeType',
                                                    ]
                                                ],
                                                'may_terminate' => true,
                                            ],
                                            'list' => [
                                                'type' => 'segment',
                                                'options' => [
                                                    'route' => 'fee-type-list/:date',
                                                    // 'constraints' => [
                                                    //     'date' => '([0-9]{4}\-[0-9]{2}\-[0-9]{2})',
                                                    // ],
                                                    'defaults' => [
                                                        'action' => 'feeTypeList',
                                                    ]
                                                ],
                                                'may_terminate' => true,
                                            ]
                                        ],
                                    ]
                                ],
                            ],
                        ],
                    ],
                    'admin-email-templates' => [
                        'type' => 'Segment',
                        'options' => [
                            'route' =>
                                'email-templates[/:action][/:id][/]',
                            'constraints' => [
                                'action' => '(index|add|edit|delete|previewTemplate)',
                                'id' => '[0-9\,]+',
                            ],
                            'defaults' => [
                                'controller' => Admin\Controller\TemplateController::class,
                                'action' => 'index',
                            ]
                        ],
                    ],
                    'admin-document-templates' => [
                        'type' => 'Segment',
                        'options' => [
                            'route' =>
                                'document-templates[/:action][/:id][/]',
                            'constraints' => [
                                'action' => '(index|add|edit|delete)',
                                'id' => '[0-9\,]+',
                            ],
                            'defaults' => [
                                'controller' => Admin\Controller\DocumentTemplateController::class,
                                'action' => 'index',
                            ]
                        ],
                    ],
                    'admin-editable-translations' => [
                        'type' => 'Segment',
                        'options' => [
                            'route' =>
                                'editable-translations[/:action][/:id][/][:subid]',
                            'constraints' => [
                                'action' => '(index|add|editkey|subdelete|delete|details|languages|gettext|xhrsearch)'
                            ],
                            'defaults' => [
                                'controller' => Admin\Controller\EditableTranslationsController::class,
                                'action' => 'index',
                            ]
                        ],
                    ],
                    'admin-replacements' => [
                        'type' => 'Segment',
                        'options' => [
                            'route' =>
                                'replacements[/:action][/:id][/]',
                            'constraints' => [
                                'action' => '(index|add|edit)'
                            ],
                            'defaults' => [
                                'controller' => Admin\Controller\ReplacementsController::class,
                                'action' => 'index',
                            ]
                        ],
                    ],
                    'admin-fee-rates' => [
                        'type' => 'Segment',
                        'options' => [
                            'route' =>
                                'fee-rates[/:action][/:id][/]',
                            'constraints' => [
                                'action' => '(index|edit)',
                                'id' => '[0-9\,]+',
                            ],
                            'defaults' => [
                                'controller' => Admin\Controller\FeeRateController::class,
                                'action' => 'index',
                            ]
                        ],
                    ],
                ],
            ],
        ],
    ],
    'tables' => [
        'config' => [
            __DIR__ . '/../src/Table/Tables/'
        ]
    ],
    'crud-config' => [
        /**
         * Sample crud config
         * 'route/match/name' => [
         *    // Define which actions are available, and whether they require rows to be selected
         *   'add' => ['requireRows' => false],
         *   'edit' => ['requireRows' => true]
         * ]
         */
    ],
    'controllers' => [
        'invokables' => [
            Admin\Controller\IndexController::class => Admin\Controller\IndexController::class,
            'Admin\PrintingController' => 'Admin\Controller\PrintingController',
            'Admin\IrfoStockControlController' => 'Admin\Controller\IrfoStockControlController',
            'Admin\IrfoPsvAuthContinuationController' => 'Admin\Controller\IrfoPsvAuthContinuationController',
            Admin\Controller\ScanningController::class => Admin\Controller\ScanningController::class,
            'Admin\PublicationController' => 'Admin\Controller\PublicationController',
            PublishedPublicationController::class => PublishedPublicationController::class,
            'Admin\RecipientController' => 'Admin\Controller\RecipientController',
            'Admin\ContinuationController' => 'Admin\Controller\ContinuationController',
            'Admin\ReportController' => 'Admin\Controller\ReportController',
            'Admin\UserManagementController' => 'Admin\Controller\UserManagementController',
            Admin\Controller\PublicHolidayController::class => Admin\Controller\PublicHolidayController::class,
            'Admin\DiscPrintingController' => 'Admin\Controller\DiscPrintingController',
            'Admin\MyDetailsController' => 'Admin\Controller\MyDetailsController',
            Admin\Controller\PaymentProcessingFeesController::class =>
                Admin\Controller\PaymentProcessingFeesController::class,
            'Admin\PartnerController' => 'Admin\Controller\PartnerController',
            'Admin\ContinuationChecklistReminderController' =>
                'Admin\Controller\ContinuationChecklistReminderController',
            Admin\Controller\CompaniesHouseAlertController::class =>
                Admin\Controller\CompaniesHouseAlertController::class,
            'Admin\FinancialStandingRateController' => 'Admin\Controller\FinancialStandingRateController',
            Admin\Controller\CpmsReportController::class => Admin\Controller\CpmsReportController::class,
            Admin\Controller\TeamController::class => \Admin\Controller\TeamController::class,
            'Admin\SystemParametersController' => \Admin\Controller\SystemParametersController::class,
            \Admin\Controller\FeatureToggleController::class => \Admin\Controller\FeatureToggleController::class,
            'Admin\TaskAllocationRulesController' => \Admin\Controller\TaskAllocationRulesController::class,
            'Admin\PiReportController' => 'Admin\Controller\PiReportController',
            Admin\Controller\SystemInfoMessageController::class => Admin\Controller\SystemInfoMessageController::class,
            Admin\Controller\ReportCasesOpenController::class => Admin\Controller\ReportCasesOpenController::class,
            Admin\Controller\DataRetentionController::class => Admin\Controller\DataRetentionController::class,
            Admin\Controller\DataRetentionReviewController::class =>
                Admin\Controller\DataRetentionReviewController::class,
            Admin\Controller\DataRetention\ExportController::class =>
                Admin\Controller\DataRetention\ExportController::class,
            Admin\Controller\DataRetention\RuleAdminController::class =>
                Admin\Controller\DataRetention\RuleAdminController::class,
            Admin\Controller\PermitsController::class =>
                Admin\Controller\PermitsController::class,
            Admin\Controller\IrhpPermitStockController::class =>
                Admin\Controller\IrhpPermitStockController::class,
            Admin\Controller\IrhpPermitWindowController::class =>
                Admin\Controller\IrhpPermitWindowController::class,
            Admin\Controller\IrhpPermitRangeController::class =>
                Admin\Controller\IrhpPermitRangeController::class,
            Admin\Controller\IrhpPermitSectorController::class =>
                Admin\Controller\IrhpPermitSectorController::class,
            Admin\Controller\IrhpPermitJurisdictionController::class =>
                Admin\Controller\IrhpPermitJurisdictionController::class,
            Admin\Controller\IrhpPermitScoringController::class =>
                Admin\Controller\IrhpPermitScoringController::class,
            Admin\Controller\IrhpPermitReportingController::class =>
                Admin\Controller\IrhpPermitReportingController::class,
            Admin\Controller\IrhpPermitPrintController::class =>
                Admin\Controller\IrhpPermitPrintController::class,
            Admin\Controller\ReportUploadController::class => Admin\Controller\ReportUploadController::class,
            Admin\Controller\DocumentTemplateController::class => Admin\Controller\DocumentTemplateController::class,
            Admin\Controller\TemplateController::class => Admin\Controller\TemplateController::class,
            Admin\Controller\InterimRefundsController::class =>Admin\Controller\InterimRefundsController::class,
            Admin\Controller\FeeRateController::class => Admin\Controller\FeeRateController::class,
            Admin\Controller\EditableTranslationsController::class => Admin\Controller\EditableTranslationsController::class,
            Admin\Controller\ReplacementsController::class => Admin\Controller\ReplacementsController::class
        ],
    ],
    'view_manager' => [
        'template_path_stack' => [
            'admin/view' => dirname(__DIR__) . '/view',
        ]
    ],
    'service_manager' => [
        'aliases' => [
            'user-details' => 'UserDetailsNavigation'
        ],
        'factories' => [
            'UserDetailsNavigation' => 'Admin\Navigation\UserDetailsNavigationFactory',
            IrhpPermitAdminFurniture::class => IrhpPermitAdminFurniture::class,
        ]
    ],
    'local_forms_path' => [__DIR__ . '/../src/Form/Forms/'],
    //-------- Start navigation -----------------
    'navigation' => [
        'default' => [
            include __DIR__ . '/navigation.config.php'
        ],
        'user-details' => [
            include __DIR__ . '/navigation-user-details.config.php'
        ]
    ],
    //-------- End navigation -----------------
    'local_scripts_path' => [__DIR__ . '/../assets/js/inline/'],
    'my_account_route' => 'admin-dashboard/admin-your-account',
    'route_param_listeners' => [
        \Admin\Controller\Interfaces\IrhpPermitStockControllerInterface::class => [
            RouteParam\IrhpPermitAdminFurniture::class,
        ],
    ]
];
