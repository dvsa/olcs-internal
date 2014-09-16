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

    protected $detailsView = 'case/submission/overview';

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
                'properties' => array(
                    'id'
                ),
            ),
            'case' => array(
                'properties' => 'ALL',
            ),
            'submissionActions' => array(
                'properties' => 'ALL',
                'children' => array(
                    'senderUser' => array(
                        'properties' => 'ALL'
                    ),
                    'recipientUser' => array(
                        'properties' => 'ALL'
                    ),
                )
            )
        )
    );

    /**
     * Save data. Also processes the submit submission select type drop down
     * in order to dictate which checkboxes to manipulate.
     *
     * @param array $data
     * @param string $service
     * @return array
     */
    public function addAction()
    {
        // Modify $data
        $formData = $this->getFromPost('fields');

        // Intercept Submission type submit button to prevent saving
        if (isset($formData['submissionSections']['submissionTypeSubmit'])) {
            $this->setPersist(false);
        } else {
            // remove form-actions
            $form = $this->getForm($this->getFormName());
            $form->remove('formActions[submit]');
        }
        return parent::addAction();
    }

    /**
     * Generate a form with data. Overridden to remove form actions
     *
     * @param string $name
     * @param callable $callback
     * @param mixed $data
     * @param boolean $tables
     * @return object
     *
    public function generateFormWithData($name, $callback, $data = null, $tables = false)
    {
        $form = $this->generateForm($name, $callback, $tables);

        if (!$this->getRequest()->isPost() && is_array($data)) {

            if (isset($data['submissionSections']['submissionTypeSubmit'])) {
                var_dump($data);exit;
                $form->remove('form-actions');
            }
            $form->setData($data);
        }

        return $form;
    }*/

    /**
     * Save data. Also processes the submit submission select type drop down
     * in order to dictate which checkboxes to manipulate.
     *
     * @param array $data
     * @param string $service
     * @return array
     */
    public function editAction()
    {
        // Modify $data

        return parent::editAction();
    }

    /**
     * Override Save data to allow json encoding of submission sections
     * into submission 'text' field.
     *
     * @param array $data
     * @param string $service
     * @return array
     */
    protected function save($data, $service = null)
    {
        // modify $data

        $data['text'] = json_encode($data['submissionSections']['sections']);
        $data['submissionType'] = $data['submissionSections']['submissionType'];
        $data = parent::save($data, $service);

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
        // pass false to prevent default redirect back to index action
        // and return result of the save
        $result = parent::processSave($data, false);

        $id = isset($result['id']) ? $result['id'] : $data['fields']['id'];
        return $this->redirect()->toRoute('submission', ['action' => 'details', 'submission' => $id], [], true);
    }

    /**
     * Map the data on load
     *
     * @param array $data
     * @return array
     */
    public function processLoad($data)
    {

        $case = $this->getCase();

        $data['fields']['case'] = $case['id'];

        if (isset($data['submissionSections']['sections'])) {
            $data['fields']['submissionSections']['sections'] = json_decode($data['submissionSections']['sections']);
        } elseif (isset($data['text'])) {
            $data['fields']['submissionSections']['submissionType'] = $data['submissionType']['id'];
            $data['fields']['submissionSections']['sections'] = json_decode($data['text']);
            $data['case'] = $case['id'];
            $data['fields']['id'] = $data['id'];
            $data['fields']['version'] = $data['version'];
        }

        return parent::processLoad($data);
    }


    /**
     * Get form name. Overridden so as not to create a form called SubAction
     *
     * @return string
     */
    /*protected function getFormName()
    {
        return $this->formName;
    }*/

    /**
     * Details action - shows each section detail
     *
     * @return ViewModel
     */
    public function detailsAction()
    {
        $submission = $this->loadCurrent();

        $view = $this->getView([]);

        $submissionsArray = json_decode($submission['text']);

        $submissionSections = $this->getServiceLocator()->get('Common\Service\Data\RefData')->fetchListData('submission_section');

        $sectionData = [];
        foreach ($submissionSections as $submissionSection) {
            if (in_array($submissionSection['id'], $submissionsArray)) {
                $sectionData[$submissionSection['id']]['description'] = $submissionSection['description'];
            }
        }

        $this->getViewHelperManager()
            ->get('placeholder')
            ->getContainer('sectionData')
            ->set($sectionData);

        $this->getViewHelperManager()
            ->get('placeholder')
            ->getContainer($this->getIdentifierName())
            ->set($submission);

        $view->setTemplate($this->detailsView);

        return $this->renderView($view);
    }

}
