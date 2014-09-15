<?php

/**
 * Case Statement Controller
 *
 * @author Rob Caiger <rob@clocal.co.uk>
 */

namespace Olcs\Controller\Cases\Statement;

// Olcs
use Olcs\Controller as OlcsController;
use Olcs\Controller\Traits as ControllerTraits;

/**
 * Case Statement Controller
 *
 * @author Rob Caiger <rob@clocal.co.uk>
 */
class StatementController extends OlcsController\CrudAbstract
{
    use ControllerTraits\CaseControllerTrait;

    /**
     * Identifier name
     *
     * @var string
     */
    protected $identifierName = 'statement';

    /**
     * Holds the form name
     *
     * @var string
     */
    protected $formName = 'statement';

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
    protected $service = 'Statement';

    /**
     * Holds the navigation ID,
     * required when an entire controller is
     * represneted by a single navigation id.
     */
    protected $navigationId = 'case_details_statements';

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

    /**
     * Show a table of statements for the given case
     *
     * @return object
     */
    /* public function indexAction()
    {
        $caseId = $this->fromRoute('case');
        $licenceId = $this->fromRoute('licence');

        $this->setBreadcrumb(array('licence_case_list/pagination' => array('licence' => $licenceId)));

        $this->checkForCrudAction('case_statement', array('case' => $caseId));

        $results = $this->makeRestCall('Statement', 'GET', array('caseId' => $caseId));

        $variables = array('tab' => 'statements', 'table' => $this->getTable('statement', $results));

        $view = $this->getView($this->getCaseVariables($caseId, $variables));

        $view->setTemplate('case/manage');

        return $view;
    } */

    /**
     * Add statement action
     *
     * @return object
     */
    /* public function addAction()
    {
        $caseId = $this->fromRoute('case');
        $licenceId = $this->fromRoute('licence');

        $this->setBreadcrumb(
            array(
                'licence_case_list/pagination' => array('licence' => $licenceId),
                'case_statement' => array('case' => $caseId, 'licence' => $licenceId)
            )
        );

        $form = $this->generateFormWithData(
            'statement',
            'processAddStatement',
            array('case' => $caseId)
        );

        $view = $this->getView(
            [
                'params' => [
                    'pageTitle' => 'Add statement',
                    'pageSubTitle' => ''
                ],
                'form' => $form
            ]
        );

        $view->setTemplate('form');

        return $view;
    } */

    /**
     * Edit statement action
     *
     * @return object
     */
    public function editAction()
    {
        $caseId = $this->fromRoute('case');
        $licenceId = $this->fromRoute('licence');
        $statementId = $this->fromRoute('id');

        $this->setBreadcrumb(
            array(
                'licence_case_list/pagination' => array('licence' => $licenceId),
                'case_statement' => array('case' => $caseId, 'licence' => $licenceId)
            )
        );

        $bundle = array(
            'children' => array(
                'contactType' => array(
                    'properties' => array('id')
                ),
                'requestorsAddress' => array(
                    'properties' => 'ALL',
                    'children' => array(
                        'countryCode' => array(
                            'properties' => array('id')
                        )
                    )
                )
            )
        );

        $details = $this->makeRestCall('Statement', 'GET', array('id' => $statementId), $bundle);

        if (empty($details)) {
            return $this->notFoundAction();
        }

        $data = $this->formatDataForEditForm($details);
        $data['case'] = $caseId;

        $data['requestorsAddress']['countryCode'] = $data['requestorsAddress']['countryCode']['id'];

        $form = $this->generateFormWithData('statement', 'processEditStatement', $data);

        $view = $this->getView(
            [
                'params' => [
                    'pageTitle' => 'Edit statement',
                    'pageSubTitle' => ''
                ],
                'form' => $form
            ]
        );

        $view->setTemplate('form');

        return $view;
    }

    /**
     * Format the data for the edit form
     *
     * @param array $data
     * @return array
     */
    private function formatDataForEditForm($data)
    {
        $data['details'] = $data;

        $data['details']['statementType'] = 'statement_type.' . $data['details']['statementType'];
        $data['details']['contactType'] = $data['details']['contactType']['id'];

        return $data;
    }

