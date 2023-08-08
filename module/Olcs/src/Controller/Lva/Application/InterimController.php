<?php

/**
 * Internal Application Interim Controller
 *
 * @author Alex Peshkov <alex.peshkov@valtech.co.uk>
 */
namespace Olcs\Controller\Lva\Application;

use Common\Service\Helper\FlashMessengerHelperService;
use Common\Service\Helper\FormHelperService;
use Common\Service\Script\ScriptFactory;
use Dvsa\Olcs\Utils\Translation\NiTextTranslation;
use Olcs\Controller\Lva\AbstractInterimController;
use Olcs\Controller\Lva\Traits\ApplicationControllerTrait;
use Olcs\Controller\Interfaces\ApplicationControllerInterface;
use Dvsa\Olcs\Transfer\Command\Application\UpdateInterim;
use Olcs\Mvc\Controller\Plugin\TableFactory;
use ZfcRbac\Service\AuthorizationService;

/**
 * Internal Application Interim Controller
 *
 * @author Alex Peshkov <alex.peshkov@valtech.co.uk>
 * @author Joshua Curtis <josh.curtis@valtech.co.uk>
 */
class InterimController extends AbstractInterimController implements ApplicationControllerInterface
{
    use ApplicationControllerTrait;

    protected $lva = 'application';
    protected $location = 'internal';
    protected $updateInterimCommand = UpdateInterim::class;

    /**
     * @param NiTextTranslation $niTextTranslationUtil
     * @param AuthorizationService $authService
     * @param FlashMessengerHelperService $flashMessengerHelper
     * @param FormHelperService $formHelper
     * @param ScriptFactory $scriptFactory
     * @param TableFactory $tableFactory
     */
    public function __construct(
        NiTextTranslation $niTextTranslationUtil,
        AuthorizationService $authService,
        FlashMessengerHelperService $flashMessengerHelper,
        FormHelperService $formHelper,
        ScriptFactory $scriptFactory,
        TableFactory $tableFactory
    )
    {
        $this->flashMessengerHelper = $flashMessengerHelper;
        $this->formHelper = $formHelper;
        $this->scriptFactory = $scriptFactory;
        $this->tableFactory = $tableFactory;

        parent::__construct(
            $niTextTranslationUtil,
            $authService,
            $flashMessengerHelper,
            $formHelper,
            $scriptFactory,
            $tableFactory
        );
    }
}
