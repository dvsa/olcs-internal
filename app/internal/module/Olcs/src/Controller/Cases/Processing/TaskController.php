<?php

/**
 * Case Task controller
 * Case task search and display
 *
 * @author Dan Eggleston <dan@stolenegg.com>
 */
namespace Olcs\Controller\Cases\Processing;

use Olcs\Controller as OlcsController;
use Olcs\Controller\Traits as ControllerTraits;

/**
 * Case Task controller
 * Case task search and display
 *
 * @author Dan Eggleston <dan@stolenegg.com>
 */
class TaskController extends OlcsController\CrudAbstract
{
    use ControllerTraits\TaskSearchTrait;
    use ControllerTraits\CaseControllerTrait;
    use ControllerTraits\ListDataTrait;

    /**
     * The current page's extra layout, over and above the
     * standard base template, a sibling of the base though.
     *
     * @var string
     */
    protected $pageLayout = 'case-section';

    /**
     * For most case crud controllers, we use the layout/case-details-subsection
     * layout file. Except submissions.
     *
     * @var string
     */
    protected $pageLayoutInner = 'layout/case-details-subsection';

    /**
     * Holds the navigation ID,
     * required when an entire controller is
     * represented by a single navigation id.
     */
    protected $navigationId = 'case_processing_tasks';

    /**
     * Render the tasks list or redirect if processing
     *
     * @return \Zend\View\Model\ViewModel
     */
    public function indexAction()
    {
        $redirect = $this->processTasksActions('case');
        if ($redirect) {
            return $redirect;
        }

        $case = $this->getCase($this->params()->fromRoute('case', null));

        $filters = $this->mapTaskFilters(
            array(
                'assignedToTeam' => '',
                'assignedToUser' => '',
            )
        );

        $tableFilters = array_merge($filters, $this->getIdArrayForCase($case));

        $table = $this->getTaskTable($tableFilters);
        $table->removeColumn('name');
        $table->removeColumn('link');

        $this->setTableFilters($this->getTaskForm($filters));

        $this->loadScripts(['tasks', 'table-actions', 'forms/filter']);

        $view = $this->getView(['table' => $table]);
        $view->setTemplate('partials/table');

        return $this->renderView($view);
    }

    public function getIdArrayForCase($case)
    {
        $filter = array();

        switch ($case) {
            case !is_null($case['licence']):
                $filter['licenceId'] = $case['licence']['id'];
                break;
            case !is_null($case['transportManager']):
                $filter['transportManagerId'] = $case['transportManager']['id'];
                break;
            default:
                break;
        }

        $filter['caseId'] = $case['id'];

        return array($filter);
    }
}
