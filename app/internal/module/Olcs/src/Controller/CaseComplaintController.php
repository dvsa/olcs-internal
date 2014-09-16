<?php

/**
 * Case Complaint Controller
 *
 * @author S Lizzio <shaun.lizzio@valtech.co.uk>
 */

namespace Olcs\Controller;

use Common\Controller\CrudInterface;
use Zend\View\Model\ViewModel;

/**
 * Case Complaint Controller
 *
 * @author S Lizzio <shaun.lizzio@valtech.co.uk>
 */
class CaseComplaintController extends CaseController implements CrudInterface
{
    /**
     * Performs a delete action and redirects to the index
     */
    public function deleteAction()
    {
        $id = $this->params()->fromRoute('id');

        // Now implemented deleting a list, so we don't meed the id of the entity to remove it
        $this->makeRestCall('ComplaintCase', 'DELETE', array('complaint' => $id));

        $this->redirectToIndex();
    }

    /**
     * Main index action responsible for generating the main landing page for
     * complaints.
     *
     * @return \Zend\View\Model\ViewModel
     */
    public function indexAction()
    {
        $caseId = $this->fromRoute('case');
        $licenceId = $this->fromRoute('licence');

        $this->setBreadcrumb(array('licence_case_list/pagination' => array('licence' => $licenceId)));

        // checks for CRUD and redirects as required
        $this->checkForCrudAction('complaint', array('case' => $caseId, 'licence' => $licenceId), 'id');

        // no crud, generate the main complaints table
        $view = $this->getView();
        $tabs = $this->getTabInformationArray();
        $action = 'complaints';

        $case = $this->getCase($caseId);

        $summary = $this->getCaseSummaryArray($case);

        $bundle = $this->getComplaintBundle();

        $results = $this->makeRestCall('Cases', 'GET', array('id' => $caseId), $bundle);

        $complaints = array();

        foreach ($results['complaintCases'] as $row) {
            $complaints[] = $row['complaint'];
        }

        $table = $this->getTable('complaints', $complaints);

        $view->setVariables(
            [
                'case' => $case,
                'tabs' => $tabs,
                'tab' => $action,
                'summary' => $summary,
                'table' => $table,
            ]
        );

        $view->setTemplate('case/manage');
        return $view;
    }

    /**
     * Add form action
     *
     * @return ViewModel
     */
    public function addAction()
    {
        $routeParams = $this->getParams(array('case', 'licence', 'id'));

        if (null !== $this->params()->fromPost('cancel-complaint')) {
            return $this->redirect()->toRoute(
                'case_complaints', array(
                    'licence' => $routeParams['licence'],
                    'case' => $routeParams['case']
                )
            );
        }
        // Below is for setting route params for the breadcrumb
        $this->setBreadcrumb(
            array(
                'licence_case_list/pagination' => array('licence' => $routeParams['licence']),
                'case_complaints' => array(
                    'licence' => $routeParams['licence'],
                    'case' => $routeParams['case']
                )
            )
        );

        $data = array('case' => $routeParams['case']);

        $results = $this->makeRestCall('Cases', 'GET', array('id' => $routeParams['case']));

        // todo hardcoded organisation id for now
        $data['organisation-details']['id'] = 7;
        $data['organisation-details']['version'] = 1;

        if (empty($routeParams['case']) || empty($routeParams['licence']) || empty($results)) {
            return $this->getResponse()->setStatusCode(404);
        }

        $form = $this->generateForm(
            'complaint', 'processComplaint'
        );

        $form->setData($data);
        //$form->setMessages(array('blah' => 'This is a test message'));
        $view = new ViewModel(
            array(
                'form' => $form,
                'params' => array(
                    'pageTitle' => 'add-complaint',
                    'pageSubTitle' => 'subtitle-complaint-text'
                )
            )
        );
        $view->setTemplate('complaint/form');
        return $view;
    }

