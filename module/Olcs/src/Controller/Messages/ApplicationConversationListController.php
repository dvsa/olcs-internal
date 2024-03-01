<?php

namespace Olcs\Controller\Messages;

use Common\Controller\Interfaces\ToggleAwareInterface;
use Common\FeatureToggle;
use Dvsa\Olcs\Transfer\Query\Messaging\Conversations\ByApplicationToLicence as ConversationsByApplicationToLicenceQuery;
use Laminas\View\Model\ViewModel;
use Olcs\Controller\AbstractInternalController;
use Olcs\Controller\Interfaces\ApplicationControllerInterface;
use Olcs\Controller\Interfaces\LeftViewProvider;
use Olcs\Controller\Interfaces\MessagingControllerInterface;

class ApplicationConversationListController extends AbstractConversationListController implements ApplicationControllerInterface
{
    protected $navigationId = 'application_conversations';
    protected $listVars = ['application'];
    protected $listDto = ConversationsByApplicationToLicenceQuery::class;
}
