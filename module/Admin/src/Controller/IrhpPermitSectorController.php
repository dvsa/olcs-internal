<?php

namespace Admin\Controller;

use Olcs\Controller\Interfaces\LeftViewProvider;
use Dvsa\Olcs\Transfer\Command\IrhpPermitSector\Update as Update;
use Dvsa\Olcs\Transfer\Query\IrhpPermitSector\GetList as ListDto;
use Laminas\View\Model\ViewModel;
use Laminas\Http\Response;

/**
 * IRHP Permits Sector controller
 */
class IrhpPermitSectorController extends AbstractIrhpPermitAdminController implements LeftViewProvider
{
    protected $tableName = 'admin-irhp-permit-sector';

    protected $listVars = ['irhpPermitStock' => 'stockId'];
    protected $listDto = ListDto::class;

    protected $indexPageTitle = 'Permits';

    protected $tableViewTemplate = 'pages/irhp-permit-sector/index';

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
     * Sector Quota Index Action
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
             * Otherwise, save the current Sector Quota values in the databse.
             */
            if ($postParams['action'] == 'Cancel') {
                $this->redirect()->toRoute($this->navigationId . '/stocks');
            } else {
                $cmdData = ['sectors' => $postParams['sectors']];
                $response = $this->handleCommand(Update::create($cmdData));

                if (!$response->isOk()) {
                    $this->handleErrors($response->getResult());
                }
            }
        }

        return parent::indexAction();
    }
}
