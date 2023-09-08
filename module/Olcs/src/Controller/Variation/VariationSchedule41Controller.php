<?php

namespace Olcs\Controller\Variation;

use Olcs\Controller\Interfaces\ApplicationControllerInterface;
use Olcs\Controller\Lva\Traits\VariationControllerTrait;

use Common\Controller\Lva\Schedule41Controller;

use Common\Controller\Lva\AbstractController;
use Common\Controller\Plugin\Redirect;

class VariationSchedule41Controller extends Schedule41Controller implements ApplicationControllerInterface
{
    use VariationControllerTrait;

    protected $lva = 'variation';
    protected $location = 'internal';

    protected $section = 'operating_centres';
}
