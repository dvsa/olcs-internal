<?php

use Common\Service\Table\Formatter\Date;

return array(
    'variables' => array(
        'title' => 'Prohibitions',
        'titleSingular' => 'Prohibition',
        'empty_message' => 'There are no prohibitions'
    ),
    'settings' => array(
        'crud' => array(
            'actions' => array(
                'add' => array('class' => 'govuk-button', 'label' => 'Add prohibition'),
                'edit' => array('requireRows' => true, 'class' => 'govuk-button govuk-button--secondary js-require--one'),
                'delete' => array('requireRows' => true, 'class' => 'govuk-button govuk-button--warning js-require--one')
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
            'title' => 'markup-table-th-action', //this is a view partial from olcs-common
            'width' => 'checkbox',
            'format' => '{{[elements/radio]}}'
        ),
        array(
            'title' => 'Prohibition date',
            'formatter' => function ($data, $column) {
                    $column['formatter'] = Date::class;
                    return '<a class="govuk-link" href="' . $this->generateUrl(
                        array('prohibition' => $data['id']),
                        'case_prohibition_defect',
                        true
                    ) . '">' . $this->callFormatter($column, $data) . '</a>';
            },
            'name' => 'prohibitionDate'
        ),
        array(
            'title' => 'Cleared date',
            'formatter' => Date::class,
            'name' => 'clearedDate',
        ),
        array(
            'title' => 'Vehicle',
            'format' => '{{vrm}}'
        ),
        array(
            'title' => 'Trailer',
            'formatter' => function ($data) {
                switch ($data['isTrailer']) {
                    case 'Y':
                        return 'Yes';
                    case 'N':
                        return 'No';
                    default:
                        return '-';
                }
            }
        ),
        array(
            'title' => 'Imposed at',
            'format' => '{{imposedAt}}'
        ),
        array(
            'title' => 'Type',
            'formatter' => function ($data) {
                return $data['prohibitionType']['description'];
            }
        )
    )
);
