<?php

/**
 * Operator Irfo Gv Permits Controller
 */
namespace Olcs\Controller\Operator;

use Dvsa\Olcs\Transfer\Command\Irfo\CreateIrfoGvPermit as CreateDto;
use Dvsa\Olcs\Transfer\Command\Irfo\ResetIrfoGvPermit as ResetDto;
use Dvsa\Olcs\Transfer\Command\Irfo\ApproveIrfoGvPermit as ApproveDto;
use Dvsa\Olcs\Transfer\Command\Irfo\WithdrawIrfoGvPermit as WithdrawDto;
use Dvsa\Olcs\Transfer\Command\Irfo\RefuseIrfoGvPermit as RefuseDto;
use Dvsa\Olcs\Transfer\Query\Irfo\IrfoGvPermit as ItemDto;
use Dvsa\Olcs\Transfer\Query\Irfo\IrfoGvPermitList as ListDto;
use Olcs\Controller\AbstractInternalController;
use Olcs\Controller\Interfaces\LeftViewProvider;
use Olcs\Controller\Interfaces\OperatorControllerInterface;
use Olcs\Data\Mapper\IrfoGvPermit as Mapper;
use Olcs\Form\Model\Form\IrfoGvPermit as Form;
use Zend\View\Model\ViewModel;
use Olcs\Mvc\Controller\ParameterProvider\GenericItem;

/**
 * Operator Irfo Gv Permits Controller
 */
class OperatorIrfoGvPermitsController extends AbstractInternalController implements
    OperatorControllerInterface,
    LeftViewProvider
{
    /**
     * Holds the navigation ID,
     * required when an entire controller is
     * represented by a single navigation id.
     */
    protected $navigationId = 'operator_irfo_gv_permits';

    /**
     * @var array
     */
    protected $inlineScripts = [
        'indexAction' => ['table-actions'],
        'addAction' => ['forms/irfo-gv-permit'],
    ];

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

    public function getLeftView()
    {
        $view = new ViewModel();
        $view->setTemplate('sections/operator/partials/left');

        return $view;
    }

    /**
     * Variables for controlling details view rendering
     * details view and itemDto are required.
     */
    protected $detailsViewTemplate = 'sections/operator/pages/irfo-gv-permit';
    protected $itemDto = ItemDto::class;
    protected $detailsContentTitle = 'GV Permits';

    /**
     * Variables for controlling edit view rendering
     * all these variables are required
     * itemDto (see above) is also required.
     */
    protected $formClass = Form::class;
    protected $mapperClass = Mapper::class;
    protected $addContentTitle = 'Add IRFO GV Permit';

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

    public function editAction()
    {
        return $this->notFoundAction();
    }

    public function deleteAction()
    {
        return $this->notFoundAction();
    }

    public function resetAction()
    {
        return $this->processCommand(new GenericItem(['id' => 'id']), ResetDto::class);
    }

    public function approveAction()
    {
        return $this->processCommand(new GenericItem(['id' => 'id']), ApproveDto::class);
    }

    public function withdrawAction()
    {
        return $this->processCommand(new GenericItem(['id' => 'id']), WithdrawDto::class);
    }

    public function refuseAction()
    {
        return $this->processCommand(new GenericItem(['id' => 'id']), RefuseDto::class);
    }
}
