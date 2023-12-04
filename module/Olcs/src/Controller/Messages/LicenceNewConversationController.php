<?php

namespace Olcs\Controller\Messages;

use Laminas\View\Model\ViewModel;
use Olcs\Controller\AbstractInternalController;
use Olcs\Controller\Interfaces\LeftViewProvider;
use Dvsa\Olcs\Transfer\Query\IrhpApplication\InternalApplicationsSummary;
use Olcs\Form\Model\Form\NewMessage;

class LicenceNewConversationController extends AbstractInternalController implements LeftViewProvider
{

    protected $navigationId = 'conversation_list_new_conversation';
    protected $listVars = ['licence'];
    protected $routeIdentifier = 'conversation/new';
    protected $formClass = NewMessage::class;

    public function indexAction()
    {
        return parent::indexAction();
    }

    public function addAction()
    {
        return parent::addAction();
    }

    /**
     * Get left view
     *
     * @return ViewModel
     */
    public function getLeftView()
    {
        $view = new ViewModel();
        $view->setTemplate('sections/messages/partials/left');

        return $view;
    }
}