<?php

return [
    'revoke' => [
        'name' => 'revoke',
        'attributes' => [
            'method' => 'post',
        ],
        'type' => 'Common\Form\Form',
        'fieldsets' => [
            array(
                'name' => 'main',
                'attributes' => array(
                    'class' => 'actions-container'
                ),
                'options' => array(0),
                'elements' => [
                    'reasons' => [
                        'type' => 'multiselect',
                        'label' => 'Select legislation',
                        'help-block' => 'Use CTRL to select multiple'
                    ],
                    'presidingTc' => [
                        'type' => 'select',
                        'label' => 'TC/DTC agreed',
                    ],
                    'ptrAgreedDate' => [
                        'type' => 'dateSelectWithEmpty',
                        'label' => 'PTR agreed date',
                        'filters' => '\Common\Form\Elements\InputFilters\DateRequired',
                    ],
                    'closedDate' => [
                        'type' => 'dateSelectWithEmpty',
                        'label' => 'Closed date',
                    ],
                    'comment' => [
                        'type'  => 'textarea',
                        'label' => 'Notes',
                        'class' => 'extra-long',
                        'filters' => '\Common\Form\Elements\InputFilters\TextMax4000',
                    ],
                    'case' => [
                        'type' => 'hidden'
                    ],
                    'id' => [
                        'type' => 'hidden'
                    ],
                    'version' => [
                        'type' => 'hidden'
                    ]
                ],
            ),
            array(
                'name' => 'form-actions',
                'attributes' => array(
                    'class' => 'actions-container'
                ),
                'options' => array(0),
                'elements' => array(
                    'submit' => array(
                        'enable' => true,
                        'type' => 'submit',
                        'filters' => '\Common\Form\Elements\InputFilters\ActionButton',
                        'label' => 'Save',
                        'class' => 'action--primary large'
                    ),
                    'cancel' => array(
                        'enable' => true,
                        'type' => 'submit',
                        'filters' => '\Common\Form\Elements\InputFilters\ActionButton',
                        'label' => 'Cancel',
                        'class' => 'action--secondary large'
                    )
                )
            )

        ],
    ]
];
