<?php

/**
 * IRHP Application Controller
 *
 * @author Andy Newton <andy@vitri.ltd>
 */

namespace Olcs\Controller\IrhpPermits;

use Common\Controller\Interfaces\ToggleAwareInterface;
use Common\Data\Mapper\Permits\NoOfPermits;
use Common\FeatureToggle;
use Common\RefData;
use Common\Service\Cqrs\Exception\NotFoundException;
use Dvsa\Olcs\Transfer\Command\IrhpApplication\CancelApplication;
use Dvsa\Olcs\Transfer\Command\IrhpApplication\Grant;
use Dvsa\Olcs\Transfer\Command\IrhpApplication\SubmitApplication;
use Dvsa\Olcs\Transfer\Command\IrhpApplication\Withdraw;
use Olcs\Form\Model\Form\IrhpApplicationWithdraw as WithdrawForm;
use Dvsa\Olcs\Transfer\Query\IrhpApplication\ApplicationPath;
use Dvsa\Olcs\Transfer\Query\IrhpPermitApplication\GetList as ListDTO;
use Dvsa\Olcs\Transfer\Query\IrhpPermitStock\AvailableCountries;
use Dvsa\Olcs\Transfer\Query\IrhpPermitWindow\OpenByCountry;
use Dvsa\Olcs\Transfer\Query\IrhpApplication\ById as ItemDto;
use Dvsa\Olcs\Transfer\Query\IrhpApplication\MaxStockPermits;
use Dvsa\Olcs\Transfer\Query\IrhpPermitWindow\OpenByType;
use Dvsa\Olcs\Transfer\Query\IrhpPermitType\ById as PermitTypeQry;
use Dvsa\Olcs\Transfer\Query\Licence\Licence as LicenceDto;
use Dvsa\Olcs\Transfer\Command\IrhpApplication\CreateFull as CreateDTO;
use Dvsa\Olcs\Transfer\Command\IrhpApplication\UpdateFull as UpdateDTO;
use Dvsa\Olcs\Transfer\Command\IrhpApplication\Create as QaCreateDTO;
use Olcs\Controller\Interfaces\IrhpApplicationControllerInterface;
use Olcs\Mvc\Controller\ParameterProvider\AddFormDefaultData;
use Olcs\Form\Model\Form\IrhpApplication;
use Olcs\Controller\AbstractInternalController;
use Olcs\Controller\Interfaces\LeftViewProvider;
use Olcs\Data\Mapper\IrhpApplication as IrhpApplicationMapper;
use Olcs\Mvc\Controller\ParameterProvider\ConfirmItem;
use Zend\Form\Form;
use Zend\Http\Response;
use Zend\View\Model\ViewModel;

