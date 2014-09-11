<?php

/**
 * Case Prohibition Controller
 *
 * @author Ian Lindsay <ian@hemera-business-services.co.uk>
 */
namespace Olcs\Controller;

// Olcs
use Olcs\Controller as OlcsController;
use Olcs\Controller\Traits as ControllerTraits;

    /**
     * Case Prohibition Controller
     *
     * @author Ian Lindsay <ian@hemera-business-services.co.uk>
     */
class CaseProhibitionController extends OlcsController\CrudAbstract
{
    use ControllerTraits\CaseControllerTrait;

    /**
     * Identifier name
     *
     * @var string
     */
    protected $identifierName = 'prohibition';

    /**
     * Holds the form name
     *
     * @var string
     */
    protected $formName = 'prohibition';

    /**
     * The current page's extra layout, over and above the
     * standard base template, a sibling of the base though.
     *
     * @var string
     */
    protected $pageLayout = 'case';

    /**
     * For most case crud controllers, we use the case/inner-layout
     * layout file. Except submissions.
     *
     * @var string
     */
    protected $pageLayoutInner = 'case/inner-layout';

    /**
     * Holds the service name
     *
     * @var string
     */
    protected $service = 'Prohibition';

    /**
     * Holds the navigation ID,
     * required when an entire controller is
     * represneted by a single navigation id.
     */
    protected $navigationId = 'case_details_prohibitions';

    /**
     * Holds an array of variables for the
     * default index list page.
     */
    protected $listVars = [
        'case',
    ];

    /**
     * Data map
     *
     * @var array
    */
    protected $dataMap = array(
        'main' => array(
            'mapFrom' => array(
                'fields',
                'base',
            )
        )
    );

    /**
     * Holds the isAction
     *
     * @var boolean
     */
    protected $isAction = false;

    /**
     * Holds the Data Bundle
     *
     * @var array
    */
    protected $dataBundle = array(
        'children' => array(
            'case' => array(
                'properties' => array(
                    'id'
                )
            ),
            'prohibitionType' => array(
                'properties' => array(
                    'id',
                    'description'
                )
            )
        )
    );

    public function replaceIds(array $array, array $ids)
    {
        foreach ($array as $key => $value) {
            if (in_array($key, $ids)) {
                $array[$key] = $value['id'];
            }
        }

        return $array;
    }

    /**
     * Map the data on load
     *
     * @param array $data
     * @return array
     */
    protected function processLoad($data)
    {
        $data = $this->replaceIds($data, ['prohibitionType', 'case']);
        $data['fields'] = $data;
        $data['base'] = $data;

        //die('<pre>' . print_r($data, 1));

        if (empty($data)) {

            $data['base']['case'] = $this->getQueryOrRouteParam('case');
        }

        return $data;
    }

    /**
     * Save data
     *
     * @param array $data
     * @param string $service
     * @return array
     */
    /* protected function save($data, $service = null)
    {
        $data['createdBy'] = $this->getLoggedInUser();

        //die('<pre>' . print_r($data, 1));

        parent::save($data, $service);

        return $this->redirect(null, ['action' => 'index', 'conviction' => null], [], true);
    } */

    /**
     * Index action loads the form data
     *
     * @return \Zend\Form\Form
     */
    /* public function indexAction()
    {
        $caseId = $this->fromRoute('case');
        $licence = $this->fromRoute('licence');

        $this->checkForCrudAction('case_prohibition', array('case' => $caseId, 'licence' => $licence), 'id');

        $table = $this->generateProhibitionTable($caseId);

        $form = $this->generateProhibitionNoteForm($caseId);

        $view = $this->getView();
        $tabs = $this->getTabInformationArray();

        $case = $this->getCase($caseId);
        $summary = $this->getCaseSummaryArray($case);

        $view->setVariables(
            [
                'table' => $table,
                'summary' => $summary,
                'commentForm' => $form,
            ]
        );

        $view->setTemplate('case/manage');
        return $view;
    } */

    /**
     * Add action
     *
     * @return void|\Zend\View\Model\ViewModel
     */
    /* public function addAction()
    {
        $form = $this->generateFormWithData(
            'prohibition',
            'processAddProhibition',
            ['case' => $this->fromRoute('case')]
        );

        $view = $this->getView(
            [
                'params' => [
                    'pageTitle' => 'Add prohibition',
                    'pageSubTitle' => ''
                ],
                'form' => $form,
            ]
        );

        $view->setTemplate('prohibition/form');

        return $this->renderView($view);
    } */

    /**
     * Edit action
     *
     * @return void|\Zend\View\Model\ViewModel
     */
    /* public function editAction()
    {
        $licenceId = $this->fromRoute('licence');
        $caseId = $this->fromRoute('case');
        $prohibitionId = $this->fromRoute('id');

        //check if the add/edit button has been pressed on the defect table, redirect if necessary
        $action = $this->fromPost('action');
        $defect = $this->fromPost('id');

        switch ($action) {
            case 'Add':
                return $this->redirectToRoute('case_prohibition/defect', ['action' => 'add'], [], true);
            case 'Edit':
                return $this->redirectToRoute(
                    'case_prohibition/defect',
                    ['action' => 'edit', 'defect' => $defect],
                    [],
                    true
                );
            case 'Delete':
                return $this->redirectToRoute(
                    'case_prohibition/defect',
                    ['action' => 'delete', 'defect' => $defect],
                    [],
                    true
                );
        }

        $this->setBreadcrumb(
            array(
                'licence_case_list/pagination' => array('licence' => $licenceId),
                'case_prohibition' => array('licence' => $licenceId, 'case' => $caseId)
            )
        );

        $bundle = $this->getBundle();

        $details = $this->makeRestCall(
            'Prohibition',
            'GET',
            array(
                'id' => $prohibitionId,
                'bundle' => json_encode($bundle)
            )
        );

        if (empty($details)) {
            return $this->notFoundAction();
        }

        $data = $this->formatDataForForm($details);

        $form = $this->generateFormWithData(
            'prohibition',
            'processEditProhibition',
            $data
        );

        $view = $this->getView(
            [
                'params' => [
                    'pageTitle' => 'Edit prohibition',
                    'pageSubTitle' => ''
                ],
                'form' => $form,
                'table' => $this->generateProhibitionDefectTable($prohibitionId)
            ]
        );

        $view->setTemplate('prohibition/form');

        return $view;
    } */

