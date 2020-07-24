<?php

namespace Admin\Controller;

use Olcs\Controller\Interfaces\LeftViewProvider;
use Dvsa\Olcs\Transfer\Query\IrhpPermitJurisdiction\GetList as ListDto;
use Dvsa\Olcs\Transfer\Command\IrhpPermitJurisdiction\Update as Update;
use Zend\View\Model\ViewModel;
use Zend\Http\Response;

/**
 * IRHP Permits Jurisdiction Controller
 */
class IrhpPermitJurisdictionController extends AbstractIrhpPermitAdminController implements LeftViewProvider
{
    protected $tableName = 'admin-irhp-permit-jurisdiction';

    protected $listVars = ['irhpPermitStock' => 'stockId'];
    protected $listDto = ListDto::class;

    protected $indexPageTitle = 'Permits';

    protected $tableViewTemplate = 'pages/irhp-permit-jurisdiction/index';

    protected $parentEntity = 'irhpPermitStock';

    protected $navigationId = 'admin-dashboard/admin-permits';

    protected $defaultData = ['stockId' => 'route'];

    /**
     * Get left view
     *
     * @return ViewModel
     */
    public function getLeftView()
    {
        $view = new ViewModel(
            [
                'navigationId' => 'admin-dashboard/admin-permits',
                'navigationTitle' => '',
                'stockId' => $this->params()->fromRoute()['stockId']
            ]
        );
        $view->setTemplate('admin/sections/admin/partials/generic-left');

        return $view;
    }
    /**
     * Jurisdiction Quota Index Action
     *
     * @return Response|ViewModel
     */
    public function indexAction()
    {
        $this->getServiceLocator()->get('Script')->loadFile('irhp-permit-total-table');

        $request = $this->getRequest();

         //Handle incoming POST request
        if ($request->isPost()) {
            $postParams = $this->params()->fromPost();

            /**
             * If the POST action is 'cancel', then navigate the user back to the Permit System Settings page.
             * Otherwise, save the current Permit Number values in the databse.
             */
            if ($postParams['action'] == 'Cancel') {
                $this->redirect()->toRoute($this->navigationId . '/stocks');
            } else {
                $cmdData = ['trafficAreas' => $postParams['trafficAreas']];
                $response = $this->handleCommand(Update::create($cmdData));

                if (!$response->isOk()) {
                    $this->handleErrors($response->getResult());
                }
            }
        }

        return parent::indexAction();
    }
}
