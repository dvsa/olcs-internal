<?php

/**
 * Cases Submission Controller
 *
 * @author Craig Reasbeck <craig.reasbeck@valtech.co.uk>
 */
namespace Olcs\Controller\Cases\Submission;

use Common\Service\Data\CategoryDataService;
use Olcs\Controller as OlcsController;
use Zend\View\Model\ViewModel;
use Olcs\Controller\Traits as ControllerTraits;
use ZfcUser\Exception\AuthenticationEventException;
use Common\Controller\Traits\GenericUpload;

/**
 * Cases Submission Controller
 *
 * @author Craig Reasbeck <craig.reasbeck@valtech.co.uk>
 */
class SubmissionController extends OlcsController\CrudAbstract implements
    OlcsController\Interfaces\CaseControllerInterface
{
    use ControllerTraits\CaseControllerTrait;
    use ControllerTraits\CloseActionTrait;
    use GenericUpload;

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
    protected $pageLayout = 'case-section';

    protected $detailsView = 'pages/case/submission';

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
        'children' => array(
            'submissionType' => array(),
            'case' => array()
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

    /**
     * Entity display name
     * @var string
     */
    protected $entityDisplayName = 'submission';

    /**
     * Stores the submission data
     * @var array
     */
    protected $submissionData;

    public function alterFormBeforeValidation($form)
    {
        $postData = $this->params()->fromPost('fields');
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
     * Updates a section table, to either refresh the data or delete rows
     *
     * @return \Zend\Http\Response
     */
    public function updateTableAction()
    {
        $params['case'] = $this->params()->fromRoute('case');
        $params['section'] = $this->params()->fromRoute('section');
        $params['submission'] = $this->params()->fromRoute('submission');
        $formAction = strtolower($this->params()->fromPost('formAction'));

        if ($formAction == 'refresh-table') {
            $this->refreshTable();
        } elseif ($formAction == 'delete-row') {
            $this->deleteTableRows();
        }

        return $this->redirect()->toRoute(
            'submission',
            ['action' => 'details', 'submission' => $params['submission']],
            [],
            true
        );
    }

    /**
     * Refreshes a single section within the dataSnapshot field of a submission with the latest data
     * from the rest of the database. Redirects back to details page.
     *
     * @return void
     */
    public function refreshTable()
    {
        $params['case'] = $this->params()->fromRoute('case');
        $params['section'] = $this->params()->fromRoute('section');
        $params['subSection'] = $this->params()->fromRoute('subSection', $params['section']);
        $params['submission'] = $this->params()->fromRoute('submission');

        $submissionService = $this->getServiceLocator()->get('Olcs\Service\Data\Submission');

        $configService = $this->getServiceLocator()->get('config');
        $submissionConfig = $configService['submission_config'];

        $submission = $submissionService->fetchData($params['submission']);

        $snapshotData = json_decode($submission['dataSnapshot'], true);

        if (array_key_exists($params['section'], $snapshotData)) {
            // get fresh data
            $refreshData = $submissionService->createSubmissionSection(
                $params['case'],
                $params['section'],
                $submissionConfig['sections'][$params['section']]
            );
            // replace snapshot data
            $snapshotData[$params['section']]['data']['tables'][$params['subSection']] =
                $refreshData['tables'][$params['subSection']];
            $data['id'] = $params['submission'];
            $data['version'] = $submission['version'];
            $data['dataSnapshot'] = json_encode($snapshotData);
        }

        $this->callParentSave($data);
    }

    /**
     * Deletes a single row from a section's list data, reassigns and persists the new data back to dataSnapshot field
     * from the rest of the database. Redirects back to details page.
     *
     * @return \Zend\Http\Response
     */
    public function deleteTableRows()
    {
        $params['case'] = $this->params()->fromRoute('case');
        $params['section'] = $this->params()->fromRoute('section');
        $params['subSection'] = $this->params()->fromRoute('subSection', $params['section']);
        $params['submission'] = $this->params()->fromRoute('submission');

        $rowsToDelete = $this->params()->fromPost('id');
        $submissionService = $this->getServiceLocator()->get('Olcs\Service\Data\Submission');

        $submission = $submissionService->fetchData($params['submission']);
        $snapshotData = json_decode($submission['dataSnapshot'], true);

        if (array_key_exists($params['section'], $snapshotData) &&
        is_array($snapshotData[$params['section']]['data']['tables'][$params['subSection']])) {
            foreach ($snapshotData[$params['section']]['data']['tables'][$params['subSection']] as $key => $dataRow) {
                if (in_array($dataRow['id'], $rowsToDelete)) {
                    unset($snapshotData[$params['section']]['data']['tables'][$params['subSection']][$key]);
                }
            }
            ksort($snapshotData[$params['section']]['data']['tables'][$params['subSection']]);

            $data['id'] = $params['submission'];
            $data['version'] = $submission['version'];
            $data['dataSnapshot'] = json_encode($snapshotData);

            $this->callParentSave($data);
        }
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
        $params['case'] = $this->params()->fromRoute('case');
        $params['submission'] = $this->params()->fromRoute('submission');
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
        $data['fields']['transportManager'] = $case['transportManager']['id'];

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

        $submission = $submissionService->fetchData($submissionId);

        $this->setSubmissionData($submission);

        $case = $this->getQueryOrRouteParam('case');
        if ($submission['case']['id'] != $this->getQueryOrRouteParam('case')) {
            throw new AuthenticationEventException('Case ' . $case . ' is not associated with this submission.');
        }

        $submission['submissionTypeTitle'] =
            $submissionService->getSubmissionTypeTitle(
                $submission['submissionType']['id']
            );

        $selectedSectionsArray =
            $submissionService->extractSelectedSubmissionSectionsData(
                $submission
            );

        $selectedSectionsArray = $this->generateSectionForms($selectedSectionsArray);

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
        $view->setVariable('closeAction', $this->generateCloseActionButtonArray($submission['id']));
        $view->setVariable('readonly', $submissionService->isClosed($submission['id']));

        $view->setTemplate($this->detailsView);

        return $this->renderView($view);
    }

    /**
     * Method to generate and add the section forms for each section to the selectedSectionArray
     *
     * @param array $selectedSectionsArray
     * @return array $selectedSectionsArray
     */
    private function generateSectionForms($selectedSectionsArray)
    {
        $configService = $this->getServiceLocator()->get('config');
        $submissionConfig = $configService['submission_config'];

        if (is_array($selectedSectionsArray)) {
            foreach ($selectedSectionsArray as $sectionId => $sectionData) {
                $this->sectionId = $sectionId;

                // if we allow attachments, then create the attachments form for this section
                if (isset($submissionConfig['sections'][$sectionId]['allow_attachments']) &&
                    $submissionConfig['sections'][$sectionId]['allow_attachments']) {

                    $this->sectionSubcategory = $submissionConfig['sections'][$sectionId]['subcategoryId'];

                    // generate a unique attachment form for this section
                    $attachmentsForm = $this->getSectionForm($this->sectionId);

                    $hasProcessedFiles = $this->processFiles(
                        $attachmentsForm,
                        'attachments',
                        array($this, 'processSectionFileUpload'),
                        array($this, 'deleteSubmissionAttachment'),
                        array($this, 'loadFiles')
                    );

                    $selectedSectionsArray[$sectionId]['attachmentsForm'] = $attachmentsForm;
                }
            }
        }

        return $selectedSectionsArray;
    }

    /**
     * Calls genericUpload::deleteFile() and refreshes the submission data
     *
     * @param $documentId
     * @return bool
     */
    public function deleteSubmissionAttachment($documentId)
    {
        if ($this->deleteFile($documentId)) {
            $this->refreshSubmissionDocuments();
        }
        return true;
    }

    /**
     * Callback to handle the file upload
     *
     * @param array $file
     * @return int $id of file
     */
    public function processSectionFileUpload($file)
    {
        $request = $this->getRequest();
        if ($request->isPost()) {
            $postData = (array)$request->getPost();
            // ensure only the file only uploads to the section we are dealing with
            if ($postData['sectionId'] == $this->sectionId) {
                $data = [
                    'submission' => $this->params()->fromRoute('submission'),
                    'description' => $file['name'],
                    'isExternal' => 0,
                    'category'    => CategoryDataService::CATEGORY_SUBMISSION,
                    'subCategory' => $this->sectionSubcategory,
                ];

                if ($this->uploadFile($file, $data)) {
                    $this->refreshSubmissionDocuments();
                }
            }
        }
    }

    /**
     * Queries backend (not cached) and refresh document list for the submission
     */
    private function refreshSubmissionDocuments()
    {
        $submissionId = $this->getQueryOrRouteParam('submission');

        $submissionService = $this->getServiceLocator()
            ->get('Olcs\Service\Data\Submission');

        $submission['documents'] = $submissionService->getDocuments($submissionId);

        $this->setSubmissionData($submission);
    }

    /**
     * Handle the file upload
     *
     * @return array
     */
    public function loadFiles()
    {
        $submission = $this->getSubmissionData();
        $sectionDocuments = [];
        foreach ($submission['documents'] as $document) {
            // ensure only the file only uploads to the section we are dealing with
            if ($document['sub_category_id'] == $this->sectionSubcategory) {
                $sectionDocuments[] = $document;
            }
        }

        return $sectionDocuments;
    }

    /**
     * Generates and returns the form object for a given section, changing id and name to ensure no duplicates
     * @param $sectionId
     * @return mixed
     */
    private function getSectionForm($sectionId)
    {
        $form = $this->getServiceLocator()->get('Helper\Form')
            ->createForm('SubmissionSectionAttachment');

        $form->get('sectionId')->setValue($sectionId);
        $form->setAttribute('id', $sectionId . '-section-attachments');
        $form->setAttribute('name', $sectionId . '-section-attachments');

        return $form;
    }

    public function addAction()
    {
        $this->getServiceLocator()->get('Script')->loadFile('forms/submission');
        return parent::addAction();
    }

    public function editAction()
    {
        $this->getServiceLocator()->get('Script')->loadFile('forms/submission');
        return parent::editAction();
    }

    /**
     * @param array $submissionData
     */
    public function setSubmissionData($submissionData)
    {
        $this->submissionData = $submissionData;
    }

    /**
     * @return array
     */
    public function getSubmissionData()
    {
        return $this->submissionData;
    }
}
