<?php

return array(
    'variables' => array(
        'title' => 'Notes',
        'titleSingular' => 'Note',
    ),
    'settings' => array(
        'crud' => array(
            'actions' => array(
                'add' => array('class' => 'govuk-button'),
                'edit' => array('requireRows' => true, 'class' => 'govuk-button govuk-button--secondary js-require--multiple'),
                'delete' => array('requireRows' => true, 'class' => 'govuk-button govuk-button--warning js-require--multiple')
            )
        ),
        'paginate' => array(
            'limit' => array(
                'default' => 10,
                'options' => array(10, 25, 50, 100)
            )
        ),
        'useQuery' => true
    ),
    'columns' => array(
        array(
            'title' => 'Created',
            'formatter' => 'NoteUrl',
        ),
        array(
            'title' => 'Author',
            'formatter' => function ($data, $column) {

                $column['formatter'] = 'Name';

                return $this->callFormatter($column, $data['createdBy']['contactDetails']['person']);
            }
        ),
        array(
            'title' => 'Note',
            'formatter' => 'Comment',
            'name' => 'comment',
        ),
        array(
            'title' => 'Note type',
            'formatter' => function ($data) {

                /**
                 * @see https://jira.i-env.net/browse/OLCS-10256
                 */

                switch ($data['noteType']['id']) {
                    case 'note_t_lic':
                    case 'note_t_tm':
                    case 'note_t_org':
                        return $data['noteType']['description'];
                    case 'note_t_permit':
                        $desc = $data['noteType']['description'];

                        if (isset($data['irhpApplication']['id'])) {
                            $desc .= ' ' . $data['irhpApplication']['id'];
                        }

                        return $desc;
                    case 'note_t_app':
                        return $data['noteType']['description'] . ' ' . $data['application']['id'];
                    case 'note_t_case':
                        return $data['noteType']['description'] . ' ' . $data['case']['id'];
                }

                return 'BR ' . $data['busReg']['regNo'];
            }
        ),
        array(
            'title' => 'Priority',
            'name' => 'priority',
        ),
        array(
            'title' => 'markup-table-th-action', //this is a view partial from olcs-common
            'width' => 'checkbox',
            'format' => '{{[elements/radio]}}'
        ),
    )
);
