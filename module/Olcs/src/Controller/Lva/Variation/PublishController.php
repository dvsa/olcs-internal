<?php

namespace Olcs\Controller\Lva\Variation;

use Common\Service\Helper\FormHelperService;
use Dvsa\Olcs\Utils\Translation\NiTextTranslation;
use Olcs\Controller\Lva\Traits\VariationControllerTrait;
use ZfcRbac\Service\AuthorizationService;

/**
 * Variation PublishController
 *
 * @author Mat Evans <mat.evans@valtech.co.uk>
 */
class PublishController extends \Olcs\Controller\Lva\AbstractPublishController
{
    use VariationControllerTrait;

    protected $lva = 'variation';
    protected $location = 'internal';

    /**
     * @param NiTextTranslation $niTextTranslationUtil
     * @param AuthorizationService $authService
     * @param FormHelperService $formHelper
     */
    public function __construct(
        NiTextTranslation $niTextTranslationUtil,
        AuthorizationService $authService,
        FormHelperService $formHelper
    ) {
        parent::__construct($niTextTranslationUtil, $authService, $formHelper);
    }
}
