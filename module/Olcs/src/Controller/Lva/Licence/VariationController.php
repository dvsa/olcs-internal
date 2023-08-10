<?php

/**
 * Licence Variation Controller
 *
 * @author Nick Payne <nick.payne@valtech.co.uk>
 * @author Rob Caiger <rob@clocal.co.uk>
 */

namespace Olcs\Controller\Lva\Licence;

use Common\Service\Helper\TranslationHelperService;
use Dvsa\Olcs\Utils\Translation\NiTextTranslation;
use Olcs\Controller\Lva\Traits\LicenceControllerTrait;
use Common\Controller\Lva\AbstractVariationController;
use Olcs\Controller\Interfaces\LicenceControllerInterface;
use ZfcRbac\Service\AuthorizationService;

/**
 * Licence Variation Controller
 *
 * @author Nick Payne <nick.payne@valtech.co.uk>
 * @author Rob Caiger <rob@clocal.co.uk>
 */
class VariationController extends AbstractVariationController implements LicenceControllerInterface
{
    use LicenceControllerTrait;

    protected $lva = 'licence';
    protected $location = 'internal';

    /**
     * @param NiTextTranslation $niTextTranslationUtil
     * @param AuthorizationService $authService
     * @param TranslationHelperService $translationHelper
     * @param $processingCreateVariation
     */
    public function __construct(
        NiTextTranslation $niTextTranslationUtil,
        AuthorizationService $authService,
        TranslationHelperService $translationHelper,
        $processingCreateVariation
    ) {
        parent::__construct(
            $niTextTranslationUtil,
            $authService,
            $translationHelper,
            $processingCreateVariation
        );
    }
}
