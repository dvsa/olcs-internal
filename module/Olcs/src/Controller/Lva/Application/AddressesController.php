<?php

namespace Olcs\Controller\Lva\Application;

use Olcs\Controller\Interfaces\ApplicationControllerInterface;
use Common\Controller\Lva\AbstractAddressesController;
use Olcs\Controller\Lva\Traits\ApplicationControllerTrait;

class AddressesController extends AbstractAddressesController implements ApplicationControllerInterface
{
    use ApplicationControllerTrait;

    protected $lva = 'application';
    protected $location = 'internal';
}
