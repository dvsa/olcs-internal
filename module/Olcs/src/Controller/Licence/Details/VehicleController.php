<?php

/**
 * Vehicle Controller
 *
 * Internal - Licence - Vehicle section
 */
namespace Olcs\Controller\Licence\Details;

use Common\Controller\Traits\VehicleSafety as VehicleSafetyTraits;

/**
 * Vehicle Controller
 */
class VehicleController extends AbstractLicenceDetailsController
{
    use VehicleSafetyTraits\VehicleSection,
        VehicleSafetyTraits\InternalGenericVehicleSection,
        VehicleSafetyTraits\LicenceGenericVehicleSection;

    /**
     * Set the form name
     *
     * @var string
     */
    protected $formName = 'application_vehicle-safety_vehicle';

    /**
     * Holds the table name
     *
     * @var string
     */
    protected $tableName = 'application_vehicle-safety_vehicle';

    /**
     * Setup the section
     *
     * @var string
     */
    protected $section = 'vehicle';

    /**
     * Save the vehicle
     *
     * @todo might be able to combine these 2 methods now
     *
     * @param array $data
     * @param string $service
     */
    protected function actionSave($data, $service = null)
    {
        $action = $this->getActionName();

        return $this->internalActionSave($data, $action);
    }
}
