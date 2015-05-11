<?php

namespace Olcs\Controller\Cases\PublicInquiry;

use Olcs\Controller as OlcsController;
use Olcs\Controller\Traits as ControllerTraits;
use Common\Service\Data\CategoryDataService;
use Zend\View\Model\ViewModel;
use Olcs\Controller\Interfaces\CaseControllerInterface;
use Common\Service\Data\PiHearing as PiHearingService;

/**
 * Class HearingController
 * @package Olcs\Controller\Cases\PublicInquiry
 */
class HearingController extends OlcsController\CrudAbstract implements CaseControllerInterface
{
    use ControllerTraits\CaseControllerTrait;
    use ControllerTraits\PublicationControllerTrait;
    use ControllerTraits\GenerateActionTrait;

    /**
     * Identifier name
     *
     * @var string
     */
    protected $identifierName = 'id';

    /**
     * Holds the form name
     *
     * @var string
     */
    protected $tableName = 'piHearing';

    /**
     * Holds the form name
     *
     * @var string
     */
    protected $formName = 'PublicInquiryHearing';

    /**
     * Holds the service name
     *
     * @var string
     */
    protected $service = 'PiHearing';

    /**
     * The current page's extra layout, over and above the
     * standard base template, a sibling of the base though.
     *
     * @var string
     */
    protected $pageLayout = 'case-section';

    /**
     * Holds the navigation ID,
     * required when an entire controller is
     * represneted by a single navigation id.
     */
    protected $navigationId = 'case_hearings_appeals_public_inquiry';

    /**
     * For most case crud controllers, we use the layout/case-details-subsection
     * layout file. Except submissions.
     *
     * @var string
     */
    protected $pageLayoutInner = 'layout/case-details-subsection';

