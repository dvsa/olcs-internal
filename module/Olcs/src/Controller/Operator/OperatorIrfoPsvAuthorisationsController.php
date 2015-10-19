<?php

/**
 * Operator Irfo Psv Authorisations Controller
 */
namespace Olcs\Controller\Operator;

use Dvsa\Olcs\Transfer\Command\Irfo\CreateIrfoPsvAuth as CreateDto;
use Dvsa\Olcs\Transfer\Command\Irfo\UpdateIrfoPsvAuth as UpdateDto;
use Dvsa\Olcs\Transfer\Query\Irfo\IrfoPsvAuth as ItemDto;
use Dvsa\Olcs\Transfer\Query\Irfo\IrfoPsvAuthList as ListDto;
use Olcs\Controller\AbstractInternalController;
use Olcs\Controller\Interfaces\LeftViewProvider;
use Olcs\Controller\Interfaces\OperatorControllerInterface;
use Olcs\Data\Mapper\IrfoPsvAuth as Mapper;
use Olcs\Form\Model\Form\IrfoPsvAuth as Form;
use Zend\View\Model\ViewModel;
use Common\RefData;
use Zend\Form\Form as ZendForm;

/**
 * Operator Irfo Psv Authorisations Controller
 */
class OperatorIrfoPsvAuthorisationsController extends AbstractInternalController implements
    OperatorControllerInterface,
    LeftViewProvider
{
    /**
     * Holds the navigation ID,
     * required when an entire controller is
     * represented by a single navigation id.
     */
    protected $navigationId = 'operator_irfo_psv_authorisations';

    /**
     * @var array
     */
    protected $inlineScripts = [
        'indexAction' => ['table-actions'],
        'addAction' => ['forms/irfo-psv-auth-numbers', 'forms/irfo-psv-auth-copies'],
        'editAction' => ['forms/irfo-psv-auth-numbers', 'forms/irfo-psv-auth-copies'],
    ];

    /*
     * Variables for controlling table/list rendering
     * tableName and listDto are required,
     * listVars probably needs to be defined every time but will work without
     */
    protected $tableViewPlaceholderName = 'table';
    protected $tableViewTemplate = 'pages/table-comments';
    protected $tableName = 'operator.irfo.psv-authorisations';
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
    protected $itemDto = ItemDto::class;

    /**
     * Variables for controlling edit view rendering
     * all these variables are required
     * itemDto (see above) is also required.
     */
    protected $formClass = Form::class;
    protected $updateCommand = UpdateDto::class;
    protected $mapperClass = Mapper::class;
    protected $addContentTitle = 'Add IRFO PSV Authorisation';
    protected $editContentTitle = 'Edit IRFO PSV Authorisation';

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
        'status' => 'irfo_auth_s_pending',
    ];

    public function detailsAction()
    {
        return $this->notFoundAction();
    }

    public function deleteAction()
    {
        return $this->notFoundAction();
    }

    /**
     * Method to alter the form based on status
     *
     * @param $form
     * @param $formData
     * @return mixed
     */
    protected function alterFormForEdit(ZendForm $form, $formData)
    {
        $status = isset($formData['fields']['status']) ? $formData['fields']['status'] : '';
        $readOnlyfields = [];
        switch($status)
        {
            case RefData::IRFO_PSV_AUTH_STATUS_PENDING:
                $readOnlyfields = ['irfoPsvAuthType', 'status', 'isFeeExemptApplication'];
                break;
            case RefData::IRFO_PSV_AUTH_STATUS_RENEW:
                $readOnlyfields = ['irfoPsvAuthType', 'status'];
                break;
            case RefData::IRFO_PSV_AUTH_STATUS_GRANTED:
            case RefData::IRFO_PSV_AUTH_STATUS_APPROVED:
                $readOnlyfields = ['irfoPsvAuthType', 'status', 'validityPeriod', 'isFeeExemptApplication',
                    'isFeeExemptAnnual', 'exemptionDetails', 'copiesRequired', 'copiesRequiredTotal'
                ];
                break;
            case RefData::IRFO_PSV_AUTH_STATUS_CNS:
            case RefData::IRFO_PSV_AUTH_STATUS_REFUSED:
            case RefData::IRFO_PSV_AUTH_STATUS_WITHDRAWN:
            default:
                // Every status needs to be handled here to avoid displaying a form where data shouldn't be edited.
                $form->setOption('readonly', true);
        }

        foreach ($readOnlyfields as $field) {
            $field = $form->get('fields')->get($field);
            $field->setAttribute('disabled', 'disabled');
        }

        return $form;
    }
}
