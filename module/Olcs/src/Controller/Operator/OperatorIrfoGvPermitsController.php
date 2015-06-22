<?php

/**
 * Operator Irfo Gv Permits Controller
 */
namespace Olcs\Controller\Operator;

use Dvsa\Olcs\Transfer\Command\Irfo\CreateIrfoGvPermit as CreateDto;
use Dvsa\Olcs\Transfer\Command\Irfo\UpdateIrfoGvPermit as UpdateDto;
use Dvsa\Olcs\Transfer\Query\Irfo\IrfoGvPermit as ItemDto;
use Dvsa\Olcs\Transfer\Query\Irfo\IrfoGvPermitList as ListDto;
use Olcs\Controller\AbstractInternalController;
use Olcs\Controller\Interfaces\OperatorControllerInterface;
use Olcs\Controller\Interfaces\PageInnerLayoutProvider;
use Olcs\Controller\Interfaces\PageLayoutProvider;
use Olcs\Data\Mapper\IrfoGvPermit as Mapper;
use Olcs\Form\Model\Form\IrfoGvPermit as Form;

/**
 * Operator Irfo Gv Permits Controller
 */
class OperatorIrfoGvPermitsController extends AbstractInternalController implements
    OperatorControllerInterface,
    PageLayoutProvider,
    PageInnerLayoutProvider
{
    /**
     * Holds the navigation ID,
     * required when an entire controller is
     * represented by a single navigation id.
     */
    protected $navigationId = 'operator_irfo_gv_permits';

    /*
     * Variables for controlling table/list rendering
     * tableName and listDto are required,
     * listVars probably needs to be defined every time but will work without
     */
    protected $tableViewPlaceholderName = 'table';
    protected $tableViewTemplate = 'pages/table-comments';
    protected $tableName = 'operator.irfo.gv-permits';
    protected $listDto = ListDto::class;
    protected $listVars = ['organisation'];

    public function getPageLayout()
    {
        return 'layout/operator-section';
    }

    public function getPageInnerLayout()
    {
        return 'layout/operator-subsection';
    }

    /**
     * Variables for controlling details view rendering
     * details view and itemDto are required.
     */
    protected $itemDto = ItemDto::class;

    /**
     * Variables for controlling edit view rendering
     * all these variables are required
     * itemDto (see above) is also required.
     */
    protected $formClass = Form::class;
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
        'organisation' => 'route',
        'irfoPermitStatus' => 'irfo_perm_s_pending'
    ];

    public function detailsAction()
    {
        return $this->notFoundAction();
    }

    public function deleteAction()
    {
        return $this->notFoundAction();
    }
}
