<?php

/**
 * Internal Variation Conditions Undertakings Controller
 *
 * @author Rob Caiger <rob@clocal.co.uk>
 */
namespace Olcs\Controller\Lva\Variation;

use Common\Controller\Lva;
use Olcs\Controller\Lva\Traits\VariationControllerTrait;
use Olcs\Controller\Interfaces\ApplicationControllerInterface;

/**
 * Internal Variation Conditions Undertakings Controller
 *
 * @author Rob Caiger <rob@clocal.co.uk>
 */
class ConditionsUndertakingsController extends Lva\AbstractConditionsUndertakingsController implements
    ApplicationControllerInterface
{
    use VariationControllerTrait;

    protected $lva = 'variation';
    protected $location = 'internal';

    /**
     * @NOTE At the moment this method can only be called from variation
     *
     * @return ViewModel
     */
    public function restoreAction()
    {
        $id = $this->params('child_id');

        $ids = explode(',', $id);

        $hasRestored = false;

        foreach ($ids as $id) {

            $response = $this->getAdapter()->restore($id, $this->getIdentifier());

            if ($response) {
                $hasRestored = $response;
            }
        }

        $flashMessenger = $this->getServiceLocator()->get('Helper\FlashMessenger');

        if ($hasRestored) {
            $flashMessenger->addSuccessMessage('generic-restore-success');
        } else {
            $flashMessenger->addInfoMessage('generic-nothing-updated');
        }

        return $this->redirect()->toRouteAjax(
            null,
            array($this->getIdentifierIndex() => $this->getIdentifier())
        );
    }

    /**
     * {@inheritDoc}
     *
     * @return array
     */
    protected function getRenderVariables()
    {
        return array('title' => null);
    }
}
