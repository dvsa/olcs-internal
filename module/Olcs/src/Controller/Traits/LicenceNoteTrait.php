<?php

/**
 * Licence Note Trait
 *
 * @author Ian Lindsay <ian@hemera-business-services.co.uk>
 */
namespace Olcs\Controller\Traits;

use Common\Exception\BadRequestException;

/**
 * Licence Note Trait
 *
 * @author Ian Lindsay <ian@hemera-business-services.co.uk>
 */
trait LicenceNoteTrait
{
    private $templatePrefix;
    private $routePrefix;
    private $redirectIndexRoute;

    /**
     * Allows the template to change based on the controller being used
     *
     * @return string
     */
    public function getTemplatePrefix()
    {
        return $this->templatePrefix;
    }

    /**
     * Allows the template to change based on the controller being used
     *
     * @param string $templatePrefix
     */
    public function setTemplatePrefix($templatePrefix)
    {
        $this->templatePrefix = $templatePrefix;
    }

    /**
     * Allows the route to change based on the controller being used
     *
     * @return string
     */
    public function getRoutePrefix()
    {
        return $this->routePrefix;
    }

    /**
     * Allows the route to change based on the controller being used
     *
     * @param string $routePrefix
     */
    public function setRoutePrefix($routePrefix)
    {
        $this->routePrefix = $routePrefix;
    }

    /**
     * Route used for index redirects
     *
     * @return string
     */
    public function getRedirectIndexRoute()
    {
        return $this->redirectIndexRoute;
    }

    /**
     * Route used for index redirects
     *
     * @param string $redirectIndexRoute
     */
    public function setRedirectIndexRoute($redirectIndexRoute)
    {
        $this->redirectIndexRoute = $redirectIndexRoute;
    }

    /**
     * Redirects to the index page, dependant on the note type
     *
     * @return \Zend\Http\Response
     */
    public function redirectToIndex()
    {
        return $this->redirectToRoute(
            $this->getRoutePrefix() . $this->getRedirectIndexRoute(),
            ['action'=>'index', $this->getIdentifierName() => null],
            ['code' => '303'], // Why? No cache is set with a 303 :)
            true
        );
    }

    /**
     * Gets a list of notes according to the specified criteria
     *
     * @param int $licenceId
     * @param int $linkedId
     * @param string $noteType
     * @param string $action
     * @param int $id
     * @param int $caseId
     * @return \Zend\View\Model\ViewModel|\Zend\Http\Response
     */
    public function getNotesList(
        $licenceId,
        $linkedId,
        $noteType = 'note_t_lic',
        $action = null,
        $id = null,
        $caseId = null
    ) {
        $routePrefix  = $this->getRoutePrefix();

        switch ($action) {
            case 'Add':
                return $this->redirectToRoute(
                    $routePrefix . '/add-note',
                    [
                        'action' => strtolower($action),
                        'noteType' => $noteType,
                        'linkedId' => $linkedId,
                        'licence' => $licenceId,
                        'case' => $caseId
                    ],
                    [],
                    true
                );
            case 'Edit':
            case 'Delete':
                return $this->redirectToRoute(
                    $routePrefix . '/modify-note',
                    ['action' => strtolower($action), 'id' => $id],
                    [],
                    true
                );
        }

        $searchData = array(
            'page' => 1,
            'sort' => 'priority',
            'order' => 'DESC',
            'limit' => 10,
            'noteType' => $noteType
        );

        $requestQuery = $this->getRequest()->getQuery();
        $requestArray = $requestQuery->toArray();

        $requestedNoteType = (isset($requestArray['noteType']) ? $requestArray['noteType'] : $noteType);

        //if we have a case id
        if (!is_null($caseId) ) {
            //if searching for licence notes or all notes from the cases page,
            //we actually need to unset the case search and add the licence id instead
            if ($requestedNoteType == 'note_t_lic' || !$requestedNoteType) {
                $caseDetail = $this->getCase($caseId);

                if (isset($caseDetail['licence']['id'])) {
                    $licenceId = $caseDetail['licence']['id'];
                    //$caseId = null;
                }
            }

            //if case id is still set
            if (!is_null($caseId) ) {
                $searchData['case'] = $caseId;
            }
        }

        //if we have a licence id
        if (!is_null($licenceId)) {
            $searchData['licence'] = $licenceId;
        }

        $filters = array_merge(
            $searchData,
            $requestArray
        );

        //if noteType is set to all
        if (isset($filters['noteType']) && !$filters['noteType']) {
            unset($filters['noteType']);
        }

        $form = $this->getForm('note-filter');
        $form->remove('csrf'); //we never post
        $form->setData($filters);

        $this->setTableFilters($form);

        $bundle = $this->getBundle();

        $resultData = $this->makeRestCall('Note', 'GET', $filters, $bundle);

        $formattedResult = $this->appendLinkedId($resultData, $routePrefix);

        $table = $this->getTable(
            'note',
            $formattedResult,
            array_merge(
                $filters,
                array('query' => $requestQuery)
            ),
            true
        );

        $this->loadScripts(['note-filter']);

        $view = $this->getView(['table' => $table]);
        $view->setTemplate($this->getTemplatePrefix() . '/notes/index');

        return $view;
    }

