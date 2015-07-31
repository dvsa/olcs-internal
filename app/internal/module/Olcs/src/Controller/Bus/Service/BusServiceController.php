<?php

/**
 * Bus Service Controller
 */
namespace Olcs\Controller\Bus\Service;

use Dvsa\Olcs\Transfer\Command\Bus\UpdateServiceRegister as UpdateDto;
use Dvsa\Olcs\Transfer\Query\Bus\BusReg as ItemDto;
use Dvsa\Olcs\Transfer\Query\ConditionUndertaking\GetList as ConditionUndertakingListDto;
use Olcs\Controller\AbstractInternalController;
use Olcs\Controller\Interfaces\BusRegControllerInterface;
use Olcs\Controller\Interfaces\PageLayoutProvider;
use Olcs\Controller\Interfaces\PageInnerLayoutProvider;
use Olcs\Data\Mapper\BusRegisterService as Mapper;
use Olcs\Form\Model\Form\BusRegisterService as Form;
use Common\RefData;

/**
 * Bus Service Controller
 */
class BusServiceController extends AbstractInternalController implements
    BusRegControllerInterface,
    PageLayoutProvider,
    PageInnerLayoutProvider
{
    const CONDITION_TYPE_CONDITION = 'cdt_con';

    /**
     * Holds the navigation ID,
     * required when an entire controller is
     * represented by a single navigation id.
     */
    protected $navigationId = 'licence_bus_register_service';

    protected $redirectConfig = [
        'edit' => [
            'action' => 'edit'
        ]
    ];

    public function getPageLayout()
    {
        return 'layout/bus-registrations-section';
    }

    public function getPageInnerLayout()
    {
        return 'layout/wide-layout';
    }

    /**
     * Variables for controlling details view rendering
     * details view and itemDto are required.
     */
    protected $itemDto = ItemDto::class;
    protected $itemParams = ['id' => 'busRegId'];

    /**
     * Variables for controlling edit view rendering
     * all these variables are required
     * itemDto (see above) is also required.
     */
    protected $formClass = Form::class;
    protected $updateCommand = UpdateDto::class;
    protected $mapperClass = Mapper::class;

    /**
     * @param $name
     * @return mixed
     */
    public function getForm($name)
    {
        $formHelper = $this->getServiceLocator()->get('Helper\Form');

        $form = $formHelper->createForm($name);
        $formHelper->setFormActionFromRequest($form, $this->getRequest());
        $formHelper->populateFormTable($form->get('conditions')->get('table'), $this->getConditionsTable());

        return $form;
    }

    /**
     * Get conditions table
     */
    protected function getConditionsTable()
    {
        return $this->getServiceLocator()->get('Table')->prepareTable('Bus/conditions', $this->getTableData());
    }

    /**
     * Get table data
     *
     * @return array
     */
    protected function getTableData()
    {
        $query = ConditionUndertakingListDto::class;
        $data = [
            'licence' => $this->params()->fromRoute('licence'),
            'conditionType' => self::CONDITION_TYPE_CONDITION
        ];

        $response = $this->handleQuery($query::create($data));

        if ($response->isServerError() || $response->isClientError()) {
            $this->getServiceLocator()->get('Helper\FlashMessenger')->addErrorMessage('unknown-error');
        }

        if ($response->isOk()) {
            return $response->getResult();
        }

        return [];
    }

    /**
     * Alter Form for edit
     *
     * @param \Common\Controller\Form $form
     * @param array $formData
     * @return \Common\Controller\Form
     */
    public function alterFormForEdit($form, $formData)
    {
        if (!$formData['fields']['isLatestVariation'] ||
            in_array(
                $formData['fields']['status'], [RefData::STATUS_REGISTERED, RefData::STATUS_CANCELLED]
            )) {
            $form->setOption('readonly', true);
        }

        if ($formData['fields']['status'] == 'breg_s_cancelled') {
            $form->remove('timetable');
        }

        // If Scottish rules identified by busNoticePeriod = 1, remove radio and replace with hidden field
        if ($formData['fields']['busNoticePeriod'] !== 1) {
            $form->get('fields')->remove('opNotifiedLaPte');
        } else {
            $form->get('fields')->remove('opNotifiedLaPteHidden');
        }

        return $form;
    }

    public function indexAction()
    {
        return $this->notFoundAction();
    }

    public function detailsAction()
    {
        return $this->notFoundAction();
    }

    public function addAction()
    {
        return $this->notFoundAction();
    }

    public function deleteAction()
    {
        return $this->notFoundAction();
    }
}
