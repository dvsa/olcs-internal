<?php

return [
    'label' => 'Home',
    'route' => 'dashboard',
    'pages' => [
        [
            'id' => 'admin-dashboard',
            'label' => 'Admin',
            'route' => 'admin-dashboard',
            'pages' => [
                [
                    'label' => 'Scanning',
                    'route' => 'admin-dashboard/admin-scanning',
                ],
                [
                    'id'    => 'admin-dashboard/admin-user-management',
                    'label' => 'User management',
                    'route' => 'admin-dashboard/admin-team-management',
                    'pages' => [
                        [
                            'id' => 'admin-dashboard/admin-team-management',
                            'label' => 'Teams',
                            'route' => 'admin-dashboard/admin-team-management',
                        ],
                        [
                            'id' => 'admin-dashboard/admin-partner-management',
                            'label' => 'Partner organisations',
                            'route' => 'admin-dashboard/admin-partner-management',
                        ]
                    ]
                ],
                [
                    'id'    => 'admin-dashboard/admin-printing',
                    'label' => 'Printing',
                    'route' => 'admin-dashboard/admin-printing',
                    'pages' => [
                        [
                            'id' => 'admin-dashboard/admin-printing/admin-printer-management',
                            'label' => 'Printers',
                            'route' => 'admin-dashboard/admin-printing/admin-printer-management',
                        ],
                        [
                            'label' => 'Disc Printing',
                            'route' => 'admin-dashboard/admin-disc-printing',
                        ],
                        [
                            'id' => 'admin-dashboard/admin-printing/irfo-stock-control',
                            'label' => 'IRFO stock control',
                            'route' => 'admin-dashboard/admin-printing/irfo-stock-control',
                        ],
                        [
                            'id' => 'admin-dashboard/admin-printing/irhp-permits',
                            'label' => 'Print IRHP Permits',
                            'route' => 'admin-dashboard/admin-printing/irhp-permits',
                        ],
                    ]
                ],
                [
                    'id' => 'admin-dashboard/admin-financial-standing',
                    'label' => 'Financial standing rates',
                    'route' => 'admin-dashboard/admin-financial-standing',
                ],
                [
                    'id' => 'admin-dashboard/admin-public-holiday',
                    'label' => 'Public holidays',
                    'route' => 'admin-dashboard/admin-public-holiday',
                ],
                [
                    'id'    => 'admin-dashboard/admin-publication',
                    'label' => 'Publications',
                    'route' => 'admin-dashboard/admin-publication',
                    'pages' => [
                        [
                            'id' => 'admin-dashboard/admin-publication/pending',
                            'label' => 'Pending',
                            'route' => 'admin-dashboard/admin-publication/pending'
                        ],
                        [
                            'id' => 'admin-dashboard/admin-publication/published',
                            'label' => 'Published',
                            'route' => 'admin-dashboard/admin-publication/published'
                        ],
                        [
                            'id' => 'admin-dashboard/admin-publication/recipient',
                            'label' => 'Recipients',
                            'route' => 'admin-dashboard/admin-publication/recipient',
                        ]
                    ]
                ],
                [
                    'id' => 'admin-dashboard/continuations',
                    'label' => 'admin-continuations-title',
                    'route' => 'admin-dashboard/admin-continuation',
                    'pages' => [
                        [
                            'label' => 'admin-generate-continuations-title',
                            'route' => 'admin-dashboard/admin-continuation',
                            'pages' => [
                                [
                                    'label' => 'admin-generate-continuation-details-title',
                                    'route' => 'admin-dashboard/admin-continuation/detail',
                                ],
                            ],
                        ],
                        [
                            'label' => 'admin-continuations-checklist-reminders-title',
                            'route' => 'admin-dashboard/admin-continuation/checklist-reminder',
                        ],
                    ],
                ],
                // @NOTE Duplicate of the above but with a slightly different structure, to allow the user to click
                // back to the generate page
                [
                    'id' => 'admin-dashboard/continuations-details',
                    'label' => 'admin-continuations-title',
                    'route' => 'admin-dashboard/admin-continuation',
                    'pages' => [
                        [
                            'label' => 'admin-generate-continuations-title',
                            'route' => 'admin-dashboard/admin-continuation'
                        ],
                        [
                            'label' => 'admin-generate-continuation-details-title',
                            'route' => 'admin-dashboard/admin-continuation/detail'
                        ],
                        [
                            'label' => 'admin-continuations-checklist-reminders-title',
                            'route' => 'admin-dashboard/admin-continuation/checklist-reminder',
                        ]
                    ]
                ],
                [
                    'id' => 'admin-dashboard/continuations-irfo',
                    'label' => 'admin-continuations-title',
                    'route' => 'admin-dashboard/admin-continuation',
                    'pages' => [
                        [
                            'label' => 'admin-generate-continuations-title',
                            'route' => 'admin-dashboard/admin-continuation',
                        ],
                        [
                            'id' => 'admin-dashboard/admin-continuation/irfo-psv-auth',
                            'label' => 'admin-generate-continuation-details-title',
                            'route' => 'admin-dashboard/admin-continuation/irfo-psv-auth',
                        ],
                        [
                            'label' => 'admin-continuations-checklist-reminders-title',
                            'route' => 'admin-dashboard/admin-continuation/checklist-reminder',
                        ]
                    ]
                ],
                [
                    'id'    => 'admin-dashboard/admin-payment-processing',
                    'label' => 'Payment processing',
                    'route' => 'admin-dashboard/admin-payment-processing',
                    'pages' => [
                        [
                            'id' => 'admin-dashboard/admin-payment-processing/misc-fees',
                            'label' => 'Miscellaneous fees',
                            'route' => 'admin-dashboard/admin-payment-processing/misc-fees',
                            'pages' => [
                                [
                                    'id' => 'admin-dashboard/admin-payment-processing/misc-fees/details',
                                    'label' => 'Fee details',
                                    'route' => 'admin-dashboard/admin-payment-processing/misc-fees/fee_action',
                                ],
                                [
                                    // note, we can't nest the transaction breadcrumb under fee details
                                    // due to conflicting 'action' params :(
                                    'id' => 'admin-dashboard/admin-payment-processing/misc-fees/transaction',
                                    'label' => 'Transaction details',
                                    'route' =>
                                        'admin-dashboard/admin-payment-processing/misc-fees/fee_action/transaction',
                                ],
                            ],
                        ],
                    ]
                ],
                [
                    'id'    => 'admin-dashboard/admin-your-account',
                    'label' => 'Your account',
                    'route' => 'admin-dashboard/admin-your-account',
                    'pages' => [
                        [
                            'id' => 'admin-dashboard/admin-your-account/details',
                            'label' => 'Details',
                            'route' => 'admin-dashboard/admin-your-account/details'
                        ],
                        [
                            'id' => 'admin-dashboard/admin-your-account/change-password',
                            'label' => 'Change password',
                            'route' => 'change-password',
                            'class' => 'js-modal-ajax',
                        ],
                    ]
                ],
                [
                    'label' => 'Reports',
                    'id' => 'admin-dashboard/admin-report',
                    'route' => 'admin-dashboard/admin-report',
                    'pages' => [
                        [
                            'id' => 'admin-dashboard/admin-report/ch-alerts',
                            'label' => 'Companies House alerts',
                            'route' => 'admin-dashboard/admin-report/ch-alerts'
                        ],
                        [
                            'id' => 'admin-dashboard/admin-report/cpms',
                            'label' => 'CPMS Financial report',
                            'route' => 'admin-dashboard/admin-report/cpms'
                        ],
                        [
                            'id' => 'admin-dashboard/admin-report/interim-refunds',
                            'label' => 'Interim Refunds',
                            'route' => 'admin-dashboard/admin-report/interim-refunds',
                        ],
                        [
                            'id' => 'admin-dashboard/admin-report/cpid-class',
                            'label' => 'CPID classification',
                            'route' => 'admin-dashboard/admin-report/cpid-class',
                        ],
                        [
                            'id' => 'admin-dashboard/admin-report/exported-reports',
                            'label' => 'Exported reports',
                            'route' => 'admin-dashboard/admin-report/exported-reports',
                        ],
                        [
                            'id' => 'admin-dashboard/admin-report/pi',
                            'label' => 'Public Inquiry listings',
                            'route' => 'admin-dashboard/admin-report/pi',
                        ],
                        [
                            'id' => 'admin-dashboard/admin-report/cases/open',
                            'label' => 'Open cases',
                            'route' => 'admin-dashboard/admin-report/cases/open',
                        ],
                        [
                            'id' => 'admin-dashboard/admin-report/upload',
                            'label' => 'Upload reports',
                            'route' => 'admin-dashboard/admin-report/upload',
                        ],
                    ],
                ],
                [
                    'id' => 'admin-dashboard/admin-manage-system-parameters',
                    'label' => 'System parameters',
                    'route' => 'admin-dashboard/admin-system-parameters',
                ],
                [
                    'id' => 'admin-dashboard/admin-feature-toggle',
                    'label' => 'Feature toggle',
                    'route' => 'admin-dashboard/admin-feature-toggle',
                ],
                [
                    'id' => 'admin-dashboard/admin-permits',
                    'label' => 'Permits',
                    'route' => 'admin-dashboard/admin-permits',
                    'pages' => [
                        [
                            'id' => 'admin-dashboard/admin-permits/stocks',
                            'label' => 'Permit system settings',
                            'route' => 'admin-dashboard/admin-permits/stocks'
                        ],
                        [
                            'id' => 'admin-dashboard/admin-permits/ranges',
                            'label' => 'Stock',
                            'route' => 'admin-dashboard/admin-permits/ranges'
                        ],
                        [
                            'id' => 'admin-dashboard/admin-permits/windows',
                            'label' => 'Window',
                            'route' => 'admin-dashboard/admin-permits/windows'
                        ],
                        [
                            'id' => 'admin-dashboard/admin-permits/jurisdiction',
                            'label' => 'Devolved administration',
                            'route' => 'admin-dashboard/admin-permits/jurisdiction'
                        ],
                        [
                            'id' => 'admin-dashboard/admin-permits/sectors',
                            'label' => 'Sectors',
                            'route' => 'admin-dashboard/admin-permits/sectors'
                        ],
                        [
                            'id' => 'admin-dashboard/admin-permits/scoring',
                            'label' => 'Scoring',
                            'route' => 'admin-dashboard/admin-permits/scoring'
                        ],
                        [
                            'id' => 'admin-dashboard/admin-permits/exported-reports',
                            'label' => 'Exported reports',
                            'route' => 'admin-dashboard/admin-permits/exported-reports',
                        ],
                    ],
                ],
                [
                    'id' => 'admin-dashboard/admin-system-info-message',
                    'label' => 'System messages',
                    'route' => 'admin-dashboard/admin-system-info-message',
                ],
                [
                    'label' => 'Task allocation rules',
                    'id' => 'admin-dashboard/task-allocation-rules',
                    'route' => 'admin-dashboard/task-allocation-rules',
                ],
                [
                    'label' => 'Content Management',
                    'id' => 'admin-dashboard/content-management',
                    'route' => 'admin-dashboard/admin-email-templates',
                    'pages' => [
                        [
                            'id' => 'admin-dashboard/content-management/email-templates',
                            'label' => 'Email Templates',
                            'route' => 'admin-dashboard/admin-email-templates',
                        ],
                        [
                            'id' => 'admin-dashboard/content-management/document-templates',
                            'label' => 'Document Templates',
                            'route' => 'admin-dashboard/admin-document-templates',
                        ],
                        [
                            'id' => 'admin-dashboard/content-management/editable-translations',
                            'label' => 'Editable Translations',
                            'route' => 'admin-dashboard/admin-editable-translations',
                        ],
                        [
                            'id' => 'admin-dashboard/content-management/replacements',
                            'label' => 'Replacements',
                            'route' => 'admin-dashboard/admin-replacements',
                        ]
                    ],
                ],
                [
                    'label' => 'Data retention',
                    'id' => 'admin-dashboard/admin-data-retention',
                    'route' => 'admin-dashboard/admin-data-retention',
                    'pages' => [
                        [
                            'id' => 'admin-dashboard/admin-data-retention/review',
                            'label' => 'Review',
                            'route' => 'admin-dashboard/admin-data-retention/review',
                            'pages' => [
                                [
                                    'id' => 'admin-dashboard/admin-data-retention/review/records',
                                    'label' => 'Records',
                                    'route' => 'admin-dashboard/admin-data-retention/review/records'
                                ],
                            ]
                        ],
                        [
                            'id' => 'admin-dashboard/admin-data-retention/export',
                            'label' => 'Export',
                            'route' => 'admin-dashboard/admin-data-retention/export'
                        ],
                        [
                            'id' => 'admin-dashboard/admin-data-retention/rule-admin',
                            'label' => 'Rule admin',
                            'route' => 'admin-dashboard/admin-data-retention/rule-admin'
                        ],
                    ],
                ],
                [
                    'id' => 'admin-dashboard/admin-fee-rates',
                    'label' => 'Fee rates',
                    'route' => 'admin-dashboard/admin-fee-rates',
                    'pages' => [
                        [
                            'id' => 'admin-dashboard/admin-fee-rates',
                            'label' => 'Fee Rates',
                            'route' => 'admin-dashboard/admin-fee-rates',
                        ],
                    ],
                ],
            ]
        ]
    ]
];
