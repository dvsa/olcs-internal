<?php

/**
 * Bus Processing Controller
 *
 * @author Ian Lindsay <ian@hemera-business-services.co.uk>
 */
namespace Olcs\Controller\Bus\Processing;

use Olcs\Controller\Bus\BusController;

/**
 * Bus Processing Controller
 *
 * @author Ian Lindsay <ian@hemera-business-services.co.uk>
 */
class BusProcessingController extends BusController
{
    protected $section = 'processing';

    public function indexAction()
    {
        $view = $this->getViewWithLicence();

        $view->setTemplate('licence/bus/index');
        return $this->viewVars($view, 'licence_bus_processing');
    }
}
