<?php

/**
 * Internal Application Vehicles PSV Controller
 *
 * @author Nick Payne <nick.payne@valtech.co.uk>
 */
namespace Olcs\Controller\Lva\Application;

use Olcs\Controller\Interfaces\ApplicationControllerInterface;
use Common\Controller\Lva\AbstractVehiclesPsvController;
use Common\Controller\Lva\Traits;
use Olcs\Controller\Lva\Traits\ApplicationControllerTrait;

/**
 * Internal Application Vehicles PSV Controller
 *
 * @author Nick Payne <nick.payne@valtech.co.uk>
 */
class VehiclesPsvController extends AbstractVehiclesPsvController implements ApplicationControllerInterface
{
    use ApplicationControllerTrait,
        Traits\PsvApplicationControllerTrait,
        Traits\ApplicationGenericVehiclesControllerTrait;

    protected $lva = 'application';
    protected $location = 'internal';
}
