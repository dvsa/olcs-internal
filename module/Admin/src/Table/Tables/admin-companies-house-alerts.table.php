<?php

return array(
    'variables' => array(
        'title' => 'crud-companies-house-alert-title',
        'titleSingular' => 'crud-companies-house-alert-title-singular',
    ),
    'settings' => array(
        'crud' => array(
            'actions' => array(
                'close' => array(
                    'class' => 'action--primary js-require--multiple',
                    'requireRows' => false
                ),
            )
        ),
        'paginate' => array(
            'limit' => array(
                'default' => 10,
                'options' => array(10, 25, 50)
            )
        )
    ),
    'columns' => array(
        array(
            'title' => 'Company No.',
            'sort' => 'companyOrLlpNo',
            'name' => 'companyOrLlpNo',
        ),
        array(
            'title' => 'Licence No.',
            'name' => 'licNo',
            'formatter' => function ($row) {
                return $row['licence']['licNo'];
            }
        ),
        array(
            'title' => 'Licence Type.',
            'name' => 'description',
            'formatter' => 'LicenceTypeShort'
        ),
        array(
            'title' => 'OLCS Company name.',
            'name' => 'organisation',
            'sort' => 'o.name',
            'formatter' => 'OrganisationLink',
        ),


        array(
            'title' => 'Reason(s)',
            'name' => 'reason',
            'formatter' => function ($row) {
                if (!isset($row['reasons'])) {
                    return '';
                }
                return implode(
                    ', ',
                    array_map(
                        function ($reason) {
                            return $reason['reasonType']['description'];
                        },
                        $row['reasons']
                    )
                );
            }
        ),
        array(
            'title' => 'Detected',
            'name' => 'createdOn',
            'formatter' => 'Date',
        ),
        array(
            'title' => 'Select',
            'width' => 'checkbox',
            'type' => 'Checkbox'
        ),
    )
);
