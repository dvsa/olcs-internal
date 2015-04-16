<?php

/**
 * Internal Variation Addresses Controller
 *
 * @author Nick Payne <nick.payne@valtech.co.uk>
 * @author Rob Caiger <rob@clocal.co.uk>
 */
namespace Olcs\Controller\Lva\Variation;

use Common\Controller\Lva;
use Olcs\Controller\Lva\Traits\VariationControllerTrait;
use Olcs\Controller\Interfaces\ApplicationControllerInterface;

/**
 * Internal Variation Addresses Controller
 *
 * @author Nick Payne <nick.payne@valtech.co.uk>
 * @author Rob Caiger <rob@clocal.co.uk>
 */
class AddressesController extends Lva\AbstractAddressesController implements ApplicationControllerInterface
{
    use VariationControllerTrait;

    protected $lva = 'variation';
    protected $location = 'internal';

    protected function postChange(array $data)
    {
        if (isset($data['hasChanged']) && $data['hasChanged']) {
            $this->postSave('addresses');
        }
    }
}
