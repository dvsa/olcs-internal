<?php

/**
 * Case Controller
 *
 * @author Rob Caiger <rob@clocal.co.uk>
 */

namespace Olcs\Controller\Cases;

use Zend\View\Model\ViewModel;
use Olcs\Controller as OlcsController;
use Olcs\Controller\Traits as ControllerTraits;

/**
 * Case Controller
 *
 * @author Rob Caiger <rob@clocal.co.uk>
 */
class CaseController extends OlcsController\CrudAbstract
{
    use ControllerTraits\CaseControllerTrait;

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
    protected $tableName = 'case';

    /**
     * Holds the form name
     *
     * @var string
     */
    protected $formName = 'cases';

    /**
     * The current page's extra layout, over and above the
     * standard base template
     *
     * @var string
     */
    protected $pageLayout = 'case';

    protected $pageLayoutInner = 'case/inner-layout';

    /**
     * Holds the service name
     *
     * @var string
     */
    protected $service = 'Cases';

    /**
     * Data map
     *
     * @var array
     */
    protected $dataMap = array(
        'main' => array(
            'mapFrom' => array(
                'fields'
            )
        )
    );

    /**
     * Holds the Data Bundle
     *
     * @var array
     */
    protected $dataBundle = array(
        'children' => array(
            'submissionSections' => array(
                'properties' => array(
                    'id',
                    'description'
                )
            ),
            'legacyOffences' => array(
                'properties' => 'ALL',
            ),
            'caseType' => array(
                'properties' => 'id',
            ),
            'categorys' => array(
                'properties' => 'ALL',
            ),
            'licence' => array(
                'properties' => 'ALL',
                'children' => array(
                    'status' => array(
                        'properties' => array('id')
                    ),
                    'licenceType' => array(
                        'properties' => array('id')
                    ),
                    'goodsOrPsv' => array(
                        'properties' => array('id')
                    ),
                    'trafficArea' => array(
                        'properties' => 'ALL'
                    ),
                    'organisation' => array(
                        'properties' => 'ALL',
                        'children' => array(
                            'type' => array(
                                'properties' => array('id')
                            )
                        )
                    )
                )
            )
        )
    );

    protected $detailsView = 'case/overview';

    /**
     * Holds an array of variables for the default
     * index list page.
     */
    protected $listVars = [
        'licence',
        'application',
        'transportManager'
    ];

    /**
     * This action is the case overview page.
     */
    /* public function overviewAction()
    {
        return $this->redirect()->toRoute('case', ['action' => 'details'], [], true);
    } */

    public function redirectAction()
    {
        return $this->redirect()->toRoute('case', ['action' => 'details'], [], true);
    }

    /**
     * Simple redirect to index.
     */
    public function redirectToIndex()
    {
        if (!$case = func_get_arg(0)) {
            throw new \LogicException('Case missing');
        }

        //die('<pre>' . print_r(['action' => 'details', $this->getIdentifierName() => $case], 1));
        return $this->redirectToRoute(
            'case',
            ['action' => 'details', $this->getIdentifierName() => $case],
            ['code' => '303'], // Why? No cache is set with a 303 :)
            true
        );
    }

    public function processSave($data)
    {
        if (empty($data['id'])) {
            $data['fields']['openDate'] = date('Y-m-d');
        }

        $result = parent::processSave($data, false);

        return $this->redirectToIndex($result['id']);
    }

    /**
     * List of cases. Moved to Licence controller's cases method.
     *
     * @return void
     */
    public function indexAction()
    {
        return $this->redirect()->toRoute('case', ['action' => 'details'], [], true);
        //return $this->redirect()->toRoute('licence/cases', [], [], true);
    }

    /**
     * Add a new case
     *
     * @return ViewModel
     */
    public function addAction()
    {
        $this->pageLayout = 'licence';
        $this->pageLayoutInner = null;

        return parent::saveThis();
    }

    public function editAction()
    {
        $this->pageLayout = 'case';
        $this->pageLayoutInner = null;

        return parent::saveThis();
    }

    public function processLoad($data)
    {
        $data = parent::processLoad($data);

        if ($licence = $this->getQueryOrRouteParam('licence', null)) {
            $data['licence'] = $licence;
            $data['fields']['licence'] = $licence;
        }

        return $data;
    }
}
