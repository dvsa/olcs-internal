<?php

/**
 * Application Overview Controller
 *
 * @author Rob Caiger <rob@clocal.co.uk>
 * @author Dan Eggleston <dan@stolenegg.com>
 */

namespace Olcs\Controller\Lva\Application;

use Common\Controller\Lva\AbstractController;
use Common\Service\Helper\FlashMessengerHelperService;
use Common\Service\Helper\StringHelperService;
use Common\Service\Helper\TranslationHelperService;
use Dvsa\Olcs\Utils\Translation\NiTextTranslation;
use Olcs\Controller\Interfaces\ApplicationControllerInterface;
use Olcs\Controller\Lva\Traits\ApplicationControllerTrait;
use Olcs\Controller\Lva\Traits\ApplicationOverviewTrait;
use ZfcRbac\Service\AuthorizationService;

/**
 * Application Overview Controller
 *
 * @author Rob Caiger <rob@clocal.co.uk>
 * @author Dan Eggleston <dan@stolenegg.com>
 */
class OverviewController extends AbstractController implements ApplicationControllerInterface
{
    use ApplicationControllerTrait;
    use ApplicationOverviewTrait;

    protected string $lva = 'application';
    protected string $location = 'internal';

    protected StringHelperService $stringHelper;

    /**
     * @param NiTextTranslation $niTextTranslationUtil
     * @param AuthorizationService $authService
     * @param StringHelperService $stringHelper
     */
    public function __construct(
        NiTextTranslation $niTextTranslationUtil,
        AuthorizationService $authService,
        StringHelperService $stringHelper
    ) {
        $this->stringHelper = $stringHelper;

        parent::__construct($niTextTranslationUtil, $authService);
    }
}
