<?php

namespace Admin\Controller;

use Olcs\Controller\AbstractInternalController;
use Olcs\Controller\Interfaces\LeftViewProvider;
use Zend\View\Model\ViewModel;
//use Dvsa\Olcs\Transfer\Query\Application\InterimRefunds as ListDto;
use Dvsa\Olcs\Transfer\Query\CompaniesHouse\AlertList as ListDto;
use Olcs\Form\Model\Form\InterimRefunds as FilterForm;
use Olcs\Form\Model\Form\InterimRefundsReset as ResetForm;
/**
 * Class InterimRefundsController
 *
 * @package Admin\Controller
 */
class InterimRefundsController extends AbstractInternalController implements LeftViewProvider
{
    protected $navigationId = 'admin-dashboard/admin-interim-refunds';


    /**
     * @var array
     */
    protected $inlineScripts = [
        'indexAction' => ['table-actions'],
    ];

    // list
    protected $tableName = 'admin-interim-refunds';
    protected $defaultTableSortField = 'id';
    protected $defaultTableOrderField = 'ASC';
    protected $listDto = ListDto::class;
    protected $filterForm = ResetForm::class;

    protected $tableViewTemplate = 'pages/interim-refund/index';

    public function getLeftView()
    {
        $view = new ViewModel(
            [
                'navigationId' => 'admin-dashboard/admin-interim-refunds',
                'navigationTitle' => 'Interim Refunds'
            ]
        );
        $view->setTemplate('admin/sections/admin/partials/generic-left');

        return $view;
    }

    public function indexAction()
    {
        $this->placeholder()->setPlaceholder('pageTitle', 'Interim Refunds');
        $view = parent::indexAction();
        return $view;
    }

    public function generateReportAction()
    {
        $this->placeholder()->setPlaceholder('pageTitle', 'Interim Refunds');
        $this->placeholder()->setPlaceholder('contentTitle', 'Choose Criteria');

        $form = $this->getFormForInterim();


        $editViewTemplate = 'pages/crud-form';

        $this->placeholder()->setPlaceholder('form', $form);
        return $this->viewBuilder()->buildViewFromTemplate($editViewTemplate);
    }

    /**
     * @return mixed
     */
    private function getFormForInterim()
    {
        $filterFormPath = explode('\\', FilterForm::class);
        $formClass = array_pop($filterFormPath);
        $form = $this->getForm($formClass);
        return $form;
    }
}
