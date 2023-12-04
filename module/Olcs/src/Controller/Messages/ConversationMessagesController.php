<?php

namespace Olcs\Controller\Messages;

use Laminas\View\Model\ViewModel;
use Olcs\Controller\AbstractInternalController;
use Olcs\Controller\Interfaces\LeftViewProvider;
use Olcs\Controller\Interfaces\LicenceControllerInterface;
use Dvsa\Olcs\Transfer\Query\Messaging\GetConversationMessages;
use Dvsa\Olcs\Transfer\Query\IrhpApplication\InternalApplicationsSummary;
use Common\Controller\Interfaces\ToggleAwareInterface;
use Common\FeatureToggle;
use Olcs\Controller\Interfaces\RightViewProvider;
use Olcs\Listener\RouteParam\Licence;

class ConversationMessagesController extends AbstractInternalController implements LeftViewProvider, LicenceControllerInterface, ToggleAwareInterface
{
    protected $navigationId = 'conversations';
    protected $listVars = ['licence'];

    // Can we get this based on config?
    // config/backend-routes/messaging/messaging.php
    protected $listDto = GetConversationMessages::class;

    // protected $routeIdentifier = 'messaging';
    protected $tableName = 'conversation-messages';
    protected $tableViewTemplate = 'pages/table';
    protected $toggleConfig = [
        'default' => [
            FeatureToggle::MESSAGING
        ],
    ];

    public function indexAction()
    {
        return parent::indexAction();
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

    /**
     * Get right view
     *
     * @return ViewModel
     */
    public function getRightView()
    {
        $view = new ViewModel();
        $view->setTemplate('sections/messages/partials/right');

        return $view;
    }
}