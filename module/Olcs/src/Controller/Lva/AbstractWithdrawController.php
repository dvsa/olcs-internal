<?php

namespace Olcs\Controller\Lva;

use Dvsa\Olcs\Transfer\Command\Application\WithdrawApplication;

/**
 * Abstract Internal Withdraw Controller
 *
 * @author Dan Eggleston <dan@stolenegg.com>
 */
abstract class AbstractWithdrawController extends AbstractApplicationDecisionController
{
    protected $cancelMessageKey  =  'application-not-withdrawn';
    protected $successMessageKey =  'application-withdrawn-successfully';
    protected $titleKey          =  'internal-application-withdraw-title';

    /**
     * get from
     *
     * @return \Common\Form\Form
     */
    protected function getForm()
    {
        $request  = $this->getRequest();
        $formHelper = $this->getServiceLocator()->get('Helper\Form');
        $form = $formHelper->createFormWithRequest('Withdraw', $request);

        // override default label on confirm action button
        $form->get('form-actions')->get('confirm')->setLabel('Confirm');

        return $form;
    }

    /**
     * process decision
     *
     * @param int   $id   id
     * @param array $data data
     *
     * @return \Common\Service\Cqrs\Response
     */
    protected function processDecision($id, $data)
    {
        return $this->handleCommand(
            WithdrawApplication::create(
                [
                    'id' => $id,
                    'reason' => $data['withdraw-details']['reason'],
                ]
            )
        );
    }
}
