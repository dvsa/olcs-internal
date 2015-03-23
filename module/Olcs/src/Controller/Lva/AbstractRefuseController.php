<?php

/**
 * Abstract Internal Refuse Controller
 *
 * @author Dan Eggleston <dan@stolenegg.com>
 */
namespace Olcs\Controller\Lva;

use Olcs\Controller\Lva\AbstractApplicationDecisionController;

/**
 * Abstract Internal Refuse Controller
 *
 * @author Dan Eggleston <dan@stolenegg.com>
 */
abstract class AbstractRefuseController extends AbstractApplicationDecisionController
{
    protected $cancelMessageKey  =  'application-not-refused';
    protected $successMessageKey =  'application-refused-successfully';
    protected $titleKey          =  'internal-application-refuse-title';

    protected function getForm()
    {
        $request  = $this->getRequest();
        $formHelper = $this->getServiceLocator()->get('Helper\Form');
        $form = $formHelper->createFormWithRequest('GenericConfirmation', $request);

        // override default label on confirm action button
        $form->get('messages')->get('message')->setValue('internal-application-refuse-confirm');

        return $form;
    }

    protected function processDecision($id, $data)
    {
        $this->getServiceLocator()->get('Processing\Application')->processRefuseApplication($id);
    }
}
