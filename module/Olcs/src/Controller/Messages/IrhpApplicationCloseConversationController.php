<?php

declare(strict_types=1);

namespace Olcs\Controller\Messages;

use Laminas\Http\Response;

class IrhpApplicationCloseConversationController extends AbstractCloseConversationController
{
    protected function getRedirect(): Response
    {
        $params = [
            'licence' => $this->params()->fromRoute('licence'),
            'action'  => 'close',

        ];
        return $this->redirect()->toRouteAjax('licence/irhp_conversations', $params);
    }
}