class IrhpApplicationController extends AbstractInternalController implements
    IrhpApplicationControllerInterface,
    LeftViewProvider,
    ToggleAwareInterface
{
    use ShowIrhpApplicationNavigationTrait;

    protected $toggleConfig = [
        'default' => [
            FeatureToggle::BACKEND_PERMITS
        ],
    ];

    protected $routeIdentifier = 'irhp-application';

    protected $navigationId = 'licence_irhp_applications-edit';

    // Maps the route parameter irhpPermitId to the "id" parameter in the the ById (ItemDTO) query.
    protected $itemParams = ['id' => 'irhpAppId'];

    // Maps the licence route parameter into the ListDTO as licence => value
    protected $listVars = ['licence'];
    protected $listDto = ListDto::class;
    protected $itemDto = ItemDto::class;
    protected $formClass = IrhpApplication::class;
    protected $addFormClass = IrhpApplication::class;
    protected $mapperClass = IrhpApplicationMapper::class;
    protected $createCommand = CreateDto::class;
    protected $updateCommand = UpdateDto::class;

    protected $addContentTitle = 'Add Irhp Application';

    const PERMIT_TYPE_LABELS = [
        RefData::IRHP_BILATERAL_PERMIT_TYPE_ID => 'Bilateral',
        RefData::IRHP_MULTILATERAL_PERMIT_TYPE_ID => 'Multilateral',
    ];

    // After Adding and Editing we want users taken back to index dashboard
    protected $redirectConfig = [
        'add' => [
            'route' => 'licence/permits',
            'action' => 'index',
        ],
        'edit' => [
            'route' => 'licence/permits',
            'action' => 'index',
        ],
        'cancel' => [
            'route' => 'licence/permits',
            'action' => 'index',
        ],
        'submit' => [
            'route' => 'licence/permits',
            'action' => 'index',
        ],
        'grant' => [
            'route' => 'licence/permits',
            'action' => 'index',
        ],
        'withdraw' => [
            'route' => 'licence/permits',
            'action' => 'index',
        ]
    ];

    // Scripts to include when rendering actions.
    protected $inlineScripts = [
        'indexAction' => ['table-actions'],
    ];

    /**
     * Get left view
     *
     * @return \Zend\View\Model\ViewModel
     */
    public function getLeftView()
    {
        $view = new ViewModel(
            [
                'navigationId' => 'irhp_permits',
                'navigationTitle' => 'Application details'
            ]
        );
        $view->setTemplate('admin/sections/admin/partials/generic-left');

        return $view;
    }

    /**
     * @return mixed|Response
     *
     * Small override to handle the cancel button on the Add form as this form is not shown in a JS modal popup
     *
     */
    public function addAction()
    {
        $typeResponse = $this->handleQuery(PermitTypeQry::create(['id' => $this->params()->fromRoute('permitTypeId')]));
        $irhpPermit = $typeResponse->getResult();
        if ($irhpPermit['isApplicationPathEnabled']) {
            $this->questionAnswerAddApplicationRedirect();
        }

        $this->getServiceLocator()->get('Navigation')->findOneBy('id', 'licence_irhp_applications')->setVisible(0);
        $this->setFormTitle($this->params()->fromRoute('permitTypeId', null));
        $request = $this->getRequest();
        if ($request->isPost() && array_key_exists('back', (array)$this->params()->fromPost()['form-actions'])) {
            return $this->permitDashRedirect();
        }

        return parent::addAction();
    }

    /**
     * Handles creation of IrhpApplication rows to support QA Application form rendering.
     *
     * @return Response
     */
    protected function questionAnswerAddApplicationRedirect()
    {
        $response = $this->handleCommand(
            QaCreateDTO::create(
                [
                    'licence' => $this->params()->fromRoute('licence'),
                    'type' => $this->params()->fromRoute('permitTypeId'),
                    'year' => $this->params()->fromQuery('year')
                ]
            )
        );
        $result = $response->getResult();

        return $this->redirect()
            ->toRoute(
                'licence/irhp-application/application',
                [
                    'licence' => $this->params()->fromRoute('licence'),
                    'action' => 'edit',
                    'irhpAppId' => $result['id']['irhpApplication']
                ]
            );
    }


    /**
     * @return mixed|Response
     *
     * Small override to handle the cancel button on the Edit form
     *
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
     * Sets content tile to identify type of application being submitted
     *
     * @param $permitTypeId
     */
    protected function setFormTitle($permitTypeId)
    {
        $type = '';
        if (array_key_exists($permitTypeId, self::PERMIT_TYPE_LABELS)) {
            $type = self::PERMIT_TYPE_LABELS[$permitTypeId];
        }
        $this->addContentTitle = "Add $type Permit Application";
    }

    /**
     * Dash redirect helper
     *
     * @return Response
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
     * Handles click of the Submit button on right-sidebar
     *
     * @return \Zend\Http\Response
     *
     */
    public function submitAction()
    {
        $response = $this->handleQuery(ItemDto::create(['id' => $this->params()->fromRoute('irhpAppId')]));
        $irhpPermit = $response->getResult();

        $feeIds = $this->getOutstandingFeeIds(
            $irhpPermit['fees'],
            [
                RefData::IRHP_GV_APPLICATION_FEE_TYPE,
                RefData::IRHP_GV_ISSUE_FEE_TYPE,
                RefData::IRFO_GV_FEE_TYPE
            ]
        );

        // The application canBeSubmitted, check for an outstanding fee and redirect ICW User to pay screen
        if (!empty($feeIds)) {
            return $this->redirect()
                ->toRoute(
                    'licence/irhp-application-fees/fee_action',
                    [
                        'action' => 'pay-fees',
                        'fee' => implode(',', $feeIds),
                        'licence' => $this->params()->fromRoute('licence'),
                        'irhpAppId' => $this->params()->fromRoute('irhpAppId')
                    ],
                    [],
                    false
                );
        } else {
            // There was no outstanding fee for this application (already been paid) but it is submitable to call handler
            return $this->confirmCommand(
                new ConfirmItem($this->itemParams),
                SubmitApplication::class,
                'Are you sure?',
                'Submit Application. Are you sure?',
                'IRHP Application Submitted'
            );
        }
    }

    /**
     * check for any outstanding fees of the specified types, return the IDs to pass to Fees controller to pay.
     *
     * @param array $fees Array of fees associated with the application
     * @param array $feeTypes Array of fee types of which we need to know if any are outstanding
     *
     * @return array
     */
    protected function getOutstandingFeeIds(array $fees, array $feeTypes)
    {
        $feeIds = [];
        foreach ($fees as $fee) {
            if ($fee['feeStatus']['id'] === RefData::FEE_STATUS_OUTSTANDING
                && in_array($fee['feeType']['feeType']['id'], $feeTypes)) {
                $feeIds[] = $fee['id'];
            }
        }
        return $feeIds;
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
            new ConfirmItem($this->itemParams),
            CancelApplication::class,
            'Are you sure?',
            'Cancel Application. Are you sure?',
            'IRHP Application Cancelled'
        );
    }

    /**
     * withdraw action
     *
     * @return ViewModel
     */
    public function withdrawAction()
    {
        return $this->add(
            WithdrawForm::class,
            new AddFormDefaultData(['id' => $this->params()->fromRoute('irhpAppId')]),
            Withdraw::class,
            \Olcs\Data\Mapper\IrhpWithdraw::class,
            'pages/crud-form',
            'Withdraw Application',
            'Withdraw Application'
        );
    }

    /**
     * Handles click of the Cancel button on right sidebar
     *
     * @return \Zend\Http\Response
     *
     */
    public function grantAction()
    {
        return $this->confirmCommand(
            new ConfirmItem($this->itemParams),
            Grant::class,
            'Are you sure?',
            'Grant Application. Are you sure?',
            'IRHP Application Granted'
        );
    }

    /**
     * Setup required values for Add form
     *
     * @param $form
     * @param $formData
     * @return mixed
     * @throws NotFoundException
     */
    protected function alterFormForAdd($form, $formData)
    {
        $licence = $this->getLicence();
        $permitTypeId = $this->params()->fromRoute('permitTypeId', null);
        $formData['topFields']['numVehicles'] = $licence['totAuthVehicles'];
        $formData['topFields']['numVehiclesLabel'] = $licence['totAuthVehicles'];
        $formData['topFields']['dateReceived'] = date("Y-m-d");
        $formData['topFields']['irhpPermitType'] = $permitTypeId;
        $formData['topFields']['licence'] = $this->params()->fromRoute('licence', null);

        $maxStockPermits = $this->handleQuery(
            MaxStockPermits::create(['licence' => $this->params()->fromRoute('licence', null)])
        );
        if (!$maxStockPermits->isOk()) {
            throw new NotFoundException('Could not retrieve max permits data');
        }
        $formData['maxStockPermits']['result'] = $maxStockPermits->getResult()['results'];

        $windows = (int)$formData['topFields']['irhpPermitType'] === (int)RefData::IRHP_BILATERAL_PERMIT_TYPE_ID
            ? $this->getBilateralWindows()['results']
            : $this->getMultilateralWindows()['results'];

        // Prepare data structure with open bilateral windows for NoOfPermits form builder
        $formData['application'] = IrhpApplicationMapper::mapApplicationData(
            $windows,
            $permitTypeId
        );

        // Build the dynamic NoOfPermits per country per year form from Common
        $formData['application']['licence']['totAuthVehicles'] = $licence['totAuthVehicles'];

        $this->getServiceLocator()->get(NoOfPermits::class)->mapForFormOptions(
            $formData,
            $form,
            'application',
            'maxStockPermits',
            'feePerPermit'
        );
        $form->setData($formData);

        return $form;
    }

    /**
     * Setup required values for Edit form
     *
     * @param $form
     * @param $formData
     * @return mixed
     *
     * @throws NotFoundException
     */
    protected function alterFormForEdit($form, $formData)
    {
        $licence = $this->getLicence();

        $formData['topFields']['numVehicles'] = $licence['totAuthVehicles'];
        $formData['topFields']['numVehiclesLabel'] = $licence['totAuthVehicles'];
        $formData['topFields']['licence'] = $this->params()->fromRoute('licence', null);

        if (!in_array(
            $formData['topFields']['irhpPermitType'],
            [
                RefData::ECMT_SHORT_TERM_PERMIT_TYPE_ID,
                RefData::ECMT_REMOVAL_PERMIT_TYPE_ID,
                RefData::IRHP_BILATERAL_PERMIT_TYPE_ID,
                RefData::IRHP_MULTILATERAL_PERMIT_TYPE_ID
            ]
        )) {
            throw new \RuntimeException('Unsupported Permit Type');
        }

        if ($formData['topFields']['isApplicationPathEnabled']) {
            $form = $this->questionAnswerFormSetup($this->params()->fromRoute('irhpAppId'), $form);
        } else {
            $formData = $this->nonQuestionAnswerFormSetup($form, $formData, $licence);
        }

        $form->setData($formData);

        return $form;
    }

    /**
     * @param Form $form
     * @param array $formData
     * @param array $licence
     * @return mixed
     * @throws NotFoundException
     */
    protected function nonQuestionAnswerFormSetup(Form $form, array $formData, array $licence)
    {
        // Prepare data structure with open bilateral windows for NoOfPermits form builder
        $windows = (int)$formData['topFields']['irhpPermitType'] === RefData::IRHP_BILATERAL_PERMIT_TYPE_ID
            ? $this->getBilateralWindows()['results']
            : $this->getMultilateralWindows()['results'];

        $formData['application'] = IrhpApplicationMapper::mapApplicationData(
            $windows,
            $formData['topFields']['irhpPermitType'],
            $formData
        );

        $maxStockPermits = $this->handleQuery(
            MaxStockPermits::create(['licence' => $this->params()->fromRoute('licence', null)])
        );

        if (!$maxStockPermits->isOk()) {
            throw new NotFoundException('Could not retrieve max permits data');
        }
        $formData['maxStockPermits']['result'] = $maxStockPermits->getResult()['results'];

        // Build the dynamic NoOfPermits per country per year form from Common
        $formData['application']['licence']['totAuthVehicles'] = $licence['totAuthVehicles'];

        $this->getServiceLocator()->get(NoOfPermits::class)->mapForFormOptions(
            $formData,
            $form,
            'application',
            'maxStockPermits',
            'feePerPermit'
        );

        return $formData;
    }

    /**
     * Perform query to obtain application steps for given application ID and populate form.
     *
     * @param int $irhpApplicationId
     * @param Form $form
     * @return mixed
     */
    protected function questionAnswerFormSetup(int $irhpApplicationId, Form $form)
    {
        $response = $this->handleQuery(
            ApplicationPath::create(
                ['id' => $irhpApplicationId]
            )
        );

        $applicationSteps = $response->getResult();

        $fieldsetPopulator = $this->getServiceLocator()->get('QaFieldsetPopulator');
        $fieldsetPopulator->populate($form, $applicationSteps);
        return $form;
    }

    /**
     * @return array|mixed
     * @throws NotFoundException
     */
    protected function getBilateralWindows()
    {
        //Get list of countries that BiLaterals are applicable to
        $countries = $this->handleQuery(AvailableCountries::create([]));
        if (!$countries->isOk()) {
            throw new NotFoundException('Could not retrieve available countries');
        }

        // We just want the IDs for the next Query
        $countryIds = array_column($countries->getResult()['countries'], 'id');

        if (empty($countryIds)) {
            throw new NotFoundException('No countries are available for this type of application');
        }

        //Query open windows for the country IDs retrieved above
        $windows = $this->handleQuery(OpenByCountry::create(['countries' => $countryIds]));
        if (!$windows->isOk()) {
            throw new NotFoundException('Could not retrieve open windows');
        }

        return $windows->getResult();
    }

    /**
     * @return array|mixed
     * @throws NotFoundException
     */
    protected function getMultilateralWindows()
    {
        $windows = $this->handleQuery(OpenByType::create(
            [
                'irhpPermitType' => RefData::IRHP_MULTILATERAL_PERMIT_TYPE_ID,
            ]
        ));

        if (!$windows->isOk()) {
            throw new NotFoundException('Could not retrieve open windows');
        }


        return $windows->getResult();
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
}