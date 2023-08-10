<?php

/**
 * Variation Overview Controller
 *
 * @author Rob Caiger <rob@clocal.co.uk>
 */
namespace Olcs\Controller\Lva\Variation;

use Common\Controller\Lva\AbstractController;
use Dvsa\Olcs\Utils\Translation\NiTextTranslation;
use Olcs\Controller\Lva\Traits\VariationControllerTrait;
use Olcs\Controller\Lva\Traits\ApplicationOverviewTrait;
use Olcs\Controller\Interfaces\VariationControllerInterface;
use ZfcRbac\Service\AuthorizationService;

/**
 * Application Overview Controller
 *
 * @author Rob Caiger <rob@clocal.co.uk>
 */
class OverviewController extends AbstractController implements VariationControllerInterface
{
    use VariationControllerTrait,
        ApplicationOverviewTrait;

    protected string $lva = 'variation';
    protected string $location = 'internal';

    /**
     * @param NiTextTranslation $niTextTranslationUtil
     * @param AuthorizationService $authService
     */
    public function __construct(
        NiTextTranslation $niTextTranslationUtil,
        AuthorizationService $authService
    ) {
        parent::__construct($niTextTranslationUtil, $authService);
    }
}
