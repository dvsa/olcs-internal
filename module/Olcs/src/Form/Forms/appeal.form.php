<?php

return [
    'appeal' => [
        'name' => 'appeal',
        'attributes' => [
            'method' => 'post',
        ],
        'type' => 'Common\Form\Form',
        'fieldsets' => [
            [
                'name' => 'details',
                'options' => [
                    'label' => 'Appeal Details'
                ],
                'elements' => [
                    'deadlineDate' => [
                        'type' => 'dateSelectWithEmpty',
                        'label' => 'Appeal deadline',
                        'required' => false
                    ],
                    'appealDate' => [
                        'type' => 'dateSelectWithEmpty',
                        'label' => 'Date of appeal',
                        'filters' => '\Common\Form\Elements\InputFilters\DateRequired'
                    ],
                    'appealNumber' => [
                        'label' => 'Appeal number',
                        'type' => 'text',
                        'filters' => '\Common\Form\Elements\InputFilters\TextMax20'
                    ],
                    'reason' => [
                        'label' => 'Reason',
                        'type' => 'select',
                        'value_options' => 'appeal_reasons',
                        'filters' => '\Common\Form\Elements\InputFilters\SelectEmpty'
                    ],
                    'outlineGround' => [
                        'type' => 'text',
                        'filters' => '\Common\Form\Elements\InputFilters\TextMax4000',
                        'label' => 'Outline ground',
                        'class' => 'extra-long'
                    ],
                    'hearingDate' => [
                        'type' => 'dateSelectWithEmpty',
                        'label' => 'Date of appeal hearing',
                        'required' => false
                    ],
                    'decisionDate' => [
                        'type' => 'dateSelectWithEmpty',
                        'label' => 'Date of decision',
                        'required' => false
                    ],
                    'papersDue' => [
                        'type' => 'dateSelectWithEmpty',
                        'label' => 'Papers due at tribunal',
                        'required' => false
                    ],
                    'papersSent' => [
                        'type' => 'dateSelectWithEmpty',
                        'label' => 'Papers sent date',
                        'required' => false
                    ],
                    'outcome' => [
                        'label' => 'Outcome',
                        'type' => 'select',
                        'value_options' => 'appeal_outcomes',
                        'filters' => '\Common\Form\Elements\InputFilters\SelectEmpty'
                    ],
                    'comment' => [
                        'type' => 'text',
                        'filters' => '\Common\Form\Elements\InputFilters\TextMax4000',
                        'label' => 'Comments',
                        'class' => 'extra-long'
                    ]
                ]
            ]
        ],
        'elements' => [
            'case' => [
                'type' => 'hidden'
            ],
            'id' => [
                'type' => 'hidden'
            ],
            'version' => [
                'type' => 'hidden'
            ],
            'submit' => [
                'type' => 'submit',
                'label' => 'Save',
                'class' => 'action--primary large'
            ]
        ]
    ]
];
