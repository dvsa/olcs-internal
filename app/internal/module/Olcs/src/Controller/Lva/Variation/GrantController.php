<?php

/**
 * Variation Grant Controller
 *
 * @author Dan Eggleston <dan@stolenegg.com>
 */
namespace Olcs\Controller\Lva\Variation;

use Olcs\Controller\Lva\AbstractGrantController;
use Olcs\Controller\Lva\Traits\VariationControllerTrait;
use Olcs\Controller\Interfaces\ApplicationControllerInterface;

/**
 * Variation Grant Controller
 *
 * @author Dan Eggleston <dan@stolenegg.com>
 */
class GrantController extends AbstractGrantController implements ApplicationControllerInterface
{
    use VariationControllerTrait;

    protected $lva = 'variation';
    protected $location = 'internal';

    /**
     * Check that the variation can be granted
     *
     * @param int $applicationId
     * @return array Array of error messages, empty if no validation errors
     */
    protected function validateGrantConditions($applicationId)
    {
        $errors = [];
        $applicationProcessingService = $this->getServiceLocator()->get('Processing\Application');
        $variationProcessingService = $this->getServiceLocator()->get('Processing\VariationSection');
        $variationProcessingService->setApplicationId($applicationId);

        // check tracking status
        $required = $this->getAccessibleSections();
        if (!$applicationProcessingService->trackingIsValid($applicationId, $required)) {
            $errors[] = 'application-grant-error-tracking';
        }

        // check section completion
        if (!$variationProcessingService->hasChanged()) {
            $errors[] = 'variation-grant-error-no-change';
        } else {
            $incompleteSections = $variationProcessingService->getSectionsRequiringAttention($applicationId);
            if (!empty($incompleteSections)) {
                $translator = $this->getServiceLocator()->get('Helper\Translation');
                $sections = array_map(
                    function ($section) use ($translator) {
                        return $translator->translate('lva.section.title.'.$section);
                    },
                    $incompleteSections
                );
                $errors[] = $translator->translateReplace(
                    'variation-grant-error-sections',
                    [implode(', ', $sections)]
                );
            }
        }

        // check fee status
        if (!$applicationProcessingService->feeStatusIsValid($applicationId)) {
             $errors[] = 'application-grant-error-fees';
        }

        return $errors;
    }
}
