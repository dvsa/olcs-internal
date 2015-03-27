<?php

return array(
    'variables' => array(
        'title' => 'Environmental complaints',
    ),
    'settings' => array(),
    'columns' => array(
        array(
            'title' => 'Case No.',
            'formatter' => function ($row) {
                return '<a href="' . $this->generateUrl(
                    array('case' => $row['caseId'], 'tab' => 'overview'),
                    'case_opposition',
                    false
                ) . '">' . $row['caseId'] . '</a>';
            }
        ),
        array(
            'title' => 'Date received',
            'formatter' => 'Date',
            'name' => 'complaintDate'
        ),
        array(
            'title' => 'Complainant',
            'formatter' => function ($data, $column) {
                return $data['complainantContactDetails']['person']['forename'] . ' ' .
                $data['complainantContactDetails']['person']['familyName'];
            }
        ),
        array(
            'title' => 'OC Address',
            'formatter' => function ($data, $column) {
                $column['formatter'] = 'Address';
                $addressList = '';
                foreach ($data['ocComplaints'] as $ocComplaint) {
                    $addressList .= $this->callFormatter($column, $ocComplaint['operatingCentre']['address']) . '<br
                    />';
                }

                return $addressList;
            },
            'name' => 'ocComplaints'
        ),
        array(
            'title' => 'Description',
            'name' => 'description'
        ),
        array(
            'title' => 'Status',
            'formatter' => function ($data, $column) {
                return $data['status']['description'];
            }
        )
    )
);
