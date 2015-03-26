<?php

return array(
    'variables' => array(
        'title' => 'Publications'
    ),
    'settings' => array(
        'crud' => array(
            'actions' => array(
                'generate' => array('class' => 'primary', 'requireRows' => true),
                'publish' => array('class' => 'secondary', 'requireRows' => true)
            )
        ),
        'paginate' => array(
            'limit' => array(
                'default' => 25,
                'options' => array(10, 25, 50)
            )
        )
    ),
    'attributes' => array(
    ),
    'columns' => array(
        array(
            'title' => 'Traffic Area',
            'name' => 'trafficArea',
            'formatter' => function ($row) {
                return $row['trafficArea']['name'];
            }
        ),
        array(
            'title' => 'Publication No.',
            'formatter' => function ($data, $column) {
                if ($data['pubStatus']['id'] != 'pub_s_new') {
                    return '<a href="' . $this->generateUrl(
                        [
                            'publication' => $data['id'],
                            'docIdentifier' => $data['document']['identifier']
                        ],
                        'admin-dashboard/admin-publication/download',
                        true
                    ) . '">' . $data['publicationNo'] . '</a>';
                } else {
                    return $data['publicationNo'];
                }
            },
            'name' => 'publicationNo',
            'sort' => 'publicationNo',
        ),
        array(
            'title' => 'Document Type',
            'name' => 'pubType',
        ),
        array(
            'title' => 'Document status',
            'formatter' => function ($data) {
                return $data['pubStatus']['description'];
            }
        ),
        array(
            'title' => 'Publication date',
            'name' => 'pubDate',
            'sort' => 'pubDate',
            'formatter' => 'Date'
        ),
        array(
            'title' => '',
            'width' => 'checkbox',
            'format' => '{{[elements/radio]}}'
        ),
    )
);