    /**
     * Adds a note
     *
     * @return \Zend\View\Model\ViewModel
     */
    public function addAction()
    {
        $licenceId = $this->getFromRoute('licence');
        $caseId = $this->getFromRoute('case');
        $noteType = $this->getFromRoute('noteType');
        $linkedId = $this->getFromRoute('linkedId');

        //if this is from a case, we also need to populate a licence id, which won't be in the route
        if (!is_null($caseId)) {
            $caseDetail = $this->getCase($caseId);

            if (isset($caseDetail['licence']['id'])) {
                $licenceId = $caseDetail['licence']['id'];
            }
        }

        $form = $this->generateFormWithData(
            'licence-notes',
            'processAddNotes',
            array(
                'licence' => $licenceId,
                'case' => $caseId,
                'noteType' => $noteType,
                'linkedId' => $linkedId
            )
        );

        $view = $this->getView(['form' => $form]);
        $view->setTemplate($this->getTemplatePrefix() . '/notes/form');

        return $this->renderView($view);
    }

    /**
     * Processes the add note form
     *
     * @param array $data
     * @return \Zend\Http\Response
     * @throws \Common\Exception\BadRequestException
     */
    public function processAddNotes($data)
    {
        $user = $this->getLoggedInUser();

        $data = array_merge($data, $data['main']);
        $data['createdBy'] = $user;
        $data['lastModifiedBy'] = $user;

        //checks which field to add in the linked id to
        $field = $this->getIdField($data['noteType']);

        //if this is a licence note this isn't needed, for other types of note it is expected
        if (!empty($field)) {
            if (!(int)$data['linkedId']) {
                throw new BadRequestException('Unable to link your note to the correct record');
            }

            $data[$field['field']] = $data['linkedId'];
        }

        $result = $this->processAdd($data, 'Note');

        if (isset($result['id'])) {
            return $this->redirectToIndex();
        }

        return $this->redirectToRoute($this->getRoutePrefix() . '/add-note', ['action' => 'Add'], [], true);
    }

    /**
     * Edits a note
     *
     * @return \Zend\View\Model\ViewModel
     */
    public function editAction()
    {
        $id = $this->getFromRoute('id');

        $bundle = $this->getBundle();
        $note = $this->makeRestCall('Note', 'GET', array('id' => $id), $bundle);

        $data = [
            'main' => [
                'comment' => $note['comment'],
                'priority' => $note['priority']
            ],
            'id' => $note['id'],
            'version' => $note['version']
        ];

        $form = $this->generateFormWithData(
            'licence-edit-notes',
            'processEditNotes',
            $data
        );

        $form->get('main')
            ->get('comment')
            ->setAttribute('disabled', 'disabled');

        $view = $this->getView(['form' => $form]);
        $view->setTemplate($this->getTemplatePrefix() . '/notes/form');

        return $this->renderView($view);
    }

    /**
     * Processes the edit note form
     *
     * @param array $data
     * @return \Zend\Http\Response
     */
    public function processEditNotes($data)
    {
        $data = array_merge($data, $data['main']);

        //don't allow licence, case, note type, linkedId or comment to be changed
        unset($data['licence'], $data['noteType'], $data['linkedId'], $data['comment'], $data['case']);

        $data['lastModifiedBy'] = $this->getLoggedInUser();

        $result = $this->processEdit($data, 'Note');

        if (empty($result)) {
            return $this->redirectToIndex();
        }

        return $this->redirectToRoute($this->getRoutePrefix() . '/modify-note', ['action' => 'Edit'], [], true);
    }

    /**
     * Appends the route prefix and a linked ID e.g. licence, case, application id etc.
     *
     * @param array $resultData
     * @return array
     */
    public function appendLinkedId($resultData, $routePrefix)
    {
        $formatted = [];

        foreach ($resultData['Results'] as $key => $result) {
            $field = $this->getIdField($result['noteType']['id']);

            $formatted[$key] = $result;

            $id = (isset($result[$field['field']][$field['displayId']]) ?
                $result[$field['field']][$field['displayId']] : '');

            $formatted[$key]['noteType']['description'] =
                $result['noteType']['description'] . ' ' . $id;

            $formatted[$key]['routePrefix'] = $routePrefix;
        }

        $resultData['Results'] = $formatted;

        return $resultData;
    }

    /**
     * Returns the field info used for linking the id to the appropriate record type
     *
     * @param $noteType
     * @return string
     */
    public function getIdField($noteType)
    {
        $field = [
            'field' => 'empty',
            'displayId' => 'id',
            'id' => 'id'
        ];

        switch ($noteType) {
            case 'note_t_lic':
                //we're not going to show licence number in the tables
                break;
            case 'note_t_case':
                $field['field'] = 'case';
                break;
            case 'note_t_bus':
                $field['field'] = 'busReg';
                $field['displayId'] = 'routeNo';
                break;
            case 'note_t_app':
                $field['field'] = 'application';
                break;
            case 'note_t_irfo_gv':
                $field['field'] = 'irfoGvPermit';
                break;
            case 'note_t_irfo_psv':
                $field['field'] = 'irfoPsvAuth';
                break;
        }

        return $field;
    }

    /**
     * Gets a bundle for the notes search
     *
     * @return array
     */
    public function getBundle()
    {
        return [
            'children' => [
                'createdBy' => [
                    'properties' => [
                        'name'
                    ]
                ],
                'noteType' => [
                    'properties' => [
                        'id',
                        'description'
                    ]
                ],
                'licence' => [
                    'properties' => [
                        'id',
                        'licNo'
                    ]
                ],
                'application' => [
                    'properties' => [
                        'id'
                    ]
                ],
                'irfoGvPermit' => [
                    'properties' => [
                        'id'
                    ]
                ],
                'irfoPsvAuth' => [
                    'properties' => [
                        'id'
                    ]
                ],
                'case' => [
                    'properties' => [
                        'id'
                    ]
                ],
                'busReg' => [
                    'properties' => [
                        'id',
                        'routeNo'
                    ]
                ]
            ]
        ];
    }
}