    /**
     * Process the add post
     *
     * @param array $data
     */
    public function processAddStatement($data)
    {
        $data = $this->processDataBeforePersist($data);

        $this->processAdd($data, 'Statement');

        $this->redirect()->toRoute(
            'case_statement',
            ['case' => $this->fromRoute('case'), 'licence' => $this->fromRoute('licence')],
            [],
            false
        );
    }

    /**
     * Process the edit post
     *
     * @param array $data
     */
    public function processEditStatement($data)
    {
        $data = $this->processDataBeforePersist($data);

        $this->processEdit($data, 'Statement');

        $this->redirect()->toRoute(
            'case_statement',
            ['case'=>$this->fromRoute('case'), 'licence'=>$this->fromRoute('licence')],
            [],
            false
        );
    }

    /**
     * Method to map form data to bookmark data for replacement in the document
     * template
     *
     * @param array $data
     * @return array of bookmark mappings
     */
    public function mapDocumentData($data)
    {
        $bookmarkData = $this->getBookmarkData($data);
        $bookmarks = [];
        $bookmarks['TAName'] = $bookmarkData['licence']['trafficArea']['areaName']; // Traffic area name;
        $bookmarks['TAAddress_2'] = '<user\'s location address>'; // users location address

        $bookmarks['Address_1'] =
            $data['addresses']['requestorsAddress']['addressLine1'] . " \line " .
            $data['addresses']['requestorsAddress']['addressLine2'] . " \line " .
            $data['addresses']['requestorsAddress']['addressLine3'] . " \line " .
            $data['addresses']['requestorsAddress']['addressLine4'] . " \line" .
            $data['addresses']['requestorsAddress']['town'] . " \line" .
            $data['addresses']['requestorsAddress']['postcode'] . " \line" .
            $data['addresses']['requestorsAddress']['countryCode'];
        $bookmarks['Ref'] = '184130/' . $bookmarkData['licence']['licNo'];
        $bookmarks['Name'] = $data['requestorsForename'] . ' ' . $data['requestorsFamilyName'];
        $bookmarks['RequestMode'] = 'letter';
        $bookmarks['RequestDate'] = $data['requestedDate'];
        $bookmarks['UserKnownAs'] = '<user\'s name>';
        $bookmarks['AuthorisorTeam'] = 'Authoriser Team';
        $bookmarks['AuthorisorName2'] = '<user\'s name> <olcs job role>';
        $bookmarks['AuthorisedDecision'] = '<user\'s location address>' .
            " \line \line " .
            $data['authorisersDecision'];
        $bookmarks['AuthorisorName3'] = '<user\'s name> <olcs job role>';

        return $bookmarks;
    }

    /**
     * Gets bookmark data for the document tempate
     *
     * @param array $data
     * @return array
     */
    public function getBookmarkData($data)
    {
        $bundle = $this->getBookmarkBundle();

        $bookmarkData = $this->makeRestCall(
            'Cases',
            'GET',
            ['id' => $data['case'], 'bundle' => json_encode($bundle)]
        );

        return $bookmarkData;

    }

    /**
     * Gets the bookmark bundle
     *
     * @return array
     */
    public function getBookmarkBundle()
    {
         return array(
            'properties' => array(
                'id',
                'licence'
            ),
            'children' => array(
                'licence' => array(
                    'properties' => array(
                        'id',
                        'licNo',
                        'trafficArea',
                    ),
                    'children' => array(
                        'trafficArea' => array(
                            'properties' => array(
                                'id',
                                'areaName'
                            ),
                        )
                    )
                )
            )
        );
    }

    /**
     * Pre-persist data processing
     *
     * @param array $data
     * @return array
     */
    protected function processDataBeforePersist($data)
    {
        $data = array_merge($data, $data['details']);

        unset($data['details']);

        $data = $this->processAddressData($data, 'requestorsAddress');

        $data['statementType'] = str_replace('statement_type.', '', $data['statementType']);
        $data['contactType'] = str_replace('contact_type.', '', $data['contactType']);

        return $data;
    }
}
