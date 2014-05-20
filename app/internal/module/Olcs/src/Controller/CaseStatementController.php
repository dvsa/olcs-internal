<?php

/**
 * Case Statement Controller
 *
 * @author Rob Caiger <rob@clocal.co.uk>
 */

namespace Olcs\Controller;

/**
 * Case Statement Controller
 *
 * @todo For Breadcrumbs we need to pull the real licence id in
 *
 * @author Rob Caiger <rob@clocal.co.uk>
 */
class CaseStatementController extends CaseController
{
    /**
     * Show a table of statements for the given case
     *
     * @return object
     */
    public function indexAction()
    {
        $this->setBreadcrumb(array('licence_case_list/pagination' => array('licence' => 1)));

        $caseId = $this->fromRoute('case');

        $this->checkForCrudAction('case_statement', array('case' => $caseId), 'statement', true);

        $results = $this->makeRestCall('Statement', 'GET', array('caseId' => $caseId));

        $variables = array('tab' => 'statements', 'table' => $this->buildTable('statement', $results));

        $view = $this->getView($this->getCaseVariables($caseId, $variables));

        $view->setTemplate('case/manage');

        return $view;
    }

    /**
     * Add statement action
     *
     * @return object
     */
    public function addAction()
    {
        $caseId = $this->fromRoute('case');

        $this->setBreadcrumb(
            array(
                'licence_case_list/pagination' => array('licence' => 1),
                'case_statement' => array('case' => $caseId)
            )
        );

        $form = $this->generateFormWithData(
            'statement', 'processAddStatement', array('case' => $caseId)
        );

        $view = $this->getView(
            [
                'params' => [
                    'pageTitle' => 'Add statement',
                    'pageSubTitle' => ''
                ],
                'form' => $form
            ]
        );

        $view->setTemplate('form');

        return $view;
    }

    /**
     * Edit statement action
     *
     * @return object
     */
    public function editAction()
    {
        $caseId = $this->fromRoute('case');

        $this->setBreadcrumb(
            array(
                'licence_case_list/pagination' => array('licence' => 1),
                'case_statement' => array('case' => $caseId)
            )
        );

        $statementId = $this->fromRoute('statement');

        $bundle = array(
            'children' => array(
                'requestorsAddress'
            )
        );

        $details = $this->makeRestCall('Statement', 'GET', array('id' => $statementId), $bundle);

        if (empty($details)) {
            return $this->notFoundAction();
        }

        $data = $this->formatDataForEditForm($details);
        $data['case'] = $caseId;

        $data['requestorsAddress']['country'] = 'country.' . $data['requestorsAddress']['country'];

        $form = $this->generateFormWithData(
            'statement',
            'processEditStatement',
            $data
        );

        $view = $this->getView(
            [
                'params' => [
                    'pageTitle' => 'Edit statement',
                    'pageSubTitle' => ''
                ],
                'form' => $form
            ]
        );

        $view->setTemplate('form');

        return $view;
    }

    /**
     * Format the data for the edit form
     *
     * @param array $data
     * @return array
     */
    private function formatDataForEditForm($data)
    {
        $data['details'] = $data;

        $data['details']['statementType'] = 'statement_type.' . $data['details']['statementType'];
        $data['details']['contactType'] = 'contact_type.' . $data['details']['contactType'];

        return $data;
    }

    /**
     * Delete action
     */
    public function deleteAction()
    {
        $caseId = $this->fromRoute('case');

        $bundle = array(
            'children' => array(
                'case' => array(
                    'properties' => 'ALL',
                )
            )
        );

        $statementId = $this->fromRoute('statement');

        // Check that the statement belongs to the case before deleting
        $results = $this->makeRestCall('Statement', 'GET', array('id' => $statementId), $bundle);

        if (isset($results['case']) && $results['case']['id'] == $caseId) {

            $this->makeRestCall('Statement', 'DELETE', array('id' => $statementId));
            return $this->redirect()->toRoute('case_statement', ['statement'=>''], [], true);
        }

        return $this->notFoundAction();
    }

