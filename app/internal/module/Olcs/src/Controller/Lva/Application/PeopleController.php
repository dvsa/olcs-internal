<?php

/**
 * Internal Application People Controller
 *
 * @author Nick Payne <nick.payne@valtech.co.uk>
 * @author Rob Caiger <rob@clocal.co.uk>
 */
namespace Olcs\Controller\Lva\Application;

use Olcs\Controller\Interfaces\ApplicationControllerInterface;
use Common\Controller\Lva;
use Olcs\Controller\Lva\Traits\ApplicationControllerTrait;

/**
 * Internal Application People Controller
 *
 * @author Nick Payne <nick.payne@valtech.co.uk>
 * @author Rob Caiger <rob@clocal.co.uk>
 */
class PeopleController extends Lva\AbstractPeopleController implements ApplicationControllerInterface
{
    use ApplicationControllerTrait;

    protected $lva = 'application';
    protected $location = 'internal';

    public function disqualifyAction()
    {
        return $this->forward()->dispatch(
            \Olcs\Controller\DisqualifyController::class,
            [
                'action' => 'index',
                'application' => $this->params()->fromRoute('application'),
                'person' => $this->params()->fromRoute('child_id')
            ]
        );
    }
}
