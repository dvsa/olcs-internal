<?php

namespace Olcs\Controller\TransportManager\Processing;

use Olcs\Controller\Traits;

/**
 * Transport Manager Processing Task Controller
 *
 * @author Alex Peshkov <alex.peshkov@valtech.co.uk>
 * @author Dan Eggleston <dan@stolenegg.com>
 */
class TransportManagerProcessingTaskController extends AbstractTransportManagerProcessingController
{
    use Traits\TaskActionTrait {
        Traits\TaskActionTrait::getTaskForm as trait_getTaskForm;
    }

    /**
     * Get task action type
     *
     * @see \Olcs\Controller\Traits\TaskActionTrait
     * @return string
     */
    protected function getTaskActionType()
    {
        return 'transportManager';
    }

    /**
     * Get task action filters
     *
     * @see \Olcs\Controller\Traits\TaskActionTrait
     * @return array
     */
    protected function getTaskActionFilters()
    {
        return [
            'transportManager' => $this->getFromRoute('transportManager'),
            'assignedToTeam' => '',
            'assignedToUser' => ''
        ];
    }

    /**
     * Create filter form
     *
     * @param array $filters Field values
     *
     * @return \Zend\Form\FormInterface
     */
    protected function getTaskForm(array $filters = [])
    {
        return $this->trait_getTaskForm($filters)
            ->remove('showTasks');
    }
}