    /**
     * Process the add post
     *
     * @param array $data
     */
    public function processAddStatement($data)
    {
        $data = $this->processDataBeforePersist($data);

        $this->processAdd($data, 'Statement');

        $this->redirect()->toRoute(
            'case_statement',
            ['case'=>$this->fromRoute('case'), 'licence'=>$this->fromRoute('licence')],
            [],
            false
        );
    }

    /**
     * Process the edit post
     *
     * @param array $data
     */
    public function processEditStatement($data)
    {
        $data = $this->processDataBeforePersist($data);

        $this->processEdit($data, 'Statement');

        $this->redirect()->toRoute(
            'case_statement',
            ['case'=>$this->fromRoute('case'), 'licence'=>$this->fromRoute('licence')],
            [],
            false
        );
    }

    /**
     * Method to map form data to bookmark data for replacement in the document
     * template
     *
     * @param array $data
     * @return array of bookmark mappings
     */
    public function mapDocumentData($data)
    {
        $bookmarkData = $this->getBookmarkData($data);
        $bookmarks = [];
        $bookmarks['TAName'] = $bookmarkData['licence']['trafficArea']['areaName']; // Traffic area name;
        $bookmarks['TAAddress_2'] = '<user\'s location address>'; // users location address

        $bookmarks['Address_1'] =
            $data['addresses']['requestorsAddress']['addressLine1'] . " \line " .
            $data['addresses']['requestorsAddress']['addressLine2'] . " \line " .
            $data['addresses']['requestorsAddress']['addressLine3'] . " \line " .
            $data['addresses']['requestorsAddress']['addressLine4'] . " \line" .
            $data['addresses']['requestorsAddress']['city'] . " \line" .
            $data['addresses']['requestorsAddress']['postcode'] . " \line" .
            $data['addresses']['requestorsAddress']['country'];
        $bookmarks['Ref'] = '184130/' . $bookmarkData['licence']['licenceNumber'];
        $bookmarks['Name'] = $data['requestorsForename'] . ' ' . $data['requestorsFamilyName'];
        $bookmarks['RequestMode'] = 'letter';
        $bookmarks['RequestDate'] = $data['dateRequested'];
        $bookmarks['UserKnownAs'] = '<user\'s name>';
        $bookmarks['AuthorisorTeam'] = 'Authoriser Team';
        $bookmarks['AuthorisorName2'] = '<user\'s name> <olcs job role>';
        $bookmarks['AuthorisedDecision'] = '<user\'s location address>' .
            " \line \line " .
            $data['authorisersDecision'];
        $bookmarks['AuthorisorName3'] = '<user\'s name> <olcs job role>';

        return $bookmarks;
    }

    /**
     * Gets bookmark data for the document tempate
     *
     * @param array $data
     * @return array
     */
    public function getBookmarkData($data)
    {
        $bundle = $this->getBookmarkBundle();

        $bookmarkData = $this->makeRestCall(
            'VosaCase',
            'GET',
            ['id' => $data['case'], 'bundle' => json_encode($bundle)]
        );

        return $bookmarkData;

    }

    /**
     * Gets the bookmark bundle
     *
     * @return array
     */
    public function getBookmarkBundle()
    {
         return array(
            'properties' => array(
                'id',
                'licence'
            ),
            'children' => array(
                'licence' => array(
                    'properties' => array(
                        'id',
                        'licenceNumber',
                        'trafficArea',
                    ),
                    'children' => array(
                        'trafficArea' => array(
                            'properties' => array(
                                'id',
                                'areaName'
                            ),
                        )
                    )
                )
            )
        );
    }

    /**
     * Pre-persist data processing
     *
     * @param array $data
     * @return array
     */
    protected function processDataBeforePersist($data)
    {
        $data = array_merge($data, $data['details']);

        unset($data['details']);

        $data = $this->processAddressData($data, 'requestorsAddress');

        $data['statementType'] = str_replace('statement_type.', '', $data['statementType']);
        $data['contactType'] = str_replace('contact_type.', '', $data['contactType']);

        return $data;
    }
}
