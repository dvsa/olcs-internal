<?php

/**
 * Case Hearing Controller
 *
 * @author Ian Lindsay <ian@hemera-business-services.co.uk>
 */
namespace Olcs\Controller\Cases\Hearing;

// Olcs
use Olcs\Controller as OlcsController;
use Olcs\Controller\Traits as ControllerTraits;

/**
 * Case Hearing Controller
 *
 * @author Ian Lindsay <ian@hemera-business-services.co.uk>
 */
class HearingAppealController extends OlcsController\CrudAbstract
{
    use ControllerTraits\CaseControllerTrait;

    /**
     * Identifier name
     *
     * @var string
     */
    protected $identifierName = 'appeal';

    /**
     * Table name string
     *
     * @var string
     */
    protected $tableName = 'appeal';

    /**
     * Holds the form name
     *
     * @var string
     */
    protected $formName = 'appeal';

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
    protected $service = 'Appeal';

    /**
     * Holds the navigation ID,
     * required when an entire controller is
     * represneted by a single navigation id.
     */
    protected $navigationId = 'case_hearings_appeals_stays';

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
                'details',
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
            'appeal' => array(
                'properties' => 'ALL'
            )
        )
    );

    /**
     * Holds the Stay Data Bundle
     *
     * @var array
     */
    protected $stayDataBundle = array(
        'children' => array(
            'stayType' => array(
                'properties' => array(
                    'id',
                    'description'
                )
            ),
            'outcome' => array(
                'properties' => array(
                    'id',
                    'description'
                )
            ),
            'case' => array(
                'properties' => array(
                    'id'
                )
            )
        )
    );

    /**
     * Holds the details view
     *
     * @return array|\Zend\Http\Response|\Zend\View\Model\ViewModel
     */
    protected $detailsView = '/case/hearing-appeal/details';

    /**
     * Ensure index action redirects to details action
     *
     * @return array|mixed|\Zend\Http\Response|\Zend\View\Model\ViewModel
     */
    public function indexAction()
    {
        return $this->redirectToIndex();
    }

    /**
     * Override to redirect to details page
     *
     * @return mixed|\Zend\Http\Response
     */
    public function redirectToIndex()
    {
        return $this->redirectToRoute(null, ['action' => 'details'], [], true);
    }

    /**
     * Details action. Shows appeals and stays (if appeal exists)
     *
     * @return \Zend\View\Model\ViewModel
     */
    public function detailsAction()
    {
        $caseId = $this->getCase()['id'];
        $appeal = $this->getAppealData($caseId);
        $stayRecords = $this->getStayData($caseId);

        $view = $this->getView([]);

        $this->getViewHelperManager()
            ->get('placeholder')
            ->getContainer($this->getIdentifierName())
            ->set($appeal);

        $view->setTemplate($this->detailsView);
        $view->setVariable('case', $this->getCase());
        $view->setVariable('stayRecords', $stayRecords);

        return $this->renderView($view);
    }

    /**
     * Gets stay data for use on the index page
     *
     * @param int $caseId
     * @return array
     */
    private function getStayData($caseId)
    {
        $stayRecords = array();

        $stayResult = $this->makeRestCall(
            'Stay',
            'GET',
            array('case' => $caseId),
            $this->stayDataBundle
        );

        //need a better way to do this...
        foreach ($stayResult['Results'] as $stay) {
            $stayRecords[$stay['stayType']['id']][] = $stay;
        }

        return $stayRecords;
    }

    /**
     * Retrieves appeal data
     *
     * @param int $caseId
     * @return array
     */
    private function getAppealData($caseId)
    {
        $bundle = [
            'children' => [
                'reason' => [
                    'properties' => [
                        'id',
                        'description'
                    ]
                ],
                'outcome' => [
                    'properties' => [
                        'id',
                        'description'
                    ]
                ]
            ],
        ];

        $appealResult = $this->makeRestCall(
            'Appeal',
            'GET',
            array(
                'case' => $caseId,
                'bundle' => json_encode($bundle)
            )
        );

        $appeal = array();

        if (!empty($appealResult['Results'][0])) {
            $appeal = $appealResult['Results'][0];
        }

        return $appeal;
    }
}
