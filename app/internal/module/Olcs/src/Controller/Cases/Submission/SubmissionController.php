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

    /**
     * Holds the Data Bundle
     *
     * @var array
     */
    protected $dataBundle = array(
        'children' => array(
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
        if (isset($formData['submission_sections']['submission_type_submit'])) {
            $this->setPersist(false);
        }
        return parent::addAction();
    }

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
        $data = $this->loadCurrent();

        return parent::editAction();
    }

    /**
     * Get the form data
     *
     * This is not in the method above as it can be overridden independantly
     *
     * @return array
     *
    protected function getFormData()
    {
        if ($this->isAction()) {

            $action = $this->getActionName();

            if (strstr($action, '-')) {
                $splitted = explode('-', $action);
                $action = count($splitted) ? $splitted[count($splitted) - 1] : '';
            }

            if ($action === 'edit') {

                $data = $this->actionLoad($this->getActionId());
            } else {
                $data = array();
            }

            return $this->processActionLoad($data);
        }

        return $this->processLoad($this->loadCurrent());
    }*/

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

        $data['text'] = json_encode($data['submission_sections']['submission_sections']);

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
    protected function processSave($data)
    {
        // pass false to prevent default redirect back to index action
        // and return result of the save
        $result = parent::processSave($data, false);

        return $this->redirect()->toRoute('submission', ['action' => 'details', 'submission' => $result['id']], [], true);
    }

    /**
     * Map the data on load
     *
     * @param array $data
     * @return array
     */
    protected function processLoad($data)
    {

        // modify $data for form population
        $routeParams = $this->params()->fromRoute();
        $caseId = $routeParams['case'];
        $case = $this->getCase($caseId);
        $data['fields']['case'] = $case['id'];

        if (isset($data['submission_sections']['submission_sections'])) {
            $data['fields']['submission_sections']['submission_sections'] = json_decode($data['submission_sections']['submission_sections']);
        } elseif (isset($data['text'])) {
            $data['fields']['submission_sections']['submission_type'] = 'submission_type_o_bus_reg';
            $data['fields']['submission_sections']['submission_sections'] = json_decode($data['text']);
            $data['case'] = $case['id'];
        }

        return parent::processLoad($data);
    }


    /**
     * Get form name. Overridden so as not to create a form called SubAction
     *
     * @return string
     */
    protected function getFormName()
    {
        return $this->formName;
    }

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
            ->set($this->loadCurrent());

        $view->setTemplate($this->detailsView);

        return $this->renderView($view);
    }

}