    /**
     * Formats data for use in the form
     *
     * @param array $results
     * @return array
     */
    private function formatDataForForm($results)
    {
        $formatted = array();

        $formatted['fields']['prohibitionDate'] = $results['prohibitionDate'];
        $formatted['fields']['clearedDate'] = $results['clearedDate'];
        $formatted['fields']['vrm'] = $results['vrm'];
        $formatted['fields']['imposedAt'] = $results['imposedAt'];
        $formatted['fields']['prohibitionType'] = $results['prohibitionType']['id'];
        $formatted['fields']['isTrailer'] = $results['isTrailer'];

        $formatted['id'] = $results['id'];
        $formatted['case_id'] = $results['case']['id'];
        $formatted['version'] = $results['version'];

        return $formatted;
    }

    /**
     * Gets a table of prohibitions for the specified case
     *
     * @param int $caseId
     * @return string
     */
    /* private function generateProhibitionTable($caseId)
    {
        $results = $this->makeRestCall('Prohibition', 'GET', array('case' => $caseId), $this->getBundle());

        return $this->buildTable('prohibition', $results);
    } */

    /**
     * Gets a table of defects for the prohibition
     *
     * @param int $prohibitionId
     * @return string
     */
    private function generateProhibitionDefectTable($prohibitionId)
    {
        $bundle = [
            'children' => [
                'prohibition' => [
                    'properties' => [
                        'id'
                    ]
                ]

            ]
        ];

        $results = $this->makeRestCall(
            'ProhibitionDefect',
            'GET',
            array(
                'prohibition' => $prohibitionId,
                'bundle' => json_encode($bundle)
            )
        );

        if ($results['Count']) {
            return $this->buildTable('prohibitionDefect', $results);
        }

        return $this->buildTable('prohibitionDefect', []);
    }

    /**
     * Creates and returns the prohibition form.
     *
     * @param int $caseId
     * @return \Zend\Form\Form
     */
    private function generateProhibitionNoteForm($caseId)
    {
        $bundle = array(
            'properties' => array(
                'id',
                'version',
                'prohibitionNote'
            )
        );

        $prohibitionNote = $this->makeRestCall('Cases', 'GET', array('id' => $caseId), $bundle);

        if (!isset($prohibitionNote['id'])) {
            $prohibitionNote['id'] = $caseId;
        }

        $data = [
            'main' => $prohibitionNote
        ];

        $form = $this->generateForm(
            'prohibition-comment',
            'saveProhibitionNoteForm'
        );

        $form->setData($data);

        return $form;
    }

    /**
     * Saves the prohibition notes form.
     *
     * @param array $data
     * @return \Zend\Http\Response
     */
    public function saveProhibitionNoteForm($data)
    {
        if (!empty($data['main']['id'])) {
            $this->processEdit($data['main'], 'Cases');
        } else {
            $this->processAdd($data['main'], 'Cases');
        }

        return $this->redirectToRoute('case_prohibition', array(), array(), true);
    }

    /**
     * Processes the add prohibition form
     *
     * @param array $data
     * @return \Zend\Http\Response
     */
    public function processAddProhibition($data)
    {
        $formatted = $this->formatForSave($data);

        $result = $this->processAdd($formatted, 'Prohibition');

        if (isset($result['id'])) {
            return $this->redirectToIndex();
        }

        return $this->redirectToRoute('case_prohibition', ['action' => 'add'], [], true);
    }

    /**
     * Processes the edit prohibition form
     *
     * @param array $data
     * @return \Zend\Http\Response
     */
    public function processEditProhibition($data)
    {
        $formattedData = $this->formatForSave($data);

        $result = $this->processEdit($formattedData, 'Prohibition');

        if (empty($result)) {
            return $this->redirectToIndex();
        }

        return $this->redirectToRoute('case_prohibition', ['action' => 'edit'], [], true);
    }

    /**
     * @param array $data
     * @return array
     */
    private function formatForSave($data)
    {
        $formatted = $data['fields'];

        $formatted['id'] = $data['id'];
        $formatted['case'] = $data['case_id'];
        $formatted['version'] = $data['version'];

        return $formatted;
    }

    /**
     * Gets a search bundle
     *
     * @return array
     */
    private function getBundle()
    {
        return array(
            'children' => array(
                'case' => array(
                    'properties' => array(
                        'id'
                    )
                ),
                'prohibitionType' => array(
                    'properties' => array(
                        'id',
                        'description'
                    )
                )
            )
        );
    }

    /**
     * Should return the name of the service to call for deleting the item
     *
     * @return string
     */
    public function getDeleteServiceName()
    {
        return 'Prohibition';
    }
}
