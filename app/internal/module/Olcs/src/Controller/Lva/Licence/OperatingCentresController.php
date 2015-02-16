<?php

/**
 * Internal Licencing Operating Centres Controller
 *
 * @author Nick Payne <nick.payne@valtech.co.uk>
 * @author Rob Caiger <rob@clocal.co.uk>
 */
namespace Olcs\Controller\Lva\Licence;

use Olcs\Controller\Interfaces\LicenceControllerInterface;
use Zend\View\Model\ViewModel;
use Common\Controller\Lva;
use Olcs\Controller\Lva\Traits\LicenceControllerTrait;

/**
 * Internal Licencing Operating Centres Controller
 *
 * @author Nick Payne <nick.payne@valtech.co.uk>
 * @author Rob Caiger <rob@clocal.co.uk>
 */
class OperatingCentresController extends Lva\AbstractOperatingCentresController implements
    LicenceControllerInterface
{
    use LicenceControllerTrait,
        Lva\Traits\LicenceOperatingCentresControllerTrait;

    protected $lva = 'licence';
    protected $location = 'internal';

    /**
     * Override add action to show variation warning
     */
    public function addAction()
    {
        $view = new ViewModel(
            array(
                'licence' => $this->getIdentifier()
            )
        );
        $view->setTemplate('licence/add-authorisation');

        return $this->render($view);
    }
}
