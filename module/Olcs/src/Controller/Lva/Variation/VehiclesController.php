<?php

/**
 * Internal Variation Vehicles Controller
 *
 * @author Nick Payne <nick.payne@valtech.co.uk>
 * @author Rob Caiger <rob@clocal.co.uk>
 */

namespace Olcs\Controller\Lva\Variation;

use Common\Data\Mapper\Lva\GoodsVehiclesVehicle;
use Common\FormService\FormServiceManager;
use Common\Service\Helper\FlashMessengerHelperService;
use Common\Service\Helper\FormHelperService;
use Common\Service\Helper\GuidanceHelperService;
use Common\Service\Helper\ResponseHelperService;
use Common\Service\Helper\StringHelperService;
use Common\Service\Helper\TranslationHelperService;
use Common\Service\Lva\VariationLvaService;
use Common\Service\Script\ScriptFactory;
use Common\Service\Table\TableFactory;
use Dvsa\Olcs\Utils\Translation\NiTextTranslation;
use Olcs\Controller\Lva\AbstractGenericVehiclesController;
use Olcs\Controller\Lva\Traits\VariationControllerTrait;
use Common\Controller\Lva\Traits;
use Olcs\Controller\Interfaces\VariationControllerInterface;
use ZfcRbac\Service\AuthorizationService;

/**
 * Internal Variation Vehicles Controller
 *
 * @author Nick Payne <nick.payne@valtech.co.uk>
 * @author Rob Caiger <rob@clocal.co.uk>
 */
class VehiclesController extends AbstractGenericVehiclesController implements VariationControllerInterface
{
    use VariationControllerTrait;

    protected $lva = 'variation';
    protected string $location = 'internal';

    protected StringHelperService $stringHelper;

    /**
     * @param NiTextTranslation $niTextTranslationUtil
     * @param AuthorizationService $authService
     * @param FormHelperService $formHelper
     * @param FlashMessengerHelperService $flashMessengerHelper
     * @param FormServiceManager $formServiceManager
     * @param TableFactory $tableFactory
     * @param GuidanceHelperService $guidanceHelper
     * @param TranslationHelperService $translationHelper
     * @param ScriptFactory $scriptFactory
     * @param VariationLvaService $variationLvaService
     * @param GoodsVehiclesVehicle $goodsVehiclesVehicleMapper
     * @param ResponseHelperService $responseHelper
     * @param StringHelperService $stringHelper
     */
    public function __construct(
        NiTextTranslation $niTextTranslationUtil,
        AuthorizationService $authService,
        FormHelperService $formHelper,
        FlashMessengerHelperService $flashMessengerHelper,
        FormServiceManager $formServiceManager,
        TableFactory $tableFactory,
        GuidanceHelperService $guidanceHelper,
        TranslationHelperService $translationHelper,
        ScriptFactory $scriptFactory,
        VariationLvaService $variationLvaService,
        GoodsVehiclesVehicle $goodsVehiclesVehicleMapper,
        ResponseHelperService $responseHelper,
        StringHelperService $stringHelper
    ) {
        $this->stringHelper = $stringHelper;

        parent::__construct(
            $niTextTranslationUtil,
            $authService,
            $formHelper,
            $flashMessengerHelper,
            $formServiceManager,
            $tableFactory,
            $guidanceHelper,
            $translationHelper,
            $scriptFactory,
            $variationLvaService,
            $goodsVehiclesVehicleMapper,
            $responseHelper
        );
    }
}
