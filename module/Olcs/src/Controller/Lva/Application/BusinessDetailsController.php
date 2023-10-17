<?php

/**
 * Internal Application Business Details Controller
 *
 * @author Nick Payne <nick.payne@valtech.co.uk>
 * @author Rob Caiger <rob@clocal.co.uk>
 */

namespace Olcs\Controller\Lva\Application;

use Common\Controller\Lva\AbstractBusinessDetailsController;
use Common\FormService\FormServiceManager;
use Common\Service\Helper\FileUploadHelperService;
use Common\Service\Helper\FlashMessengerHelperService;
use Common\Service\Helper\FormHelperService;
use Common\Service\Helper\RestrictionHelperService;
use Common\Service\Helper\StringHelperService;
use Common\Service\Script\ScriptFactory;
use Common\Service\Table\TableFactory;
use Dvsa\Olcs\Utils\Translation\NiTextTranslation;
use Olcs\Controller\Interfaces\ApplicationControllerInterface;
use Olcs\Controller\Lva\Traits\ApplicationControllerTrait;
use ZfcRbac\Identity\IdentityProviderInterface;
use ZfcRbac\Service\AuthorizationService;

/**
 * Internal Application Business Details Controller
 *
 * @author Nick Payne <nick.payne@valtech.co.uk>
 * @author Rob Caiger <rob@clocal.co.uk>
 */
class BusinessDetailsController extends AbstractBusinessDetailsController implements ApplicationControllerInterface
{
    use ApplicationControllerTrait;

    protected $lva = 'application';
    protected string $location = 'internal';

    protected StringHelperService $stringHelper;
    protected RestrictionHelperService $restrictionHelper;

    /**
     * @param NiTextTranslation           $niTextTranslationUtil
     * @param AuthorizationService        $authService
     * @param FormHelperService           $formHelper
     * @param FlashMessengerHelperService $flashMessengerHelper
     * @param FormServiceManager          $formServiceManager
     * @param ScriptFactory               $scriptFactory
     * @param IdentityProviderInterface   $identityProvider
     * @param StringHelperService         $stringHelper
     * @param TableFactory                $tableFactory
     * @param FileUploadHelperService     $fileUploadHelper
     * @param RestrictionHelperService    $restrictionHelper
     */
    public function __construct(
        NiTextTranslation $niTextTranslationUtil,
        AuthorizationService $authService,
        FormHelperService $formHelper,
        FlashMessengerHelperService $flashMessengerHelper,
        FormServiceManager $formServiceManager,
        ScriptFactory $scriptFactory,
        IdentityProviderInterface $identityProvider,
        StringHelperService $stringHelper,
        TableFactory $tableFactory,
        FileUploadHelperService $fileUploadHelper,
        RestrictionHelperService $restrictionHelper
    ) {
        $this->stringHelper = $stringHelper;
        $this->restrictionHelper = $restrictionHelper;

        parent::__construct(
            $niTextTranslationUtil,
            $authService,
            $formHelper,
            $flashMessengerHelper,
            $formServiceManager,
            $scriptFactory,
            $identityProvider,
            $tableFactory,
            $fileUploadHelper
        );
    }
}
