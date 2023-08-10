<?php

/**
 * Class TrailersController
 *
 * @author Alex Peshkov <alex.peshkov@valtech.co.uk>
 */
namespace Olcs\Controller\Lva\Licence;

use Common\Controller\Lva;
use Common\FormService\FormServiceManager;
use Common\Service\Cqrs\Query\QuerySender;
use Common\Service\Helper\DateHelperService;
use Common\Service\Helper\FlashMessengerHelperService;
use Common\Service\Helper\FormHelperService;
use Common\Service\Script\ScriptFactory;
use Common\Service\Table\TableFactory;
use Dvsa\Olcs\Utils\Translation\NiTextTranslation;
use Olcs\Controller\Lva\Traits\LicenceControllerTrait;
use Olcs\Controller\Interfaces\LicenceControllerInterface;
use ZfcRbac\Service\AuthorizationService;

/**
 * Class TrailersController
 *
 * @author Alex Peshkov <alex.peshkov@valtech.co.uk>
 */
class TrailersController extends Lva\AbstractTrailersController implements LicenceControllerInterface
{
    use LicenceControllerTrait;

    protected $lva = 'licence';
    protected $location = 'internal';

    /**
     * @param NiTextTranslation $niTextTranslationUtil
     * @param AuthorizationService $authService
     * @param FormHelperService $formHelper
     * @param FormServiceManager $formServiceManager
     * @param FlashMessengerHelperService $flashMessengerHelper
     * @param TableFactory $tableFactory
     * @param ScriptFactory $scriptFactory
     * @param DateHelperService $dateHelper
     * @param QuerySender $querySender
     */
    public function __construct(
        NiTextTranslation $niTextTranslationUtil,
        AuthorizationService $authService,
        FormHelperService $formHelper,
        FormServiceManager $formServiceManager,
        FlashMessengerHelperService $flashMessengerHelper,
        TableFactory $tableFactory,
        ScriptFactory $scriptFactory,
        DateHelperService $dateHelper,
        QuerySender $querySender
    ) {
        $this->formHelper = $formHelper;
        $this->formServiceManager = $formServiceManager;
        $this->scriptFactory = $scriptFactory;
        $this->flashMessengerHelper = $flashMessengerHelper;
        $this->tableFactory = $tableFactory;
        $this->dateHelper = $dateHelper;
        $this->querySender = $querySender;

        parent::__construct($niTextTranslationUtil, $authService);
    }
}
