<?php

return array(
    'variables' => array(
        'action_route' => [
            'route' => 'case_pi',
            'params' => ['action' => 'details']
        ],
        'title' => 'Hearings',
    ),
    'settings' => array(
        'crud' => array(
            'actions' => array(
                'add' => array('class' => 'primary'),
                'edit' => array('requireRows' => true),
            ),
        ),
        'paginate' => array(
            'limit' => array(
                'default' => 10,
                'options' => array(10, 25, 50)
            )
        )
    ),
    'attributes' => array(
    ),
    'columns' => array(
        array(
            'title' => '&nbsp;',
            'width' => 'checkbox',
            'format' => '{{[elements/radio]}}'
        ),
        array(
            'title' => 'Date of PI',
            'formatter' => function ($data, $column) {
                $url = $this->generateUrl(
                    ['action' => 'edit', 'id' => $data['id'], 'pi' => $data['pi']['id']],
                    'case_pi_hearing', true
                );
                $column['formatter'] = 'Date';
                //return '<a href="' . $url . '" class="js-modal-ajax">'
                return '<a href="' . $url . '">'
                . date('d/m/Y', strtotime($data['hearingDate'])) . '</a>';
            },
            'name' => 'id'
        ),
        array(
            'title' => 'Venue',
            'formatter' => function ($data) {
                return (isset($data['piVenue']['name']) ? $data['piVenue']['name'] : $data['piVenueOther']);
            }
        ),
        array(
            'title' => 'Adjourned',
            'name' => 'isAdjourned'
        ),
        array(
            'title' => 'Cancelled',
            'name' => 'isCancelled'
        ),
    )
);
