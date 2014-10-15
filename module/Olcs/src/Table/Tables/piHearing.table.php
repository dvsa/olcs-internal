<?php

return array(
    'variables' => array(
        'title' => 'Hearings'
    ),
    'settings' => array(
        'crud' => array(
            'formName' => 'PublicInquiryHearing',
            'actions' => array(
                'addHearing' => array('class' => 'primary', 'value' => 'Add'),
                'editHearing' => array('requireRows' => true, 'value' => 'Edit')
            )
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
            'title' => 'Date of Pi',
            'formatter' => function ($data, $column) {
                $url = $this->generateUrl(
                    ['action' => 'edit', 'id' => $data['id'], 'pi' => $data['pi']['id']],
                    'case_pi_hearing', true
                );
                $column['formatter'] = 'Date';
                return '<a href="' . $url . '">' . date('d/m/Y', strtotime($data['hearingDate'])) . '</a>';
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
