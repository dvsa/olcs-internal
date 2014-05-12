<?php


return [
    'condition-undertaking-form' => [
        'name' => 'Complaint',
        'attributes' => [
            'method' => 'post',
        ],
        'fieldsets' => [
            [
                'name' => 'condition-undertaking',
                'elements' => [
                    'id' => [
                        'type' => 'hidden'
                    ],
                    'version' => [
                        'type' => 'hidden'
                    ],
                    'conditionType' => [
                        'type' => 'hidden'
                    ],
                    'addedVia' => [
                        'type' => 'hidden',
                        'value' => 'Case'
                    ],
                    'caseId' => [
                        'type' => 'hidden',
                    ],
                    'status' => [
                        'type' => 'hidden',
                        'value' => 'Approved',
                    ],
                    'description' => [
                        'type' => 'textarea',
                        'filters' => '\Common\Form\Elements\InputFilters\TextMax4000',
                        'label' => 'Description',
                        'class' => 'extra-long'
                    ],
                    'isFulfilled' => [
                        'type' => 'checkbox-yn',
                        'label' => 'Fulfilled',
                    ],
                    'attachedTo' => [
                        'type' => 'radio',
                        'label' => 'Attached condition to',
                        'value_options' => 'conditionundertaking_attachedTo_types'
                    ],
                    'operatingCentreAddressId' => [
                        'type' => 'select',
                        'label' => 'OC Address',
                        'value_options' => 'oc-addresses'
                    ],
               ],
           ],
        ],
        'elements' => [
            'vosaCase' => [
                'type' => 'hidden'
            ],
            'id' => [
                'type' => 'hidden'
            ],
            'version' => [
                'type' => 'hidden'
            ],
            'complaint' => [
                'type' => 'submit',
                'label' => 'Save',
                'class' => 'action--primary large'
            ],
            'cancel' => [
                'name' => 'cancel-complaint',
                'type' => 'submit',
                'label' => 'Cancel',
                'class' => 'action--secondary large'
            ]
        ]
    ]
];
