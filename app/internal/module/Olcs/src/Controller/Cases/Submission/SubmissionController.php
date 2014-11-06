<?php

/**
 * Cases Submission Controller
 *
 * @author Craig Reasbeck <craig.reasbeck@valtech.co.uk>
 */
namespace Olcs\Controller\Cases\Submission;

use Olcs\Controller as OlcsController;
use Zend\View\Model\ViewModel;
use Olcs\Controller\Cases\AbstractController as AbstractCasesController;
use Olcs\Controller\Traits as ControllerTraits;

/**
 * Cases Submission Controller
 *
 * @author Craig Reasbeck <craig.reasbeck@valtech.co.uk>
 */
class SubmissionController extends OlcsController\CrudAbstract
{
    use ControllerTraits\CaseControllerTrait;

    /**
     * Identifier name
     *
     * @var string
     */
    protected $identifierName = 'submission';

    /**
     * Table name string
     *
     * @var string
     */
    protected $tableName = 'submission';

    /**
     * Holds the form name
     *
     * @var string
     */
    protected $formName = 'submission';

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
    protected $service = 'Submission';

    /**
     * Holds an array of variables for the default
     * index list page.
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
            'submissionType' => array(
                'properties' => 'ALL',
            ),
            'case' => array(
                'properties' => 'ALL',
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
     * Holds all the submission section ref data with descriptions
     */
    protected $submissionSectionRefData = array();

    public function alterFormBeforeValidation($form)
    {
        $postData = $this->getFromPost('fields');
        $formData = $this->getDataForForm();

        // Intercept Submission type submit button to prevent saving
        if (isset($postData['submissionSections']['submissionTypeSubmit']) ||
            !(empty($formData['submissionType']))) {
            $this->setPersist(false);
        } else {
            // remove form-actions
            $form->remove('form-actions');
        }

        return $form;
    }

    /**
     * Override Save data to allow json encoding of submission sections
     * into submission 'dataSnapshot' field.
     *
     * @param array $data
     * @param string $service
     * @return array
     */
    public function save($data, $service = null)
    {
        // modify $data
        $submissionService = $this->getServiceLocator()->get('Olcs\Service\Data\Submission');
        $commentService = $this->getServiceLocator()->get('Olcs\Service\Data\SubmissionSectionComment');
        $params = $this->getParams(array('case', 'submission'));

        $caseId = $params['case'];
        $snapshotData = $submissionService->generateSnapshotData($caseId, $data);

        $data['dataSnapshot'] = json_encode($snapshotData);
        $data['submissionType'] = $data['submissionSections']['submissionType'];

        // save submission entity
        $result = $this->callParentSave($data);

        if (isset($result['id'])) {
            // insert
            $data['id'] = $result['id'];

            // Generate comments for all sections that are configured as type = 'text'
            $submissionSectionComments = $commentService->generateComments($caseId, $data);

            // insert comments
            foreach ($submissionSectionComments as $comment) {
                $comment['submission'] = $data['id'];
                $this->makeRestCall('SubmissionSectionComment', 'POST', $comment);
            }
        } else {
            // update
            // Generate comments for all sections that are configured as type = 'text'
            $commentResult = $commentService->updateComments($caseId, $data);

            // insert new comments
            foreach ($commentResult['add'] as $comment) {
                $comment['submission'] = $data['id'];
                $this->makeRestCall('SubmissionSectionComment', 'POST', $comment);
            }
            // remove unwanted comments
            foreach ($commentResult['remove'] as $commentId) {
                $this->makeRestCall('SubmissionSectionComment', 'DELETE', ['id' => $commentId]);
            }
        }

        return $data;
    }

    /**
     * Complete section and save
     * Redirects to details action.
     *
     * @param array $data
     * @return array
     */
    public function processSave($data)
    {
        $result = $this->callParentProcessSave($data);

        $id = isset($result['id']) ? $result['id'] : $data['fields']['id'];
        return $this->redirect()->toRoute('submission', ['action' => 'details', 'submission' => $id], [], true);
    }

    /**
     * @codeCoverageIgnore Calls parent method
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
     * Map the data on load
     *
     * @param array $data
     * @return array
     */
    public function processLoad($data)
    {
        $data = $this->callParentProcessLoad($data);

        $case = $this->getCase();

        $data['fields']['case'] = $case['id'];

        if (isset($data['submissionSections']['sections'])) {
            $sectionData = json_decode($data['submissionSections']['sections'], true);
            $data['fields']['submissionSections']['sections'] = array_keys($sectionData);
        } elseif (isset($data['dataSnapshot'])) {
            $sectionData = json_decode($data['dataSnapshot'], true);
            $data['fields']['submissionSections']['submissionType'] = $data['submissionType'];
            $data['fields']['submissionSections']['sections'] = array_keys($sectionData);
            $data['case'] = $case['id'];
            $data['fields']['id'] = $data['id'];
            $data['fields']['version'] = $data['version'];
        }

        return $data;
    }

    /**
     * @codeCoverageIgnore Calls parent method
     * Call parent process load and return result. Public method to allow unit testing
     *
     * @param array $data
     * @return array
     */
    public function callParentProcessLoad($data)
    {
        return parent::processLoad($data);
    }

    /**
     * @codeCoverageIgnore Calls parent method
     * Call parent process load and return result. Public method to allow unit testing
     *
     * @param array $data
     * @return array
     */
    public function callParentSave($data, $service = null)
    {
        return parent::save($data, $service);
    }

    /**
     * Details action - shows each section detail
     *
     * @return ViewModel
     */
    public function detailsAction()
    {
        $submissionId = $this->getQueryOrRouteParam('submission');

        $this->submissionConfig = $this->getServiceLocator()->get('config')['submission_config'];

        $submissionService = $this->getServiceLocator()
            ->get('Olcs\Service\Data\Submission');

        $submission = $submissionService->fetchSubmissionData($submissionId);

        $submission['submissionTypeTitle'] =
            $submissionService->getSubmissionTypeTitle(
                $submission['submissionType']['id']
            );

        $selectedSectionsArray =
            $submissionService->extractSelectedSubmissionSectionsData(
                $submission
            );

        $this->getViewHelperManager()
            ->get('placeholder')
            ->getContainer('selectedSectionsArray')
            ->set($selectedSectionsArray);

        $this->getViewHelperManager()
            ->get('placeholder')
            ->getContainer($this->getIdentifierName())
            ->set($submission);

        $view = $this->getView([]);
        $view->setVariable('allSections', $submissionService->getAllSectionsRefData());
        $view->setVariable('submissionConfig', $this->submissionConfig['sections']);

        $view->setTemplate($this->detailsView);

        return $this->renderView($view);
    }
}