    /**
     * Holds an array of variables for the
     * default index list page.
     */
    protected $listVars = [
        'case',
        'pi'
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
            )
        )
    );

    /**
     * Holds the Data Bundle
     *
     * @var array
     */
    protected $dataBundle = [
        'children' => [
            'piVenue' => [],
            'presidingTc' => [],
            'presidedByRole' => [],
            'pi' => [
                'children' => [
                    'publicationLinks' => [
                        'children' => [
                            'publication' => [
                                'children' => [
                                    'pubStatus'
                                ]
                            ],
                        ],
                    ]
                ],
            ],
        ]
    ];

    protected $inlineScripts = ['forms/pi-hearing', 'shared/definition', 'table-actions'];

    /**
     * @var int $licenceId cache of licence id for a given case
     */
    protected $licenceId;

    /**
     * @return mixed|\Zend\Http\Response
     */
    public function redirectToIndex()
    {
        return $this->redirectToRouteAjax(
            'case_pi',
            ['action'=>'details'],
            ['code' => '303'], // Why? No cache is set with a 303 :)
            true
        );
    }

    /**
     * Get data for form
     *
     * @return array
     */
    public function getDataForForm()
    {
        $data = parent::getDataForForm();
        $pi = $this->params()->fromRoute('pi');
        $data['fields']['pi'] = $pi;

        //if there's an add, we can preload data from a previous hearing
        if ($this->params()->fromRoute('action') == 'add') {
            $params = [
                'pi' => $pi,
                'limit' => 1,
                'sort' => 'hearingDate',
                'order' => 'desc'
            ];

            $previousHearing = $this->getServiceLocator()
                ->get('DataServiceManager')
                ->get(PiHearingService::class)
                ->fetchList($params);

            if (!empty($previousHearing['Results'])) {
                $hearing = $previousHearing['Results'][0];

                //if the venue other field is filled in, override venue id
                if ($hearing['piVenueOther'] != '') {
                    $hearing['piVenue']['id'] = 'other';
                }

                $populateFields = [
                    'piVenue' => $hearing['piVenue']['id'],
                    'piVenueOther' => $hearing['piVenueOther'],
                    'presidingTc' => $hearing['presidingTc']['id'],
                    'presidedByRole' => $hearing['presidedByRole']['id'],
                    'witnesses' => $hearing['witnesses']
                ];

                $data['fields'] = array_merge($populateFields, $data['fields']);
            }
        }

        return $data;
    }

    /**
     * @param array $data
     * @return array
     */
    public function processLoad($data)
    {
        // get pi as not set when adding first hearing.
        // Should really alter bundle to query pi table, not hearings.
        $piId = $this->getFromRoute('pi');
        $pi = $this->makeRestCall('Pi', 'GET', $piId);

        $data['agreedDate'] = $pi['agreedDate'];

        $data = parent::processLoad($data);

        $this->getServiceLocator()->get('Common\Service\Data\Sla')->setContext('pi_hearing', $data);

        if (isset($data['fields']['piVenueOther']) && $data['fields']['piVenueOther'] != '') {
            $data['fields']['piVenue'] = 'other';
        }

        return $data;
    }

    public function onInvalidPost($form)
    {
        $this->processLoad($this->loadCurrent());
    }

    /**
     * Overrides the parent, make sure there's nothing there shouldn't be in the optional fields
     *
     * @param array $data
     * @return \Zend\Http\Response
     */
    public function processSave($data)
    {
        if ($data['fields']['piVenue'] != 'other') {
            $data['fields']['piVenueOther'] = null;
        }

        if ($data['fields']['isCancelled'] != 'Y') {
            $data['fields']['cancelledReason'] = null;
            $data['fields']['cancelledDate'] = null;
        }

        if ($data['fields']['isAdjourned'] != 'Y') {
            $data['fields']['adjournedReason'] = null;
            $data['fields']['adjournedDate'] = null;
        }

        $savedData = parent::processSave($data, false);

        //check whether we need to publish
        $post = $this->params()->fromPost();

        if (isset($post['form-actions']['publish'])) {
            $hearingData = $data['fields'];

            $hearingData['text2'] = $hearingData['details'];

            //if this was an add we need the id of the new record
            if (empty($hearingData['id'])) {
                $hearingData['id'] = $savedData['id'];
            }

            $publishData = [
                'pi' => $hearingData['pi'],
                'text2' => $hearingData['text2'],
                'hearingData' => $hearingData
            ];
            $case = $this->getCase();

            if ($case->isTm()) {
                $publishData['publicationSectionConst'] = 'tmHearingSectionId';
                $publishData['case'] = $case;
                $publishData['transportManager'] = $case['transportManager']['id'];
                $this->getPublicationHelper()->publishMultiple(
                    $publishData,
                    $hearingData['trafficAreas'],
                    $hearingData['pubType'],
                    'TmHearingPublicationFilter'
                );
            } else {
                $publishData['publicationSectionConst'] = 'hearingSectionId';
                $this->getPublicationHelper()->publish(
                    $publishData,
                    'HearingPublicationFilter'
                );
            }
        }

        $data['fields']['pi'] = [
            'id' =>$data['fields']['pi'],
            'piStatus' => 'pi_s_schedule',
        ];

        $this->addTask($data);

        return $this->redirectToIndex();
    }

    /**
     * @param array $data
     */
    public function addTask(array $data)
    {
        if (isset($data['fields']) && is_array($data['fields'])) {
            $data = $data['fields'];
        }

        if ($data['isAdjourned'] == 'Y') {

            $task = [
                'assignedByUser' => $this->getLoggedInUser(),
                'assignedToUser' => $this->getLoggedInUser(),
                'assignedToTeam' => 2, // @NOTE: not stubbed yet
                'cases' => $this->getCase()['id']
            ];

            if (isset($this->getCase()['licence']['id'])) {
                $task['licence'] = $this->getCase()['licence']['id'];
            }

            if (isset($this->getCase()['licence']['application']['id'])) {
                $task['application'] = $this->getCase()['licence']['application']['id'];
            }

            $task['description'] = 'Verify adjournment of case';
            $task['actionDate'] = date(
                'Y-m-d',
                mktime(date("H"), date("i"), date("s"), date("n"), date("j")+7, date("Y"))
            );
            $task['urgent'] = 'Y';
            $task['category'] = CategoryDataService::CATEGORY_COMPLIANCE;
            $task['subCategory'] = CategoryDataService::TASK_SUB_CATEGORY_HEARINGS_APPEALS;

            $service = $this->getTaskService();
            $service->create($task);
        }
    }

    /**
     * @return \Common\Service\Data\Task
     */
    public function getTaskService()
    {
        return $this->getServiceLocator()->get('DataServiceManager')->get('Common\Service\Data\Task');
    }

    /**
     * Alter form for TM cases, set pubType and trafficAreas to be visible for publishing
     *
     * @param \Common\Controller\Form $form
     * @return \Common\Controller\Form
     */
    public function alterForm($form)
    {
        $data = $this->loadCurrent();

        // set the label to republish if *any* publication has NOT been printed
        if (!empty($data['pi']['publicationLinks'])) {
            foreach ($data['pi']['publicationLinks'] as $pl) {
                if (isset($pl['publication']) && $pl['publication']['pubStatus']['id'] != 'pub_s_printed') {
                    $form->get('form-actions')->get('publish')->setLabel('Republish');
                }
            }
        }

        $case = $this->getCase();
        if (!($case->isTm())) {
            $form->get('fields')->remove('pubType');
            $form->get('fields')->remove('trafficAreas');
        }

        return $form;
    }

    /**
     * Route for document generate action redirects
     * @see Olcs\Controller\Traits\GenerateActionTrait
     * @return string
     */
    protected function getDocumentGenerateRoute()
    {
        return 'case_licence_docs_attachments/entity/generate';
    }

    /**
     * Route params for document generate action redirects
     * @see Olcs\Controller\Traits\GenerateActionTrait
     * @return array
     */
    protected function getDocumentGenerateRouteParams()
    {
        return [
            'case' => $this->getFromRoute('case'),
            'licence' => $this->getLicenceIdForCase(),
            'entityType' => 'hearing',
            'entityId' => $this->getFromRoute('id')
        ];
    }

    /**
     * Gets licence id from route or backend, caching it in member variable
     */
    protected function getLicenceIdForCase()
    {
        if (is_null($this->licenceId)) {
            $this->licenceId = $this->getQueryOrRouteParam('licence');
            if (empty($this->licenceId)) {
                $case = $this->getCase();
                $this->licenceId = $case['licence']['id'];
            }
        }
        return $this->licenceId;
    }
}
