<?php

/**
 * Transport Manager Details Competence Controller
 * 
 * @author Alex Peshkov <alex.peshkov@valtech.co.uk>
 */
namespace Olcs\Controller\TransportManager\Details;

use Olcs\Controller\TransportManager\Details\AbstractTransportManagerDetailsController;

/**
 * Transport Manager Details Competence Controller
 * 
 * @author Alex Peshkov <alex.peshkov@valtech.co.uk>
 */
class TransportManagerDetailsCompetenceController extends AbstractTransportManagerDetailsController
{
    /**
     * @var string
     */
    protected $section = 'details-competences';

    /**
     * Placeholder stub
     *
     * @return ViewModel
     */
    public function indexAction()
    {
        $view = $this->getViewWithTm();
        $view->setTemplate('transport-manager/index');
        return $this->renderView($view);
    }
}
