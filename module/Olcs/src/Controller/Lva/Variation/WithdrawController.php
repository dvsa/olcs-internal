<?php

/**
 * Variation Withdraw Controller
 *
 * @author Dan Eggleston <dan@stolenegg.com>
 */
namespace Olcs\Controller\Lva\Variation;

use Common\Service\Helper\FlashMessengerHelperService;
use Common\Service\Helper\FormHelperService;
use Common\Service\Helper\TranslationHelperService;
use Dvsa\Olcs\Utils\Translation\NiTextTranslation;
use Olcs\Controller\Lva\AbstractWithdrawController;
use Olcs\Controller\Lva\Traits\VariationControllerTrait;
use Olcs\Controller\Interfaces\VariationControllerInterface;
use ZfcRbac\Service\AuthorizationService;

/**
 * Variation Withdraw Controller
 *
 * @author Dan Eggleston <dan@stolenegg.com>
 */
class WithdrawController extends AbstractWithdrawController implements VariationControllerInterface
{
    use VariationControllerTrait;

    protected $lva = 'variation';
    protected $location = 'internal';

    /**
     * @param NiTextTranslation $niTextTranslationUtil
     * @param AuthorizationService $authService
     * @param FlashMessengerHelperService $flashMessengerHelper
     * @param TranslationHelperService $translationHelper
     * @param FormHelperService $formHelper
     */
    public function __construct(
        NiTextTranslation $niTextTranslationUtil,
        AuthorizationService $authService,
        FlashMessengerHelperService $flashMessengerHelper,
        TranslationHelperService $translationHelper,
        FormHelperService $formHelper
    ) {
        parent::__construct(
            $niTextTranslationUtil,
            $authService,
            $flashMessengerHelper,
            $translationHelper,
            $formHelper
        );
    }
}
