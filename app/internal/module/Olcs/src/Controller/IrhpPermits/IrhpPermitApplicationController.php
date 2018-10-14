<?php

/**
 * IRHP Permit Application Controller
 *
 * @author Andy Newton <andy@vitri.ltd>
 */

namespace Olcs\Controller\IrhpPermits;

use Common\RefData;
use Common\Service\Cqrs\Exception\NotFoundException;
use Dvsa\Olcs\Transfer\Command\Permits\AcceptEcmtPermits;
use Dvsa\Olcs\Transfer\Command\Permits\CancelEcmtPermitApplication;
use Dvsa\Olcs\Transfer\Command\Permits\EcmtSubmitApplication;
use Dvsa\Olcs\Transfer\Command\Permits\WithdrawEcmtPermitApplication;
use Dvsa\Olcs\Transfer\Query\IrhpPermitApplication\GetList as ListDTO;
use Dvsa\Olcs\Transfer\Query\Permits\ById as ItemDto;
use Dvsa\Olcs\Transfer\Query\Licence\Licence as LicenceDto;
use Dvsa\Olcs\Transfer\Command\Permits\CreateFullPermitApplication as CreateDTO;
use Dvsa\Olcs\Transfer\Command\Permits\UpdateEcmtPermitApplication as UpdateDTO;
use Dvsa\Olcs\Transfer\Command\Permits\DeclineEcmtPermits as DeclineDTO;
use Olcs\Mvc\Controller\ParameterProvider\ConfirmItem;
use Olcs\Controller\AbstractInternalController;
use Olcs\Controller\Interfaces\IrhpPermitApplicationControllerInterface;
use Olcs\Controller\Interfaces\LeftViewProvider;
use Olcs\Data\Mapper\IrhpPermit as IrhpPermitMapper;
use Olcs\Form\Model\Form\PermitCreate;
use Zend\View\Model\ViewModel;

