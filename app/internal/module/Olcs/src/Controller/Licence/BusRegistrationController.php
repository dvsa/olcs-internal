<?php
/**
 * Licence Bus Reg Controller
 *
 * @author Craig Reasbeck <craig.reasbeck@valtech.co.uk>
 */
namespace Olcs\Controller\Licence;

use Olcs\Controller\AbstractInternalController;
use Olcs\Controller\Interfaces\LeftViewProvider;
use Olcs\Controller\Interfaces\LicenceControllerInterface;
use Dvsa\Olcs\Transfer\Query\Bus\SearchViewList as ListDto;
use Olcs\Form\Model\Form\BusRegList as FilterForm;
use Zend\View\Model\ViewModel;

/**
 * Licence Bus Reg Controller
 *
 * @author Craig Reasbeck <craig.reasbeck@valtech.co.uk>
 */
class BusRegistrationController extends AbstractInternalController implements
    LicenceControllerInterface,
    LeftViewProvider
{
    /**
     * Holds the navigation ID,
     * required when an entire controller is
     * represented by a single navigation id.
     */
    protected $navigationId = 'licence_bus';

    protected $crudConfig = [
        'add' => ['route' => 'licence/bus/registration', 'requireRows' => false],
        'edit' => ['route' => 'licence/bus/registration', 'requireRows' => true]
    ];

    /*
     * Variables for controlling table/list rendering
     * tableName and listDto are required,
     * listVars probably needs to be defined every time but will work without
     */
    protected $tableViewPlaceholderName = 'table';
    protected $tableViewTemplate = 'pages/table';
    protected $defaultTableSortField = 'regNo';
    protected $tableName = 'busreg';
    protected $listDto = ListDto::class;
    protected $listVars = [
        'licId' => 'licence'
    ];
    protected $filterForm = FilterForm::class;

    /**
     * Any inline scripts needed in this section
     *
     * @var array
     */
    protected $inlineScripts = array(
        'indexAction' => ['forms/filter', 'table-actions']
    );

    /**
     * @var array
     */
    protected $redirectConfig = [
        'add' => [
            'route' => 'submission',
            'action' => 'details',
            'reUseParams' => true,
            'resultIdMap' => [
                'section' => 'submissionSection'
            ]
        ],
        'edit' => [
            'route' => 'submission',
            'action' => 'details',
            'reUseParams' => true,
            'resultIdMap' => [
                'section' => 'submissionSection'
            ]
        ]
    ];

    public function getLeftView()
    {
        $view = new ViewModel();
        $view->setTemplate('sections/bus/partials/list-left');

        return $view;
    }
}
