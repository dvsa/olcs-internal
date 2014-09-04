<?php

/**
 * Licence Processing Tasks Controller
 */
namespace Olcs\Controller\Licence\Processing;

use Zend\View\Model\ViewModel;
use \Olcs\Controller\Traits\TaskSearchTrait;

/**
 * Licence Processing Tasks Controller
 *
 * @author Ian Lindsay <ian@hemera-business-services.co.uk>
 */
class LicenceProcessingTasksController extends AbstractLicenceProcessingController
{
    use TaskSearchTrait;

    protected $section = 'tasks';

    public function indexAction()
    {
        $redirect = $this->processTasksActions('licence');
        if ($redirect) {
            return $redirect;
        }

        $this->pageLayout = 'licence';

        $filters = $this->mapTaskFilters(
            array('linkId' => $this->getFromRoute('licence'), 'linkType' => 'Licence')
        );

        $table = $this->getTaskTable($filters, false);

        $view = $this->getViewWithLicence(
            array(
                'table' => $table->render(),
                'form'  => $this->getTaskForm($filters),
                'inlineScript' => $this->loadScripts(['tasks'])
            )
        );

        $view->setTemplate('licence/processing');
        $view->setTerminal(
            $this->getRequest()->isXmlHttpRequest()
        );

        return $this->renderView($view);
    }
}
