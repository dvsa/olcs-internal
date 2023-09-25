<?php

use Common\Service\Table\Formatter\SumColumns;

return array(
    'variables' => array(
        'title' => 'transport-manager.responsibilities.table.licences'
    ),
    'columns' => array(
        array(
            'title' => 'Manager type',
            'name' => 'tmType',
            'formatter' => function ($row) {
                $routeParams = ['id' => $row['id'], 'action' => 'edit-tm-licence'];
                $url = $this->generateUrl($routeParams);
                return '<a class="govuk-link" href="' . $url . '">' .
                ((isset($row['tmType']['description']) && $row['tmType']['description']) ?
                    $row['tmType']['description'] : 'Not set') . '</a>';
            },
        ),
        array(
            'title' => 'Licence No',
            'name' => 'licence',
            'formatter' => function ($row) {
                $routeParams = ['licence' => $row['licence']['id']];
                $url = $this->generateUrl($routeParams, 'lva-licence/transport_managers');
                return '<a class="govuk-link" href="'. $url . '">' . $row['licence']['licNo'] . '</a>';
            },
        ),
        array(
            'title' => 'Operator name',
            'name' => 'operatorName',
            'formatter' => function ($row) {
                return $row['licence']['organisation']['name'];
            },
        ),
        array(
            'title' => 'Hours per week',
            'isNumeric' => true,
            'name' => 'hours',
            'formatter' => SumColumns::class,
            'columns' => ['hoursMon', 'hoursTue', 'hoursWed', 'hoursThu', 'hoursFri', 'hoursSat', 'hoursSun']
        ),
    )
);
