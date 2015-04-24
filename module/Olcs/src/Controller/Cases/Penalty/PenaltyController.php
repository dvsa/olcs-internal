<?php

/**
 * Case Penalty Controller
 *
 * @author Ian Lindsay <ian@hemera-business-services.co.uk>
 */

namespace Olcs\Controller\Cases\Penalty;

// Olcs
use Olcs\Controller as OlcsController;
use Olcs\Controller\Traits as ControllerTraits;
use Olcs\Controller\Interfaces\CaseControllerInterface;

/**
 * Case Penalty Controller
 *
 * @author Ian Lindsay <ian@hemera-business-services.co.uk>
 */
class PenaltyController extends OlcsController\CrudAbstract implements CaseControllerInterface
{
    use ControllerTraits\CaseControllerTrait;

    /**
     * Identifier
     *
     *
     * @var string
     */
    protected $identifier = 'case';

    /**
     * Identifier name
     *
     * @var string
     */
    protected $identifierName = 'case';

    /**
     * Table name string
     *
     * @var string
     */
    protected $tableName = '';

    /**
     * Name of comment box field.
     *
     * @var string
     */
    protected $commentBoxName = 'penaltiesNote';

    /**
     * The current page's extra layout, over and above the
     * standard base template, a sibling of the base though.
     *
     * @var string
     */
    protected $pageLayout = 'case-section';

    /**
     * For most case crud controllers, we use the layout/case-details-subsection
     * layout file. Except submissions.
     *
     * @var string
     */
    protected $pageLayoutInner = 'layout/case-details-subsection';

    /**
     * Holds the service name
     *
     * @var string
     */
    protected $service = 'SeriousInfringement';

    /**
     * Holds the form name
     *
     * @var string
     */
    protected $formName = 'erru-penalty';

    /**
     * Holds the navigation ID,
     * required when an entire controller is
     * represented by a single navigation id.
     */
    protected $navigationId = 'case_details_penalties';

    /**
     * @var array
     */
    protected $inlineScripts = ['table-actions'];

    /**
     * Holds the Data Bundle
     *
     * @var array
    */
    protected $dataBundle = array(
        'children' => array(
            'siCategory' => array(
                'properties' => array(
                    'description'
                )
            ),
            'siCategoryType' => array(
                'properties' => array(
                    'description'
                )
            ),
            'appliedPenalties' => array(
                'properties' => 'ALL',
                'children' => array(
                    'siPenaltyType' => array(
                        'properties' => array(
                            'id',
                            'description'
                        )
                    ),
                    'seriousInfringement' => array(
                        'properties' => array(
                            'id'
                        )
                    )
                )
            ),
            'imposedErrus' => array(
                'children' => array(
                    'siPenaltyImposedType' => array(
                        'properties' => array(
                            'id',
                            'description'
                        )
                    ),
                    'executed' => []
                )
            ),
            'requestedErrus' => array(
                'properties' => 'ALL',
                'children' => array(
                    'siPenaltyRequestedType' => array(
                        'properties' => array(
                            'id',
                            'description'
                        )
                    )
                )
            ),
            'case' => array(
                'properties' => array(
                    'erruOriginatingAuthority',
                    'erruTransportUndertakingName',
                    'erruVrm'
                )
            ),
            'memberStateCode' => array(
                'properties' => array(
                    'countryDesc'
                )
            )
        )
    );

    /**
     * Simple redirect to index.
     */
    public function redirectToIndex()
    {
        return $this->redirectToRouteAjax(
            null,
            ['action'=>'index', $this->getIdentifierName() => $this->params()->fromRoute($this->getIdentifierName())],
            ['code' => '303'], // Why? No cache is set with a 303 :)
            true
        );
    }

    /**
     * Sends the response back to Erru
     *
     * @return \Zend\Http\Response
     */
    public function sendAction()
    {
        $caseId = $this->params()->fromRoute('case');

        $response = $this->getServiceLocator()->get('BusinessServiceManager')
            ->get('Cases\Penalty\ErruAppliedPenaltyResponse')
            ->process(
                [
                    'caseId' => $caseId,
                    'user' => $this->getLoggedInUser()
                ]
            );

        if ($response->isOk()) {
            $this->addSuccessMessage($response->getMessage());
        } else {
            $this->addErrorMessage($response->getMessage());
        }

        return $this->redirectToIndex();
    }

    /**
     * Loads the tables and read only data
     *
     * @return array|\Zend\View\Model\ViewModel
     */
    public function indexAction()
    {
        //using loadListData so can use the case id in parameters, but we'll only ever have one result
        $data = $this->loadListData(['case' => $this->params()->fromRoute('case')]);

        //if a table crud button has been clicked then
        //we need to intercept the post and redirect to AppliedPenaltyController
        $postedVars = $this->params()->fromPost();

        if (isset($postedVars['action'])) {
            return $this->redirectToRoute(
                'case_penalty_edit',
                [
                    'action' => $postedVars['action'],
                    'seriousInfringement' => $data['Results'][0]['id'],
                    'id' => isset($postedVars['id']) ? $postedVars['id'] : null
                ],
                ['code' => '303'], // Why? No cache is set with a 303 :)
                true
            );
        }

        $view = $this->getView([]);

        $this->buildCommentsBoxIntoView();

        if (isset($data['Results'][0])) {
            $this->getViewHelperManager()->get('placeholder')->getContainer('penalties')->set($data['Results'][0]);
            $this->getErruTable('erru-imposed', 'imposedErrus');
            $this->getErruTable('erru-requested', 'requestedErrus');
            $this->getErruTable('erru-applied', 'appliedPenalties');
        }

        $view->setTemplate('pages/case/penalties');

        return $this->renderView($view);
    }

    /**
     * There is more than one table on the page so we can't use crud abstract
     *
     * @param string $tableName
     * @param string $dataKey
     */
    private function getErruTable($tableName, $dataKey)
    {
        //cached list data
        $listData = $this->getListData();

        if (isset($listData['Results'][0][$dataKey]) && !empty($listData['Results'][0][$dataKey])) {
            $tableData = [
                'Count' => count($listData['Results'][0][$dataKey]),
                'Results' => $listData['Results'][0][$dataKey]
            ];
        } else {
            $tableData = [
                'Count' => 0,
                'Results' => []
            ];
        }

        $this->getViewHelperManager()->get('placeholder')->getContainer($tableName)->set(
            $this->getTable($tableName, $tableData, [])
        );
    }
}
