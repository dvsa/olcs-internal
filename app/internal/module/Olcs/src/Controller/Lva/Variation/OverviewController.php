<?php

/**
 * Variation Overview Controller
 *
 * @author Rob Caiger <rob@clocal.co.uk>
 */
namespace Olcs\Controller\Lva\Variation;

use Common\Controller\Lva\AbstractController;
use Olcs\Controller\Lva\Traits\VariationControllerTrait;
use Olcs\Controller\Lva\Traits\ApplicationOverviewTrait;
use Olcs\Controller\Interfaces\ApplicationControllerInterface;

/**
 * Application Overview Controller
 *
 * @author Rob Caiger <rob@clocal.co.uk>
 */
class OverviewController extends AbstractController implements ApplicationControllerInterface
{
    use VariationControllerTrait,
        ApplicationOverviewTrait;

    protected $lva = 'variation';
    protected $location = 'internal';
}
