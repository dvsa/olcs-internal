<?php

/**
 * ReviveApplicationController.php
 */
namespace Olcs\Controller\Lva\Variation;

use Common\Service\Helper\FlashMessengerHelperService;
use Common\Service\Helper\FormHelperService;
use Common\Service\Helper\StringHelperService;
use Common\Service\Helper\TranslationHelperService;
use Dvsa\Olcs\Utils\Translation\NiTextTranslation;
use Olcs\Controller\Lva\AbstractReviveApplicationController;
use Olcs\Controller\Lva\Traits\VariationControllerTrait;
use ZfcRbac\Service\AuthorizationService;

/**
 * Class ReviveApplicationController
 *
 * @package Olcs\Controller\Lva\Variation
 *
 * @author Joshua Curtis <josh.curtis@valtech.co.uk>
 */
class ReviveApplicationController extends AbstractReviveApplicationController
{
    use VariationControllerTrait;

    protected $lva = 'variation';
    protected $location = 'internal';

    protected StringHelperService $stringHelper;

    /**
     * @param NiTextTranslation $niTextTranslationUtil
     * @param AuthorizationService $authService
     * @param FlashMessengerHelperService $flashMessengerHelper
     * @param TranslationHelperService $translationHelper
     * @param FormHelperService $formHelper
     * @param StringHelperService $stringHelper
     */
    public function __construct(
        NiTextTranslation $niTextTranslationUtil,
        AuthorizationService $authService,
        FlashMessengerHelperService $flashMessengerHelper,
        TranslationHelperService $translationHelper,
        FormHelperService $formHelper,
        StringHelperService $stringHelper
    ) {
        $this->stringHelper = $stringHelper;

        parent::__construct(
            $niTextTranslationUtil,
            $authService,
            $flashMessengerHelper,
            $translationHelper,
            $formHelper
        );
    }
}
