<?php

/**
 * Transport Manager Processing Note Controller
 *
 * @author Alex Peshkov <alex.peshkov@valtech.co.uk>
 * @author Dan Eggleston <dan@stolenegg.com>
 */
namespace Olcs\Controller\TransportManager\Processing;

use Olcs\Controller\TransportManager\Processing\AbstractTransportManagerProcessingController;
use Common\Controller\CrudInterface;
use Olcs\Controller\Traits\DeleteActionTrait;
use Zend\View\Model\ViewModel;

/**
 * Transport Manager Processing Note Controller
 *
 * @author Alex Peshkov <alex.peshkov@valtech.co.uk>
 * @author Dan Eggleston <dan@stolenegg.com>
 */
class TransportManagerProcessingNoteController extends AbstractTransportManagerProcessingController implements
    CrudInterface
{
    use DeleteActionTrait;

    /**
     * @var string
     */
    protected $section = 'processing-notes';

    /**
     * @var string
     */
    protected $routePrefix  = 'transport-manager/processing';

    /**
     * @var string
     */
    protected $noteType = 'note_t_tm';

    /**
     * @var string
     */
    protected $service = 'Note'; // for DeleteActionTrait

    /**
     * Displays the notes list or redirects to CRUD action
     *
     * @return ViewModel|\Zend\Http\Response
     */
    public function indexAction()
    {
        $tmId        = $this->getFromRoute('transportManager');
        $routePrefix = $this->getRoutePrefix();
        $noteType    = $this->getNoteType();
        $action      = $this->getFromPost('action');
        $id          = $this->getFromPost('id');

        switch ($action) {
            case 'Add':
                return $this->redirectToRoute(
                    $routePrefix . '/add-note',
                    [
                        'action' => strtolower($action),
                        'noteType' => $noteType,
                        'linkedId' => $tmId,
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

        $table = $this->getNotesTable($tmId, $action);

        $this->loadScripts(['forms/filter', 'table-actions']);

        $view = $this->getViewWithTm(['table' => $table]);
        $view->setTemplate('partials/table');

        return $this->renderView($view);
    }

    /**
     * Adds a note
     *
     * @return \Zend\View\Model\ViewModel
     */
    public function addAction()
    {
        $tmId = $this->getFromRoute('transportManager');

        $form = $this->generateFormWithData(
            'Note',
            'processAddNotes',
            [
                'transportManager' => $tmId,
                'noteType' => $this->getNoteType(),
            ]
        );

        if ($this->getResponse()->getContent() !== '') {
            return $this->getResponse();
        }

        $view = $this->getViewWithTm(['form' => $form]);
        $view->setTemplate('partials/form');

        return $this->renderView($view, 'transport-manager.processing.notes.add.title');
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

        $this->processAdd($data, 'Note');

        return $this->redirectToIndex();
    }


    /**
     * Edits a note
     *
     * @return \Zend\View\Model\ViewModel
     */
    public function editAction()
    {
        $id = $this->getFromRoute('id');

        $note = $this->getServiceLocator()->get('Entity\Note')->getNote($id);

        $data = [
            'main' => [
                'comment' => $note['comment'],
                'priority' => $note['priority']
            ],
            'id' => $note['id'],
            'version' => $note['version']
        ];

        $form = $this->generateFormWithData('Note', 'processEditNotes', $data);

        if ($this->getResponse()->getContent() !== '') {
            return $this->getResponse();
        }

        $this->getServiceLocator()->get('Helper\Form')->disableElement($form, 'main->comment');

        $view = $this->getViewWithTm(['form' => $form]);
        $view->setTemplate('partials/form');

        return $this->renderView($view, 'transport-manager.processing.notes.modify.title');
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

        // don't allow these fields to be changed
        unset($data['noteType'], $data['linkedId'], $data['transportManager'], $data['comment']);

        $data['lastModifiedBy'] = $this->getLoggedInUser();

        $this->processEdit($data, 'Note');

        return $this->redirectToIndex();
    }

    /**
     * Gets a table of existing notes
     *
     * @param int $transportManagerId
     * @param string $action
     * @return Common\Service\Table\TableBuilder
     */
    protected function getNotesTable($transportManagerId)
    {
        $searchData = array(
            'sort'             => 'priority',
            'order'            => 'DESC',
            'noteType'         => $this->getNoteType(),
            'transportManager' => $transportManagerId,
            'page'  => 1,
            'limit' => 10,
        );

        $requestQuery = $this->getRequest()->getQuery();
        $requestArray = $requestQuery->toArray();

        $filters = array_merge($searchData, $requestArray);

        // if noteType is set to all
        if (isset($filters['noteType']) && !$filters['noteType']) {
            unset($filters['noteType']);
        }

        $form = $this->getForm('note-filter');
        $form->remove('csrf'); // we never post
        $form->setData($filters);

        $this->setTableFilters($form);

        $resultData = $this->getServiceLocator()->get('Entity\Note')->getNotesList($filters);

        $formattedResult = $this->appendRoutePrefix($resultData, $this->getRoutePrefix());

        $filters['query'] = $this->getRequest()->getQuery();

        return $this->getTable('note', $formattedResult, $filters, false);
    }

    /**
     * Appends the route prefix to each row for the table formatter / url generator
     *
     * @param array $resultData
     * @return array
     */
    protected function appendRoutePrefix($notes, $routePrefix)
    {
        $formatted = [];

        foreach ($notes['Results'] as $key => $result) {
            $formatted[$key] = $result;
            $formatted[$key]['routePrefix'] = $routePrefix;
        }

        $notes['Results'] = $formatted;

        return $notes;
    }

    protected function getRoutePrefix()
    {
        return $this->routePrefix;
    }

    protected function getNoteType()
    {
        return $this->noteType;
    }

    public function redirectToIndex()
    {
        return $this->redirectToRouteAjax(
            $this->getRoutePrefix() . '/notes',
            ['transportManager' => $this->getFromRoute('transportManager')]
        );
    }
}
