<?php

declare(strict_types=1);

namespace Olcs\Controller\Messages;

use Common\Controller\Interfaces\ToggleAwareInterface;
use Common\FeatureToggle;
use Dvsa\Olcs\Transfer\Query\Messaging\Conversations\ByLicence as ConversationsByLicenceQuery;
use Laminas\View\Model\ViewModel;
use Olcs\Controller\AbstractInternalController;
use Olcs\Controller\Interfaces\CaseControllerInterface;
use Olcs\Controller\Interfaces\LeftViewProvider;
use Olcs\Controller\Interfaces\MessagingControllerInterface;

class CaseConversationListController extends AbstractConversationListController implements CaseControllerInterface
{
    protected $navigationId = 'case_conversations';
    protected $listVars = ['licence'];
    protected $listDto = ConversationsByLicenceQuery::class;
}
