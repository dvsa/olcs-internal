<?php

/**
 * Internal Variation Vehicles PSV Controller
 *
 * @author Nick Payne <nick.payne@valtech.co.uk>
 */
namespace Olcs\Controller\Lva\Variation;

use Common\Controller\Lva\AbstractVehiclesPsvController;
use Common\Controller\Lva\Traits;
use Olcs\Controller\Lva\Traits\VariationControllerTrait;
use Olcs\Controller\Interfaces\ApplicationControllerInterface;

/**
 * Internal Variation Vehicles PSV Controller
 *
 * @author Nick Payne <nick.payne@valtech.co.uk>
 */
class VehiclesPsvController extends AbstractVehiclesPsvController implements ApplicationControllerInterface
{
    use VariationControllerTrait,
        Traits\PsvVariationControllerTrait,
        // @NOTE this at the moment just sets the application id of the licence vehicle
        Traits\ApplicationGenericVehiclesControllerTrait;

    protected $lva = 'variation';
    protected $location = 'internal';
}
