<?php

namespace Olcs\Controller\Lva\Licence;

use Common\FormService\FormServiceManager;
use Common\Service\Helper\FlashMessengerHelperService;
use Common\Service\Helper\FormHelperService;
use Common\Service\Script\ScriptFactory;
use Dvsa\Olcs\Utils\Translation\NiTextTranslation;
use Olcs\Controller\Interfaces\LicenceControllerInterface;
use Common\Controller\Lva\AbstractAddressesController;
use Olcs\Controller\Lva\Traits\LicenceControllerTrait;
use ZfcRbac\Service\AuthorizationService;

/**
 * Internal Licence Addresses Controller
 *
 * @author Nick Payne <nick.payne@valtech.co.uk>
 * @author Rob Caiger <rob@clocal.co.uk>
 */
class AddressesController extends AbstractAddressesController implements LicenceControllerInterface
{
    use LicenceControllerTrait;

    protected string $lva = 'licence';
    protected string $location = 'internal';

    /**
     * @param NiTextTranslation $niTextTranslationUtil
     * @param AuthorizationService $authService
     * @param FormHelperService $formHelper
     * @param FlashMessengerHelperService $flashMessengerHelper
     * @param FormServiceManager $formServiceManager
     * @param ScriptFactory $scriptFactory
     */
    public function __construct(
        NiTextTranslation $niTextTranslationUtil,
        AuthorizationService $authService,
        FormHelperService $formHelper,
        FlashMessengerHelperService $flashMessengerHelper,
        FormServiceManager $formServiceManager,
        ScriptFactory $scriptFactory
    ) {
        parent::__construct($niTextTranslationUtil, $authService, $formHelper, $flashMessengerHelper, $formServiceManager, $scriptFactory);
    }
}
