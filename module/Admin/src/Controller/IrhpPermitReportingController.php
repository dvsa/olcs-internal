<?php
/**
 * IRHP Permits Reporting Controller
 *
 * @author Jason de Jonge <jason.de-jonge@capgemini.com>
 *
 */

namespace Admin\Controller;

use Olcs\Controller\AbstractInternalController;
use Olcs\Controller\Interfaces\LeftViewProvider;
use Olcs\Mvc\Controller\ParameterProvider\ConfirmItem;
use Zend\View\Model\ViewModel;
use Common\Category;
use Common\Controller\Traits\ViewHelperManagerAware;
use Common\Controller\Traits\GenericRenderView;
use Dvsa\Olcs\Transfer\Query\Document\DocumentList;

class IrhpPermitReportingController extends AbstractInternalController implements LeftViewProvider
{
    use ViewHelperManagerAware,
        GenericRenderView;

    protected $navigationId = 'admin-dashboard/admin-permits';
    protected $tableViewTemplate = 'pages/irhp-permit-reporting/index';

    /**
     * @return ViewModel
     */
    public function getLeftView()
    {
        $view = new ViewModel(
            [
                'navigationId' => 'admin-dashboard/admin-permits',
                'navigationTitle' => 'Permits'
            ]
        );
        $view->setTemplate('admin/sections/admin/partials/generic-left');

        return $view;
    }

    /**
     * @return \Zend\Http\Response|ViewModel
     */
    public function indexAction()
    {
        $data = [
            'page' => $this->params()->fromQuery('page', 1),
            'limit' => $this->params()->fromQuery('limit', 10),
            'query' => $this->getRequest()->getQuery()->toArray(),
        ];

        $query = DocumentList::create(
            [
                'sort' => 'issuedDate',
                'order' => 'desc',
                'category' => Category::CATEGORY_PERMITS,
                'documentSubCategory' => [
                    Category::DOC_SUB_CATEGORY_PERMITS,
                ],
                'onlyUnlinked' => 'Y',
                'page' => $data['page'],
                'limit' => $data['limit'],
            ]
        );

        $response = $this->handleQuery($query);

        $table = $this->getServiceLocator()
            ->get('Table')
            ->buildTable('admin-exported-reports', $response->getResult(), $data, false);

        $view = new ViewModel(['table' => $table]);
        $view->setTemplate('pages/table');

        $this->getViewHelperManager()->get('placeholder')->getContainer('tableFilters')
            ->set($view->getVariable('filterForm'));

        return $this->renderView($view, $pageTitle, null);
    }
}
