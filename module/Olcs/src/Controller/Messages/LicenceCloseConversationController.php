<?php

declare(strict_types=1);

namespace Olcs\Controller\Messages;

use Common\Controller\Interfaces\ToggleAwareInterface;
use Common\FeatureToggle;
use Common\Service\Helper\FlashMessengerHelperService;
use Common\Service\Helper\FormHelperService;
use Common\Service\Script\ScriptFactory;
use Common\Service\Table\TableFactory;
use Dvsa\Olcs\Transfer\Command\Messaging\Conversation\Close;
use Laminas\Http\Response;
use Laminas\View\HelperPluginManager;
use Laminas\View\Model\ViewModel;
use Olcs\Controller\AbstractController;
use Olcs\Data\Mapper\Task;
use Olcs\Form\Model\Form\CloseConversation;

class LicenceCloseConversationController extends AbstractCloseConversationController
{
    protected function getRedirect(): Response
    {
        $params = [
            'licence' => $this->params()->fromRoute('licence'),
            'action'  => 'close',

        ];
        return $this->redirect()->toRouteAjax('licence/conversation', $params);
    }
}
