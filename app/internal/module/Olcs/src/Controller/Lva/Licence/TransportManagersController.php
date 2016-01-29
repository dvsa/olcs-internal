<?php

/**
 * Internal Licence Transport Managers Controller
 *
 * @author Nick Payne <nick.payne@valtech.co.uk>
 * @author Rob Caiger <rob@clocal.co.uk>
 */
namespace Olcs\Controller\Lva\Licence;

use Common\Controller\Lva;
use Olcs\Controller\Interfaces\LicenceControllerInterface;
use Olcs\Controller\Lva\Traits\LicenceControllerTrait;
use Olcs\Controller\Interfaces\TransportManagerControllerInterface;

/**
 * Internal Licence Transport Managers Controller
 *
 * @author Nick Payne <nick.payne@valtech.co.uk>
 * @author Rob Caiger <rob@clocal.co.uk>
 */
class TransportManagersController extends Lva\AbstractTransportManagersController implements
    LicenceControllerInterface,
    TransportManagerControllerInterface
{
    use LicenceControllerTrait;

    protected $lva = 'licence';
    protected $location = 'internal';
}
