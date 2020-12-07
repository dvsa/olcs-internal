<?php

namespace Olcs\Controller;

use Dvsa\Olcs\Transfer\Query\Processing\History;
use Olcs\Controller\Interfaces\LeftViewProvider;
use Laminas\View\Model\ViewModel;
use Olcs\Form\Model\Form\EventHistory as EventHistorytForm;
use Olcs\Data\Mapper\EventHistory as Mapper;
use Dvsa\Olcs\Transfer\Query\EventHistory\EventHistory as ItemDto;

/**
 * Abstract History Controller
 *
 * @author Alex Peshkov <alex.peshkov@valtech.co.uk>
 */
class AbstractHistoryController extends AbstractInternalController implements LeftViewProvider
{
    protected $defaultTableSortField = 'eventDatetime';
    protected $tableName = 'event-history';
    protected $listDto = History::class;
    protected $itemDto = ItemDto::class;
    protected $formClass = EventHistorytForm::class;
    protected $mapperClass = Mapper::class;
    protected $editContentTitle = 'Action';
    protected $editViewTemplate = 'sections/processing/pages/event-history-popup';

    /**
     * Get left view
     *
     * @return ViewModel
     */
    public function getLeftView()
    {
        $view = new ViewModel();
        $view->setTemplate('sections/processing/partials/left');

        return $view;
    }

    /**
     * Alter form for edit
     *
     * @param \Common\Form\Form $form     Form
     * @param array             $formData Form data
     *
     * @return \Common\Form\Form
     */
    public function alterFormForEdit($form, $formData)
    {
        $this->placeholder()->setPlaceholder('readOnlyData', $formData['readOnlyData']);
        if (is_array($formData['eventHistoryDetails']) && count($formData['eventHistoryDetails'])) {
            $form->get('event-history-details')->get('table')->get('table')->setTable(
                $this->getDetailsTable($formData['eventHistoryDetails'])
            );
        } else {
            $this->getServiceLocator()->get('Helper\Form')->remove($form, 'event-history-details->table');
        }
        return $form;
    }

    /**
     * Get event details table
     *
     * @param array $details Details
     *
     * @return \Common\Service\Table\TableBuilder
     */
    protected function getDetailsTable($details)
    {
        return $this->getServiceLocator()
            ->get('Table')
            ->prepareTable('event-history-details', $details);
    }
}
