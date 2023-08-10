<?php

/**
 * Internal Variation Type Of Licence Controller
 *
 * @author Rob Caiger <rob@clocal.co.uk>
 */
namespace Olcs\Controller\Lva\Variation;

use Common\Controller\Lva\Variation\AbstractTypeOfLicenceController;
use Common\FormService\FormServiceManager;
use Common\Service\Helper\FlashMessengerHelperService;
use Common\Service\Script\ScriptFactory;
use Dvsa\Olcs\Utils\Translation\NiTextTranslation;
use Olcs\Controller\Lva\Traits;
use Olcs\Controller\Interfaces\VariationControllerInterface;
use ZfcRbac\Service\AuthorizationService;

/**
 * Internal Variation Type Of Licence Controller
 *
 * @author Rob Caiger <rob@clocal.co.uk>
 */
class TypeOfLicenceController extends AbstractTypeOfLicenceController implements VariationControllerInterface
{
    use Traits\VariationControllerTrait;

    protected $location = 'internal';
    protected $lva = 'variation';

    /**
     * @param NiTextTranslation $niTextTranslationUtil
     * @param AuthorizationService $authService
     * @param FlashMessengerHelperService $flashMessengerHelper
     * @param ScriptFactory $scriptFactory
     * @param FormServiceManager $formServiceManager
     */
    public function __construct(
        NiTextTranslation $niTextTranslationUtil,
        AuthorizationService $authService,
        FlashMessengerHelperService $flashMessengerHelper,
        ScriptFactory $scriptFactory,
        FormServiceManager $formServiceManager
    ) {
        parent::__construct(
            $niTextTranslationUtil,
            $authService,
            $flashMessengerHelper,
            $scriptFactory,
            $formServiceManager
        );
    }
}
