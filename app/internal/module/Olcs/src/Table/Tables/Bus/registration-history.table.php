<?php

$variationNo = 1;
return array(
    'variables' => array(
        'title' => 'Registration history'
    ),
    'settings' => array(
        'crud' => array(
            'actions' => array(
                'delete' => array('requireRows' => true, 'class' => 'govuk-button govuk-button--warning js-require--one'),
            ),
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
            'title' => 'Reg No.',
            'formatter' => function ($data) {
                return '<a class="govuk-link" href="' . $this->generateUrl(
                    array('action' => 'index', 'busRegId' => $data['id']),
                    'licence/bus-details/service',
                    true
                ) . '">' . $data['regNo'] . '</a>';
            },
        ),
        array(
            'title' => 'Var No.',
            'isNumeric' => true,
            'name' => 'variationNo'
        ),
        array(
            'title' => 'Status',
            'formatter' => function ($data) {
                return $data['status']['description'];
            }
        ),
        array(
            'title' => 'Application type',
            'formatter' => function ($data, $column) {
                if ($data['isTxcApp'] == 'Y') {
                    if ($data['ebsrRefresh'] == 'Y') {
                        return $this->translator->translate('EBSR Data Refresh');
                    } else {
                        return $this->translator->translate('EBSR');
                    }
                } else {
                    return $this->translator->translate('Manual');
                }
            }
        ),
        array(
            'title' => 'Date received',
            'formatter' => 'Date',
            'name' => 'receivedDate'
        ),
        array(
            'title' => 'Date effective',
            'formatter' => 'Date',
            'name' => 'effectiveDate'
        ),
        array(
            'title' => 'End date',
            'formatter' => 'Date',
            'name' => 'endDate'
        ),
        array(
            'title' => '&nbsp;',
            'width' => 'checkbox',
            'formatter' => function ($data) {
                if ($data['isLatestVariation']) {
                    return '<input type="radio" name="id" value="' . $data['id'] . '">';
                }
            },
        ),
    )
);
