<?php

return array(
    'variables' => array(
        'title' => 'transport-manager.otherlicences.table',
        'within_form' => true
    ),
    'settings' => array(
        'crud' => array(
            'actions' => array(
                'add-other-licence-licences' => array('label' => 'Add', 'class' => 'govuk-button'),
            ),
        ),
    ),
    'columns' => array(
        array(
            'title' => 'transport-manager.otherlicences.table.lic_no',
            'name' => 'licNo',
            'type' => 'Action',
            'action' => 'edit-other-licence-licences'
        ),
        array(
            'title' => 'transport-manager.otherlicences.table.role',
            'name' => 'role',
            'formatter' => 'RefData'
        ),
        array(
            'title' => 'transport-manager.otherlicences.table.operating_centres',
            'name' => 'operatingCentres',
        ),
        array(
            'title' => 'transport-manager.otherlicences.table.total_auth_vehicles',
            'isNumeric' => true,
            'name' => 'totalAuthVehicles',
        ),
        array(
            'title' => 'transport-manager.otherlicences.table.hours_per_week',
            'isNumeric' => true,
            'name' => 'hoursPerWeek',
        ),
        array(
            'title' => 'markup-table-th-remove', //this is a view partial from olcs-common
            'type' => 'ActionLinks',
            'deleteInputName' => 'table[action][delete-other-licence-licences][%d]'
        ),
    )
);
