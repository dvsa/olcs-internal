<?php

return array(
    'variables' => array(
        'title' => 'Publications'
    ),
    'settings' => array(
        'crud' => array(
            'actions' => array(
                'edit' => array('requireRows' => true),
                'delete' => array('class' => 'secondary', 'requireRows' => true)
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
            'title' => 'Created',
            'formatter' => function ($data, $column) {
                $column['formatter'] = 'Date';
                return '<a href="' . $this->generateUrl(
                    array('action' => 'edit', 'id' => $data['id']),
                    'licence/processing/publications',
                    true
                ) . '">' . $this->callFormatter($column, $data) . '</a>';
            },
            'name' => 'createdOn',
            'sort' => 'createdOn'
        ),
        array(
            'title' => 'Publication No.',
            'formatter' => function ($data) {
                return $data['publication']['publicationNo'];
            }
        ),
        array(
            'title' => 'Type',
            'formatter' => function ($data) {
                return $data['publication']['pubType'];
            }
        ),
        array(
            'title' => 'Traffic area',
            'formatter' => function ($data) {
                return $data['publication']['trafficArea']['name'];
            }
        ),
        array(
            'title' => 'Status',
            'formatter' => function ($data) {
                return $data['publication']['pubStatus']['description'];
            }
        ),
        array(
            'title' => 'Publication date',
            'formatter' => function ($data) {
                $date = new DateTime($data['publication']['pubDate']);
                return $date->format('d/m/Y');
            }
        ),
        array(
            'title' => 'Section',
            'formatter' => function ($data) {
                return $data['publicationSection']['description'];
            }
        ),
        array(
            'title' => 'Text',
            'formatter' => function ($data) {
                $string = nl2br($data['text1']) . '<br />' . $data['text2'];
                if (strlen($string) > 100) {
                    return substr($string, 0, 100) . ' [...]';
                }

                return $string;
            }
        ),
        array(
            'title' => '',
            'width' => 'checkbox',
            'format' => '{{[elements/radio]}}'
        ),
    )
);