class IrhpPermitApplicationController extends AbstractInternalController implements
    IrhpPermitApplicationControllerInterface,
    LeftViewProvider
{

    const FEE_TYPE_ECMT_APP = 'IRHPGVAPP';
    const FEE_TYPE_ECMT_ISSUE = 'IRHPGVISSUE';

    // Maps the route parameter irhpPermitId to the "id" parameter in the the ById (ItemDTO) query.
    protected $itemParams = ['id' => 'permitid'];

    protected $deleteParams = ['id' => 'permitid'];

    // Setup the default index table and sort columns/order
    protected $tableName = 'permit-applications';
    protected $defaultTableSortField = 'id';
    protected $defaultTableOrderField = 'DESC';

    // Maps the licence route parameter into the ListDTO as licence => value
    protected $listVars = ['licence'];
    protected $listDto = ListDto::class;
    protected $itemDto = ItemDto::class;
    protected $formClass = PermitCreate::class;
    protected $addFormClass = PermitCreate::class;
    protected $mapperClass = IrhpPermitMapper::class;
    protected $createCommand = CreateDto::class;
    protected $updateCommand = UpdateDto::class;


    protected $hasMultiDelete = false;
    protected $deleteModalTitle = 'Remove IRHP Permit Application?';
    protected $deleteConfirmMessage = 'Are you sure you want to remove this permit application?';
    protected $deleteSuccessMessage = 'The permit stock has been removed';
    protected $addContentTitle = 'Add Irhp Permit Application';
    protected $indexPageTitle = 'IRHP Permits';

    // This tab has two tables unlike most other index pages, so set custom template
    protected $tableViewTemplate = 'pages/irhp-permit/two-tables';
    protected $editViewTemplate = 'pages/irhp-permit/edit';
    protected $filterForm = 'PermitsHome';

    // After Adding and Editing we want users taken back to index dashboard
    protected $redirectConfig = [
        'add' => [
            'route' => 'licence/permits',
            'action' => 'index'
        ],
        'edit' => [
            'route' => 'licence/permits',
            'action' => 'index'
        ],
        'decline' => [
            'route' => 'licence/permits',
            'action' => 'index'
        ]
    ];

    // Maps to ID in navgiation-config file to underline correct item in horizontal nav menu
    protected $navigationId = 'licence_irhp_permits';

    // Scripts to include when rendering actions.
    protected $inlineScripts = [
        'editAction' => ['permits'],
        'addAction' => ['permits']
    ];

    // Override default index action to handle POSTs appropriately and perform the Query for the second table.

    /**
     * @return \Zend\Http\Response|ViewModel
     */
    public function indexAction()
    {
        $this->handleIndexPost();
        $this->indexIssuedTable();

        return parent::indexAction();
    }

    /**
     * Get left view
     *
     * @return \Zend\View\Model\ViewModel
     */
    public function getLeftView()
    {
        $view = new ViewModel();
        $view->setTemplate('sections/irhp-permit/partials/left');

        return $view;
    }

    /**
     * @return mixed|ViewModel
     *
     * Small override to handle the back button on the Add form and set some default form values
     *
     */
    public function addAction()
    {
        $request = $this->getRequest();
        if ($request->isPost() && array_key_exists('back', (array)$this->params()->fromPost()['form-actions'])) {
            return $this->permitDashRedirect();
        }

        return parent::addAction();
    }

    /**
     * @return mixed|ViewModel
     */
    public function editAction()
    {
        $request = $this->getRequest();
        if ($request->isPost() && array_key_exists('back', (array)$this->params()->fromPost()['form-actions'])) {
            return $this->permitDashRedirect();
        }
        return parent::editAction();
    }


    /**
     * @return \Zend\Http\Response
     *
     * Override to handle the Table from POST when Apply clicked and redirect to the Add form.
     *
     */
    protected function handleIndexPost()
    {
        $request = $this->getRequest();
        if ($request->isPost()) {
            $postData = (array)$this->params()->fromPost();
            if ($postData['action'] === 'Apply') {
                return $this->redirect()
                    ->toRoute(
                        'licence/permits',
                        [
                            'licence' => $this->params()->fromRoute('licence'),
                            'action' => 'add'
                        ]
                    );
            }
        }
    }


    /**
     *
     * Helper method to perform the query and setup table for Issued Permits table on dash.
     *
     */
    protected function indexIssuedTable()
    {
        // No story for this yet i have seen but we now have some data so included it in basic form.
        // Need some spec on if this table should filter from left panel controls etc. Static list for this story.
        $response = $this->handleQuery(ListDTO::create([
            'onlyIssued' => true,
            'licence' => $this->params()->fromRoute('licence'),
            'page' => 1,
            'sort' => 'id',
            'order' => 'ASC',
            'limit' => 50,

        ]));

        $data = [];
        if ($response->isOk()) {
            $data = $response->getResult();
        } else {
            $this->checkResponse($response);
        }

        $issuedTable = $this->getServiceLocator()
            ->get('Table')
            ->prepareTable('issued-permits', $data);
        $this->placeholder()->setPlaceholder('issuedTable', $issuedTable);
    }

    /**
     *
     * Dash redirect helper
     *
     */
    protected function permitDashRedirect()
    {
        return $this->redirect()
            ->toRoute(
                'licence/permits',
                ['licence' => $this->params()->fromRoute('licence')]
            );
    }


    /**
     * Setup required values for Edit form
     *
     * @param $form
     * @param $formData
     * @return mixed
     */
    protected function alterFormForEdit($form, $formData)
    {
        $licence = $this->getLicence();
        $formData['fields']['numVehicles'] = $licence['totAuthVehicles'];
        $formData['fields']['numVehiclesLabel'] = $licence['totAuthVehicles'];

        $form->setData($formData);
        return $form;
    }

    /**
     * Setup required values for Add form
     *
     * @param $form
     * @param $formData
     * @return mixed
     *
     */
    protected function alterFormForAdd($form, $formData)
    {
        $licence = $this->getLicence();
        $formData['fields']['licence'] = $licence['id'];
        $formData['fields']['numVehicles'] = $licence['totAuthVehicles'];
        $formData['fields']['numVehiclesLabel'] = $licence['totAuthVehicles'];
        $formData['fields']['dateReceived'] = date("Y-m-d");
        $form->setData($formData);
        return $form;
    }

    /**
     * to be implemented in later story - required for NavBar to work without error
     *
     * @return ViewModel
     */
    public function documentsAction()
    {
        return $this->stubAction();
    }

    /**
     * to be implemented in later story - required for NavBar to work without error
     *
     * @return ViewModel
     */
    public function processingAction()
    {
        return $this->stubAction();
    }

    /**
     * Handles click of the Submit button on right-sidebar
     *
     * @return \Zend\Http\Response
     *
     */
    public function submitAction()
    {
        $response = $this->handleQuery(ItemDto::create(['id' => $this->params()->fromRoute('permitid')]));
        $irhpPermit = $response->getResult();
        $fee = $this->getOutstandingFee($irhpPermit['fees'], self::FEE_TYPE_ECMT_APP);
        // The application canBeSubmitted, check for an outstanding fee and redirect ICW User to pay screen
        if ($fee) {
            return $this->redirect()
                ->toRoute(
                    'licence/irhp-fees/fee_action',
                    [
                        'action' => 'pay-fees',
                        'fee' => $fee['id'],
                        'licence' => $this->params()->fromRoute('licence'),
                        'permitid' => $this->params()->fromRoute('permitid')
                    ],
                    [],
                    false
                );
        } elseif ($irhpPermit['canBeSubmitted']) {
            // There was no outstanding fee for this application (already been paid) but it is submitable to call handler
            return $this->confirmCommand(
                new ConfirmItem($this->deleteParams),
                EcmtSubmitApplication::class,
                'Are you sure?',
                'Submit Application. Are you sure?',
                'Permit Application Submitted'
            );
        }
    }

    /**
     * Handles click of the Withdraw button on right-sidebar
     *
     * @return \Zend\Http\Response
     *
     */
    public function withdrawAction()
    {
        return $this->confirmCommand(
            new ConfirmItem($this->deleteParams),
            WithdrawEcmtPermitApplication::class,
            'Are you sure?',
            'Withdraw Application. Are you sure?',
            'Permit Application withdrawn'
        );
    }

    /**
     * Handles click of the Cancel button on right sidebar
     *
     * @return \Zend\Http\Response
     *
     */
    public function cancelAction()
    {
        return $this->confirmCommand(
            new ConfirmItem($this->deleteParams),
            CancelEcmtPermitApplication::class,
            'Are you sure?',
            'Cancel Permit Application. Are you sure?',
            'Permit Application cancelled'
        );
    }

    /**
     * Handles click of the Decline button on right sidebar
     *
     * @return \Zend\Http\Response
     *
     */
    public function declineAction()
    {
        return $this->confirmCommand(
            new ConfirmItem($this->deleteParams),
            DeclineDTO::class,
            'Are you sure?',
            'Decline Permits. Are you sure?',
            'Offer of permits successfully declined.'
        );
    }

    /**
     * Handles click of the Accept button on right sidebar
     *
     * @return \Zend\Http\Response
     *
     */
    public function acceptAction()
    {
        $response = $this->handleQuery(ItemDto::create(['id' => $this->params()->fromRoute('permitid')]));
        $irhpPermit = $response->getResult();
        $fee = $this->getOutstandingFee($irhpPermit['fees'], self::FEE_TYPE_ECMT_ISSUE);
        if ($fee) {
            return $this->redirect()
                ->toRoute(
                    'licence/irhp-fees/fee_action',
                    [
                        'action' => 'pay-fees',
                        'fee' => $fee['id']
                    ],
                    [],
                    true
                );
        }
    }


    protected function getOutstandingFee($fees, $type)
    {
        foreach ($fees as $key => $fee) {
            if ($fee['feeStatus']['id'] === RefData::FEE_STATUS_OUTSTANDING
                && $fee['feeType']['feeType']['id'] === $type) {
                return ($fees[$key]);
            }
        }
        return false;
    }

    /**
     * Check command handler response
     *
     * @param $response
     * @return null
     */
    protected function checkResponse($response)
    {
        if ($response->isOk()) {
            $this->getServiceLocator()
                ->get('Helper\FlashMessenger')
                ->addSuccessMessage('Application Updated Sucessfully');
        } elseif ($response->isClientError() || $response->isServerError()) {
            $this->handleErrors($response->getResult());
        }
    }

    /**
     * @return array|mixed
     * @throws NotFoundException
     */
    protected function getLicence()
    {
        $response = $this->handleQuery(LicenceDto::create(['id' => $this->params()->fromRoute('licence', null)]));
        if (!$response->isOk()) {
            throw new NotFoundException('Could not find Licence');
        }

        return $response->getResult();
    }

    /**
     * Remove this when the processing/documents actions are properly implemented.
     *
     * @return ViewModel
     */
    protected function stubAction()
    {
        $view = new ViewModel();
        $view->setTemplate('sections/irhp-permit/partials/stub');

        return $view;
    }
}
