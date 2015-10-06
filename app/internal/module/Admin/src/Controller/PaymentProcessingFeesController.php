<?php

/**
 * Payment Processing Fees Controller
 */
namespace Admin\Controller;

use Common\Controller\AbstractActionController;
use Olcs\Controller\Interfaces\LeftViewProvider;
use Olcs\Controller\Traits\FeesActionTrait;
use Zend\View\Model\ViewModel;

/**
 * Payment Processing Fees Controller
 */
class PaymentProcessingFeesController extends AbstractActionController implements LeftViewProvider
{
    use FeesActionTrait;

    /**
     * @inheritdoc
     */
    protected function alterFeeTable($table, $results)
    {
        // no-op
        return $table;
    }

    /**
     * Route (prefix) for fees action redirects
     * @see Olcs\Controller\Traits\FeesActionTrait
     * @return string
     */
    protected function getFeesRoute()
    {
        return 'admin-dashboard/admin-payment-processing/misc-fees';
    }

    /**
     * The fees route redirect params
     * @see Olcs\Controller\Traits\FeesActionTrait
     * @return array
     */
    protected function getFeesRouteParams()
    {
        return [];
    }

    /**
     * The controller specific fees table params
     * @see Olcs\Controller\Traits\FeesActionTrait
     * @return array
     */
    protected function getFeesTableParams()
    {
        return [
            'isMiscellaneous' => 1,
            'status' => 'current',
        ];
    }

    /**
     * Index action
     *
     * @return \Zend\View\Model\ViewModel
     */
    public function indexAction()
    {
        return $this->feesAction();
    }

    /**
     * @inheritdoc
     */
    protected function renderLayout($view, $pageTitle = null, $pageSubTitle = null)
    {
        return parent::renderView($view, 'Payment processing', $pageSubTitle);
    }

    public function getLeftView()
    {
        $status = $this->params()->fromQuery('status');
        $filters = [
            'status' => $status
        ];

        $this->placeholder()->setPlaceholder('tableFilters', $this->getFeeFilterForm($filters));

        $view = new ViewModel(
            [
                'navigationId' => 'admin-dashboard/admin-payment-processing',
                'navigationTitle' => 'Payment Processing'
            ]
        );
        $view->setTemplate('admin/sections/admin/partials/generic-left');

        return $view;
    }

    protected function maybeClearLeft($layout)
    {
        $this->placeholder()->setPlaceholder('tableFilters', null);
    }
}
