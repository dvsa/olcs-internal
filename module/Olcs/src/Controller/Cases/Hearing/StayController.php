<?php

/**
 * Stay Controller
 *
 * @author Ian Lindsay <ian@hemera-business-services.co.uk>
 * @author Craig Reasbeck <craig.reasbeck@valtech.co.uk>
 */
namespace Olcs\Controller\Cases\Hearing;

use Dvsa\Olcs\Transfer\Command\Cases\Hearing\CreateStay as CreateDto;
use Dvsa\Olcs\Transfer\Command\Cases\Hearing\UpdateStay as UpdateDto;
use Dvsa\Olcs\Transfer\Command\Cases\Hearing\DeleteStay as DeleteDto;
use Dvsa\Olcs\Transfer\Query\Cases\Hearing\Stay as StayDto;
use Dvsa\Olcs\Transfer\Query\Cases\Hearing\StayList as ListDto;
use Olcs\Controller\AbstractInternalController;
use Olcs\Controller\Interfaces\CaseControllerInterface;
use Olcs\Controller\Interfaces\PageInnerLayoutProvider;
use Olcs\Controller\Interfaces\PageLayoutProvider;
use Olcs\Form\Model\Form\CaseStay as FormClass;
use Olcs\Data\Mapper\Stay as Mapper;

/**
 * Stay Controller
 *
 * @author Ian Lindsay <ian@hemera-business-services.co.uk>
 * @author Craig Reasbeck <craig.reasbeck@valtech.co.uk>
 */
class StayController extends AbstractInternalController implements
    CaseControllerInterface,
    PageLayoutProvider,
    PageInnerLayoutProvider
{
    /**
     * Holds the navigation ID,
     * required when an entire controller is
     * represented by a single navigation id.
     */
    protected $navigationId = 'case_hearings_appeals_stays';

    protected $routeIdentifier = 'stay';

    /*
     * Variables for controlling table/list rendering
     * tableName and listDto are required,
     * listVars probably needs to be defined every time but will work without
     */
    protected $tableViewPlaceholderName = 'table';
    protected $tableViewTemplate = 'pages/table-comments';
    protected $defaultTableSortField = 'n/a';
    protected $tableName = 'stay';
    protected $listDto = ListDto::class;
    protected $listVars = ['case'];

    public function getPageLayout()
    {
        return 'layout/case-section';
    }

    public function getPageInnerLayout()
    {
        return 'layout/case-details-subsection';
    }

    /**
     * Variables for controlling details view rendering
     * details view and itemDto are required.
     */
    protected $detailsViewTemplate = 'pages/case/appeals-stays';
    protected $detailsViewPlaceholderName = '  ';
    protected $itemDto = StayDto::class;
    // 'id' => 'conviction', to => from
    protected $itemParams = [
        'case',
        'id' => 'stay',
    ];

    /**
     * Variables for controlling edit view rendering
     * all these variables are required
     * itemDto (see above) is also required.
     */
    protected $formClass = FormClass::class;
    protected $updateCommand = UpdateDto::class;
    protected $mapperClass = Mapper::class;

    /**
     * Variables for controlling edit view rendering
     * all these variables are required
     * itemDto (see above) is also required.
     */
    protected $createCommand = CreateDto::class;

    /**
     * Form data for the add form.
     *
     * Format is name => value
     * name => "route" means get value from route,
     * see conviction controller
     *
     * @var array
     */
    protected $defaultData = [
        'case' => 'route',
        'stayType' => 'route',
    ];

    /**
     * Variables for controlling the delete action.
     * Command is required, as are itemParams from above
     */
    protected $deleteCommand = DeleteDto::class;

    protected $inlineScripts = array('forms/hearings-appeal');

    /**
     * Allows override of default behaviour for redirects. See Case Overview Controller
     *
     * @var array
     */
    protected $redirectConfig = [
        'add' => [
            'action' => 'details',
            'route' => 'case_hearing_appeal',
            'reUseParams' => true,
        ],
        'edit' => [
            'action' => 'details',
            'route' => 'case_hearing_appeal',
            'reUseParams' => true,
        ],
    ];
}
