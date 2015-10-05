<?php
/**
 * Bus Processing Registration History Controller
 */
namespace Olcs\Controller\Bus\Processing;

use Dvsa\Olcs\Transfer\Query\Bus\BusReg;
use Dvsa\Olcs\Transfer\Query\Bus\RegistrationHistoryList as BusRegRegistrationHistoryList;
use Dvsa\Olcs\Transfer\Command\Bus\DeleteBus;
use Olcs\Controller\AbstractInternalController;
use Olcs\Controller\Interfaces\BusRegControllerInterface;
use Olcs\Controller\Interfaces\LeftViewProvider;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Common\Controller\Traits as CommonTraits;
use Zend\Mvc\MvcEvent as MvcEvent;

/**
 * Bus Processing Registration History Controller
 */
class BusProcessingRegistrationHistoryController extends AbstractInternalController implements
    BusRegControllerInterface,
    LeftViewProvider
{
    /**
     * Holds the navigation ID,
     * required when an entire controller is
     * represented by a single navigation id.
     */
    protected $navigationId = 'licence_bus_processing';

    /**
     * Holds an array of variables for the
     * default index list page.
     */
    protected $listVars = ['busReg' => 'busRegId', 'id' => 'busRegId'];
    protected $itemParams = ['id' => 'busRegId'];
    protected $defaultTableSortField = 'variationNo';
    protected $tableName = 'Bus/registration-history';
    protected $itemDto = BusReg::class;
    protected $listDto = BusRegRegistrationHistoryList::class;
    protected $deleteCommand = DeleteBus::class;

    /**
     * This config is overridden if when a bus reg is deleted there is a previous variation
     *
     * @var array
     */
    protected $redirectConfig = [
        'delete' => [
            'route' => 'licence/bus',
            'action' => 'bus'
        ]
    ];

    /**
     * Any inline scripts needed in this section
     *
     * @var array
     */
    protected $inlineScripts = [
        'index' => 'table-actions'
    ];

    /**
     * If there is a previous bus reg id then we override the redirect to the licence/bus page
     * and instead redirect to the registration history list
     *
     * @param array $restResponse
     * @return array
     */
    public function redirectConfig(array $restResponse)
    {
        //if there's a variation previous to the one just deleted
        if (isset($restResponse['id']['previousBusRegId'])) {
            $this->redirectConfig['delete'] = [
                'route' => null,
                'action' => 'index',
                'resultIdMap' => [
                    'busRegId' => 'previousBusRegId'
                ]
            ];
        }

        return parent::redirectConfig($restResponse);
    }

    public function getLeftView()
    {
        $view = new ViewModel();
        $view->setTemplate('sections/bus/partials/left');

        return $view;
    }
}