    public function editAction()
    {
        $routeParams = $this->getParams(
            array(
                'case',
                'licence',
                'id',
            )
        );
        if (null !== $this->params()->fromPost('cancel-complaint')) {
            return $this->redirect()->toRoute(
                'case_complaints', array(
                    'licence' => $routeParams['licence'],
                    'case' => $routeParams['case']
                )
            );
        }
        $this->setBreadcrumb(
            array(
                'licence_case_list/pagination' => array('licence' => $routeParams['licence']),
                'case_complaints' => array('licence' => $routeParams['licence'], 'case' => $routeParams['case'])
            )
        );

        $bundle = $this->getComplaintBundleForUpdates();

        $data = $this->makeRestCall('Complaint', 'GET', array('id' => $routeParams['id']), $bundle);

        if (isset($data['id'])) {
            $data['case'] = $data['id'];
        }

        if (empty($routeParams['case']) || empty($routeParams['licence']) || empty($data)) {
            return $this->getResponse()->setStatusCode(404);
        }

        $data['organisation-details'] = $data['organisation'];
        $data['complaint-details'] = $data;
        $data['complainant-details'] = $data['complainantContactDetails']['person'];
        $data['driver-details'] = $data['driver']['contactDetails']['person'];

        $data['complaint-details']['status'] = $data['complaint-details']['status']['id'];
        $data['complaint-details']['complaintType'] = $data['complaint-details']['complaintType']['id'];

        $form = $this->generateFormWithData('complaint', 'processComplaint', $data);

        $view = new ViewModel(
            array(
                'form' => $form,
                'params' => array(
                    'pageTitle' => 'edit-complaint',
                    'pageSubTitle' => 'subtitle-complaint-text'
                )
            )
        );
        $view->setTemplate('complaint/form');
        return $view;
    }

    public function processComplaint($data)
    {
        $routeParams = $this->getParams(array('action', 'licence', 'case'));

        if (strtolower($routeParams['action']) == 'edit') {
            // not sure how the version info is to be handled for entities
            // that are not directly updated (e.g. ContactDetails)
            // todo this *may* be possible in a single rest call
            $result = $this->processEdit($data['complaint-details'], 'Complaint');
            $result = $this->processEdit($data['complainant-details'], 'Person');
            $result = $this->processEdit($data['driver-details'], 'Person');
        } else {
            // configure complaint data
            unset($data['complaint-details']['version']);
            unset($data['organisation-details']['version']);

            $complaintCaseData = array(
                'complaint' => $data['complaint-details'],
                'case' => $data['case']
            );

            $complaintCaseData['complaint']['value'] = '';
            // @todo change these from hard coded values
            $complaintCaseData['complaint']['vehicle_id'] = 1;
            $complaintCaseData['complaint']['organisation'] = 1;

            $complaintCaseData['complaint']['driver']['contactDetails']['contactType'] = 'ct_driver';
            $complaintCaseData['complaint']['driver']['contactDetails']['is_deleted'] = 0;
            $complaintCaseData['complaint']['driver']['contactDetails']['person'] = $data['driver-details'];
            unset($complaintCaseData['complaint']['driver']['contactDetails']['person']['version']);

            $complaintCaseData['complaint']['complainantContactDetails']['contactType'] = 'ct_complainant';
            $complaintCaseData['complaint']['complainantContactDetails']['is_deleted'] = 0;
            $complaintCaseData['complaint']['complainantContactDetails']['person'] = $data['complainant-details'];
            unset($complaintCaseData['complaint']['complainantContactDetails']['person']['version']);

            $result = $this->processAdd($complaintCaseData, 'ComplaintCase');
        }

        return $this->redirect()->toRoute(
            'case_complaints',
            array(
                'case' => $routeParams['case'], 'licence' => $routeParams['licence']
            )
        );
    }

    /**
     * Method to return the bundle required for getting complaints and related
     * entities from the database.
     *
     * @return array
     */
    private function getComplaintBundleForUpdates()
    {
        return array(
            'complaint' => array(
                'properties' => array('ALL'),
            ),
            'children' => array(
                'status' => array(
                    'properties' => array('id')
                ),
                'complaintType' => array(
                    'properties' => array('id')
                ),
                'driver' => array(
                    'properties' => array('id', 'version'),
                    'children' => array(
                        'contactDetails' => array(
                            'properties' => array('id', 'version'),
                            'children' => array(
                                'person' => array(
                                    'properties' => array(
                                        'id',
                                        'version',
                                        'forename',
                                        'familyName',
                                    )
                                )
                            )
                        )
                    )
                ),
                'complainantContactDetails' => array(
                    'children' => array(
                        'person' => array(
                            'properties' => array(
                                'id',
                                'version',
                                'forename',
                                'familyName',
                            )
                        )
                    )
                ),
                'organisation' => array(
                    'properties' => array('id', 'version', 'name'),
                )
            )
        );
    }

    /**
     * Method to return the bundle required for complaints
     *
     * @return array
     */
    private function getComplaintBundle()
    {
        return array(
            'properties' => array(
                'id'
            ),
            'children' => array(
                'complaintCases' => array(
                    'children' => array(
                        'complaint' => array(
                            'properties' => array(
                                'id',
                                'complaintDate',
                                'description'
                            ),
                            'children' => array(
                                'complainantContactDetails' => array(
                                    'properties' => array(
                                        'id',
                                    ),
                                   'children' => array(
                                       'person' => array(
                                           'properties' => array(
                                               'forename',
                                               'familyName'
                                            )
                                        )
                                    )
                                )
                            )
                        )
                    )
                )
            )
        );
    }
}
