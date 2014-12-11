<?php

/**
 * Bus Docs Controller
 *
 * @author Ian Lindsay <ian@hemera-business-services.co.uk>
 */
namespace Olcs\Controller\Bus\Docs;

use Olcs\Controller\Bus\BusController;

/**
 * Bus Docs Controller
 *
 * @author Ian Lindsay <ian@hemera-business-services.co.uk>
 */
class BusDocsController extends BusController
{
    protected $section = 'docs';
    protected $subNavRoute = 'licence_bus_docs';

    /**
     * Index action
     *
     * @return \Zend\View\Model\ViewModel
     */
    public function indexAction()
    {
        $view = $this->getViewWithBusReg();

        $view->setTemplate('view-new/pages/placeholder');
        return $this->renderView($view);
    }
}
