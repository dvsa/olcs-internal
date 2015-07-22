<?php

/**
 * Partner Controller
 */
namespace Admin\Controller;

use Dvsa\Olcs\Transfer\Command\CompaniesHouse\CloseAlerts as CloseDto;
use Dvsa\Olcs\Transfer\Query\CompaniesHouse\AlertList as ListDto;
use Olcs\Controller\AbstractInternalController;
use Olcs\Controller\Interfaces\PageInnerLayoutProvider;
use Olcs\Controller\Interfaces\PageLayoutProvider;
use Olcs\Form\Model\Form\CompaniesHouseAlertFilters as FilterForm;
use Zend\View\Model\ViewModel;

/**
 * Partner Controller
 */
class CompaniesHouseAlertController extends AbstractInternalController implements
    PageLayoutProvider,
    PageInnerLayoutProvider
{
    /**
     * Holds the navigation ID,
     * required when an entire controller is
     * represented by a single navigation id.
     */
    protected $navigationId = 'admin-dashboard/admin-report';

    protected $crudConfig = [
        'close' => ['requireRows' => true],
    ];

    /**
     * @var array
     */
    protected $inlineScripts = [
        'indexAction' => ['table-actions', 'forms/filter'],
    ];

    /*
     * Variables for controlling table/list rendering
     * tableName and listDto are required,
     * listVars probably needs to be defined every time but will work without
     */
    protected $tableViewPlaceholderName = 'table';
    protected $tableViewTemplate = 'pages/table-comments';
    protected $tableName = 'admin-companies-house-alerts';
    protected $listDto = ListDto::class;
    protected $filterForm = FilterForm::class;
    protected $itemParams = ['id'];
    protected $redirectConfig = [
        'close' => [
            'action' => 'index'
        ]
    ];

    public function getPageLayout()
    {
        return 'layout/admin-report-section';
    }

    public function getPageInnerLayout()
    {
        return 'layout/wide-layout';
    }

    public function indexAction()
    {
        $this->placeholder()->setPlaceholder('pageTitle', 'Companies House change alerts');

        $view = parent::indexAction();

        // populate the filter dropdown from the data retrieved by the main ListDto
        $valueOptions = $this->listData['extra']['valueOptions']['companiesHouseAlertReason'];
        $this->getServiceLocator()
            ->get('viewHelperManager')
            ->get('placeholder')
            ->getContainer('tableFilters')
            ->getValue()
            ->get('typeOfChange')
            ->setValueOptions($valueOptions)
            ->setEmptyOption('ch_alert_reason.all');

        return $view;
    }

    public function closeAction()
    {
        $this->getLogger()->debug(__FILE__);
        $this->getLogger()->debug(__METHOD__);

        $confirmMessage = $this->getServiceLocator()->get('Helper\Translation')->translate('companies-house-alert.close.confirm');
        $confirm = $this->confirm($confirmMessage);

        if ($confirm instanceof ViewModel) {
            $this->placeholder()->setPlaceholder('pageTitle', 'companies-house-alert.close.title');
            return $this->viewBuilder()->buildView($confirm);
        }

        $dtoData = ['ids' => explode(',',$this->params()->fromRoute('id'))];
        $response = $this->handleCommand(CloseDto::create($dtoData));

        if ($response->isServerError() || $response->isClientError()) {
            $this->getServiceLocator()->get('Helper\FlashMessenger')->addErrorMessage('unknown-error');
        }

        if ($response->isOk()) {
            $this->getServiceLocator()->get('Helper\FlashMessenger')->addSuccessMessage('companies-house-alert.close.success');
        }

        return $this->redirectTo($response->getResult());
    }
}
