<?php

/**
 * Cases SubmissionSectionComments Controller
 *
 * @author Shaun Lizzio <shaun.lizzio@valtech.co.uk>
 */
namespace Olcs\Controller\Cases\Submission;

use Olcs\Controller as OlcsController;
use Zend\View\Model\ViewModel;
use Olcs\Controller\Cases\AbstractController as AbstractCasesController;
use Olcs\Controller\Traits as ControllerTraits;

/**
 * Cases SubmissionSectionComment Controller
 *
 * @author Shaun Lizzio <shaun.lizzio@valtech.co.uk>
 */
class SubmissionSectionCommentController extends OlcsController\CrudAbstract
{
    use ControllerTraits\CaseControllerTrait;

    /**
     * Identifier name
     *
     * @var string
     */
    protected $identifierName = 'id';

    /**
     * Table name string
     *
     * @var string
     */
    protected $tableName = 'submissionSectionComment';

    /**
     * Holds the form name
     *
     * @var string
     */
    protected $formName = 'SubmissionSectionComment';

    /**
     * The current page's extra layout, over and above the
     * standard base template, a sibling of the base though.
     *
     * @var string
     */
    protected $pageLayout = 'case';

    protected $detailsView = 'case/submission/details';

    protected $pageLayoutInner = null;

    /**
     * Holds the service name
     *
     * @var string
     */
    protected $service = 'SubmissionSectionComments';

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

    protected $action = false;

    /**
     * Holds the Data Bundle
     *
     * @var array
     */
    protected $dataBundle = array(
        'properties' => 'ALL',
        'children' => array(
            'submission' => array(
                'properties' => 'ALL'
            )
        )
    );

    /**
     * Holds the navigation ID,
     * required when an entire controller is
     * represented by a single navigation id.
     */
    protected $navigationId = 'case_submissions';

    /**
     * Map the data on load
     *
     * @param array $data
     * @return array
     */
    public function processLoad($data)
    {
        $data = parent::processLoad($data);
        $data['fields']['submission'] = $this->params()->fromRoute('submission');
        $data['fields']['submissionSection'] = $this->params()->fromRoute('submissionSection');

        return $data;
    }

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
        $submissionId = $this->params()->fromRoute('submission');
        return $this->redirectToRoute(submission, ['id' => $submissionId, 'action' => 'details'], [], true);
    }

    /**
     * Call parent process save and return result. Public method to allow unit testing
     *
     * @param array $data
     * @return array
     */
    public function callParentProcessSave($data)
    {
        // pass false to prevent default redirect back to index action
        // and return result of the save
        return parent::processSave($data, false);
    }

    /**
     * Call parent process load and return result. Public method to allow unit testing
     *
     * @param array $data
     * @return array
     */
    public function callParentSave($data, $service = null)
    {
        return parent::save($data, $service);
    }
}
