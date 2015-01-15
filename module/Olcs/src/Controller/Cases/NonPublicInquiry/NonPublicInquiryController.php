<?php

/**
 * Case Non Public Inquiry Complaint Controller
 *
 * @author Craig Reasbeck <craig.reasbeck@valtech.co.uk>
 */

namespace Olcs\Controller\Cases\NonPublicInquiry;

// Olcs
use Olcs\Controller\CrudAbstract;
use Olcs\Controller\Interfaces\CaseControllerInterface;
use Olcs\Controller\Traits as ControllerTraits;

use Zend\View\Model\ViewModel;

/**
 * Case Non Public Inquiry Controller
 *
 * @author Craig Reasbeck <craig.reasbeck@valtech.co.uk>
 */
class NonPublicInquiryController extends CrudAbstract implements CaseControllerInterface
{
    use ControllerTraits\CaseControllerTrait;
    use ControllerTraits\CloseActionTrait;

    /**
     * Identifier name
     *
     * @var string
     */
    protected $identifierName = 'id';

    /**
     * Identifier key
     *
     * @var string
     */
    protected $identifierKey = 'id';

    /**
     * Table name string
     *
     * @var string
     */
    protected $tableName = 'NonPi';

    /**
     * Holds the form name
     *
     * @var string
     */
    protected $formName = 'NonPi';

    /**
     * The current page's extra layout, over and above the
     * standard base template, a sibling of the base though.
     *
     * @var string
     */
    protected $pageLayout = 'case-section';

    /**
     * For most case crud controllers, we use the case/inner-layout
     * layout file. Except submissions.
     *
     * @var string
     */
    protected $pageLayoutInner = 'layout/case-details-subsection';

    /**
     * Holds the navigation ID,
     * required when an entire controller is
     * represneted by a single navigation id.
     */
    protected $navigationId = 'case_hearings_appeals_non_public_inquiry';

    protected $placeholderName = 'nonPi';

    protected $detailsView = 'pages/case/non-public-inquiry';

    /**
     * Holds an array of variables for the
     * default index list page.
     */
    protected $listVars = [
        'case'
    ];

    /**
     * Contains the name of the view placeholder for the table.
     *
     * @var string
     */
    protected $tableViewPlaceholderName = 'table';

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
     * Holds the isAction
     *
     * @var boolean
    */
    protected $isAction = false;

    /**
     * Holds the service name
     *
     * @var string
     */
    protected $service = 'Hearing';

    /**
     * Holds the Data Bundle
     *
     * @var array
     */
    protected $dataBundle = [
        'properties' => 'ALL',
        'children' => [
            'venue' => [
                'properties' => 'ALL',
            ],
            'case' => [
                'properties' => 'ALL',
            ],
            'presidingTc' => [
                'properties' => 'ALL',
            ],
            'hearingType' => [
                'properties' => 'ALL',
            ]
        ]
    ];

    protected $inlineScripts = ['forms/non-pi', 'shared/definition'];

    public function indexAction()
    {
        //die(__FUNCTION__);
        return $this->redirect()->toRoute('case_non_pi', ['action' => 'details'], [], true);
    }

    public function detailsAction()
    {
        $this->identifierName = 'case';
        $this->identifierKey = 'case';

        return parent::detailsAction();
    }

    /**
     * @param array $data
     * @return array
     */
    public function processLoad($data)
    {
        $data = parent::processLoad($data);

        if (isset($data['fields']['venueOther']) && $data['fields']['venueOther'] != '') {
            $data['fields']['venue'] = 'other';
        }

        return $data;
    }

    /**
     * Overrides the parent, make sure there's nothing there shouldn't be in the optional fields
     *
     * @param array $data
     * @return \Zend\Http\Response
     */
    public function processSave($data)
    {
        if ($data['fields']['venue'] != 'other') {
            $data['fields']['venueOther'] = null;
        }

        parent::processSave($data, false);

        return $this->redirectToIndex();
    }

    /**
     * Returns the action array to generate the close/reopen button for a given entity
     *
     * @param integer $id|null
     * @return array|null
     */
    public function generateCloseActionButtonArray($id = null)
    {
        $id = empty($id) ? $this->getIdToClose($id) : $id;
        $dataService = $this->getDataService();

        if ($dataService instanceof CloseableInterface) {
            if ($dataService->canReopen($id)) {
                return $this->generateButton('reopen');
            }
            if ($dataService->canClose($id)) {
                return $this->generateButton('close');
            }
        }
        return null;
    }
}
