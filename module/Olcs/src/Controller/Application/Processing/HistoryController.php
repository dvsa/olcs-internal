<?php

namespace Olcs\Controller\Application\Processing;

use Common\Service\Helper\FlashMessengerHelperService;
use Common\Service\Helper\FormHelperService;
use Common\Service\Helper\TranslationHelperService;
use Laminas\Navigation\Navigation;
use Olcs\Controller\Interfaces\ApplicationControllerInterface;
use Olcs\Controller\AbstractHistoryController;

class HistoryController extends AbstractHistoryController implements ApplicationControllerInterface
{
    protected $navigationId = 'application_processing_history';
    protected $listVars = ['application'];
    protected $itemParams = ['application', 'id' => 'id'];
    public function __construct(
        TranslationHelperService $translationHelper,
        FormHelperService $formHelper,
        FlashMessengerHelperService $flashMessenger,
        Navigation $navigation
    )
    {
        parent::__construct($translationHelper, $formHelper, $flashMessenger, $navigation);
    }
}
