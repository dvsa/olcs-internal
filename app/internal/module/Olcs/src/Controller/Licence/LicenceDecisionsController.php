<?php

/**
 * LicenceDecisionsController.php
 */
namespace Olcs\Controller\Licence;

use Common\Service\Entity\LicenceStatusRuleEntityService;

use Olcs\Controller\AbstractController;
use Olcs\Controller\Traits\LicenceControllerTrait;

use Dvsa\Olcs\Transfer\Query\Licence\LicenceDecisions;
use Dvsa\Olcs\Transfer\Query\LicenceStatusRule\LicenceStatusRule;

use Dvsa\Olcs\Transfer\Command\LicenceStatusRule\CreateLicenceStatusRule;
use Dvsa\Olcs\Transfer\Command\LicenceStatusRule\UpdateLicenceStatusRule;
use Dvsa\Olcs\Transfer\Command\LicenceStatusRule\DeleteLicenceStatusRule;
use Dvsa\Olcs\Transfer\Command\Licence\RevokeLicence;
use Dvsa\Olcs\Transfer\Command\Licence\CurtailLicence;
use Dvsa\Olcs\Transfer\Command\Licence\SuspendLicence;
use Dvsa\Olcs\Transfer\Command\Licence\SurrenderLicence;
use Dvsa\Olcs\Transfer\Command\Licence\ResetToValid;

/**
 * Class LicenceDecisionsController
 *
 * Calling code for logic around actions directly against the licence. E.g.
 * suspending or revoking the licence for a specified amount of time.
 *
 * @package Olcs\Controller\Licence
 */
class LicenceDecisionsController extends AbstractController
{
    use LicenceControllerTrait;

    /**
     * Display messages and enable to user to carry on to a decision if applicable.
     *
     * @return string|\Zend\Http\Response|\Zend\View\Model\ViewModel
     */
    public function activeLicenceCheckAction()
    {
        $decision = $this->fromRoute('decision', null);
        $licence = $this->fromRoute('licence', null);

        $formHelper = $this->getServiceLocator()->get('Helper\Form');

        $form = $formHelper->createFormWithRequest('LicenceStatusDecisionMessages', $this->getRequest());

        $query = LicenceDecisions::create(
            [
                'id' => $licence
            ]
        );

        $response = $this->handleQuery($query);
        $result = $response->getResult();

        $pageTitle = ucfirst($decision) ." licence";
        switch ($decision) {
            case 'terminate':
            case 'suspend':
            case 'curtail':
                if ($this->getRequest()->isPost() || $result['suitableForDecisions'] === true) {
                    return $this->redirectToDecision($decision, $licence);
                }
                break;
            case 'surrender':
            case 'revoke':
                if ($result['suitableForDecisions'] === true) {
                    return $this->redirectToDecision($decision, $licence);
                }
                $form->get('form-actions')->remove('continue');
                break;
        }

        $messages = array();
        foreach ($result['suitableForDecisions'] as $key => $value) {
            if (!$value) {
                continue;
            }

            switch ($key) {
                case 'activeComLics':
                    $messages[$key] = 'There are active, pending or suspended community licences';
                    break;
                case 'activeBusRoutes':
                    $messages[$key] = 'There are active bus routes on this licence';
                    break;
                case 'activeVariations':
                    $messages[$key] = 'There are applications still under consideration';
                    break;
            }
        }

        $form->get('messages')->get('message')->setValue(implode('<br>', $messages));

        $view = $this->getViewWithLicence(
            array(
                'form' => $form
            )
        );

        $view->setTemplate('partials/form');

        return $this->renderView($view, $pageTitle);
    }

    /**
     * Curtail a licence.
     *
     * @return string|\Zend\Http\Response|\Zend\View\Model\ViewModel
     */
    public function curtailAction()
    {
        $licenceId = $this->fromRoute('licence');
        $licenceStatus = $this->fromRoute('status', null);
        if (!is_null($licenceStatus)) {
            if ($this->isButtonPressed('remove')) {
                return $this->removeLicenceStatusRule(
                    $licenceId,
                    $licenceStatus,
                    'licence-status.curtailment.message.remove.success'
                );
            }

            $licenceStatus = $this->getStatusForLicenceById($licenceStatus);
        }

        if ($this->isButtonPressed('affectImmediate')) {
            return $this->affectImmediate(
                $licenceId,
                CurtailLicence::class,
                'licence-status.curtailment.message.save.success'
            );
        }

        $form = $this->getDecisionForm(
            'LicenceStatusDecisionCurtail',
            $licenceStatus,
            array(
                'curtailFrom' => 'startDate',
                'curtailTo' => 'endDate'
            )
        );

        if ($this->getRequest()->isPost()) {
            $form->setData((array)$this->getRequest()->getPost());

            if ($form->isValid()) {
                $formData = $form->getData();
                $response = $this->saveDecisionForLicence(
                    $licenceId,
                    array(
                        'status' => LicenceStatusRuleEntityService::LICENCE_STATUS_RULE_CURTAILED,
                        'startDate' => $formData['licence-decision']['curtailFrom'],
                        'endDate' => $formData['licence-decision']['curtailTo'],
                    ),
                    $licenceStatus
                );

                if ($response->isOk()) {
                    $this->flashMessenger()->addSuccessMessage('licence-status.curtailment.message.save.success');
                    return $this->redirectToRouteAjax('licence', array('licence' => $licenceId));
                }
            }
        }

        return $this->renderDecisionView($form, 'Curtail licence');
    }

    /**
     * Revoke a licence.
     *
     * @return string|\Zend\Http\Response|\Zend\View\Model\ViewModel
     */
    public function revokeAction()
    {
        $licenceId = $this->fromRoute('licence');
        $licenceStatus = $this->fromRoute('status', null);
        if (!is_null($licenceStatus)) {
            if ($this->isButtonPressed('remove')) {
                return $this->removeLicenceStatusRule(
                    $licenceId,
                    $licenceStatus,
                    'licence-status.revocation.message.remove.success'
                );
            }

            $licenceStatus = $this->getStatusForLicenceById($licenceStatus);
        }

        if ($this->isButtonPressed('affectImmediate')) {
            return $this->affectImmediate(
                $licenceId,
                RevokeLicence::class,
                'licence-status.revocation.message.save.success'
            );
        }

        $form = $this->getDecisionForm(
            'LicenceStatusDecisionRevoke',
            $licenceStatus,
            array(
                'revokeFrom' => 'startDate'
            )
        );

        if ($this->getRequest()->isPost()) {
            $form->setData((array)$this->getRequest()->getPost());

            if ($form->isValid()) {
                $formData = $form->getData();

                $response = $this->saveDecisionForLicence(
                    $licenceId,
                    array(
                        'status' => LicenceStatusRuleEntityService::LICENCE_STATUS_RULE_REVOKED,
                        'startDate' => $formData['licence-decision']['revokeFrom'],
                    ),
                    $licenceStatus
                );

                if ($response->isOk()) {
                    $this->flashMessenger()->addSuccessMessage('licence-status.revocation.message.save.success');
                    return $this->redirectToRouteAjax('licence', array('licence' => $licenceId));
                }
            }
        }

        return $this->renderDecisionView($form, 'Revoke licence');
    }

    /**
     * Suspend a licence.
     *
     * @return string|\Zend\Http\Response|\Zend\View\Model\ViewModel
     */
    public function suspendAction()
    {
        $licenceId = $this->fromRoute('licence');
        $licenceStatus = $this->fromRoute('status', null);
        if (!is_null($licenceStatus)) {
            if ($this->isButtonPressed('remove')) {
                return $this->removeLicenceStatusRule(
                    $licenceId,
                    $licenceStatus,
                    'licence-status.suspension.message.remove.success'
                );
            }

            $licenceStatus = $this->getStatusForLicenceById($licenceStatus);
        }

        if ($this->isButtonPressed('affectImmediate')) {
            return $this->affectImmediate(
                $licenceId,
                SuspendLicence::class,
                'licence-status.suspension.message.save.success'
            );
        }

        $form = $this->getDecisionForm(
            'LicenceStatusDecisionSuspend',
            $licenceStatus,
            array(
                'suspendFrom' => 'startDate',
                'suspendTo' => 'endDate'
            )
        );

        if ($this->getRequest()->isPost()) {
            $form->setData((array)$this->getRequest()->getPost());

            if ($form->isValid()) {
                $formData = $form->getData();
                $response = $this->saveDecisionForLicence(
                    $licenceId,
                    array(
                        'status' => LicenceStatusRuleEntityService::LICENCE_STATUS_RULE_SUSPENDED,
                        'startDate' => $formData['licence-decision']['suspendFrom'],
                        'endDate' => $formData['licence-decision']['suspendTo']
                    ),
                    $licenceStatus
                );

                if ($response->isOk()) {
                    $this->flashMessenger()->addSuccessMessage('licence-status.suspension.message.save.success');
                    return $this->redirectToRouteAjax('licence', array('licence' => $licenceId));
                }
            }
        }

        return $this->renderDecisionView($form, 'Suspend licence');
    }

    /**
     * Reset the licence back to a valid state.
     *
     * @return string|\Zend\View\Model\ViewModel
     */
    public function resetToValidAction()
    {
        $pageTitle = $this->params('title') ?: 'licence-status.reset.title';

        $licenceId = $this->fromRoute('licence');

        $form = $this->getDecisionForm('GenericConfirmation');
        $form->get('messages')
            ->get('message')
            ->setValue('licence-status.reset.message');
        $form->get('form-actions')
            ->get('submit')
            ->setLabel('licence-status.reset.title');

        if ($this->getRequest()->isPost()) {
            $form->setData((array)$this->getRequest()->getPost());

            if ($form->isValid()) {
                $response = $this->handleCommand(
                    ResetToValid::create(
                        [
                            'id' => $licenceId
                        ]
                    )
                );

                if ($response->isOk()) {
                    $this->flashMessenger()->addSuccessMessage('licence-status.reset.message.save.success');
                    return $this->redirectToRouteAjax('licence', array('licence' => $licenceId));
                }
            }
        }

        return $this->renderView(
            $this->getView(
                array(
                    'form' => $form,
                )
            )->setTemplate('partials/form'),
            $pageTitle
        );
    }

    /**
     * Surrender a licence.
     *
     * @return string|\Zend\Http\Response|\Zend\View\Model\ViewModel
     */
    public function surrenderAction()
    {
        $licenceId = $this->fromRoute('licence');

        $form = $this->getDecisionForm('LicenceStatusDecisionSurrender');

        if ($this->getRequest()->isPost()) {
            $form->setData((array)$this->getRequest()->getPost());

            if ($form->isValid()) {
                $formData = $form->getData();

                $command = SurrenderLicence::create(
                    [
                        'id' => $licenceId,
                        'surrenderDate' => $formData['licence-decision']['surrenderDate']
                    ]
                );

                $response = $this->handleCommand($command);

                if ($response->isOk()) {
                    $this->flashMessenger()->addSuccessMessage('licence-status.surrender.message.save.success');
                    return $this->redirectToRouteAjax('licence', array('licence' => $licenceId));
                }
            }
        }

        $this->getServiceLocator()->get('Helper\Form')->setDefaultDate(
            $form->get('licence-decision')->get('surrenderDate')
        );

        return $this->renderDecisionView($form, 'Surrender licence');
    }

    /**
     * Terminate a licence.
     *
     * @return string|\Zend\Http\Response|\Zend\View\Model\ViewModel
     */
    public function terminateAction()
    {
        $licenceId = $this->fromRoute('licence');

        $form = $this->getDecisionForm('LicenceStatusDecisionTerminate');

        if ($this->getRequest()->isPost()) {
            $form->setData((array)$this->getRequest()->getPost());

            if ($form->isValid()) {
                $formData = $form->getData();

                $command = SurrenderLicence::create(
                    [
                        'id' => $licenceId,
                        'surrenderDate' => $formData['licence-decision']['terminateDate'],
                        'terminated' => true
                    ]
                );

                $response = $this->handleCommand($command);

                if ($response->isOk()) {
                    $this->flashMessenger()->addSuccessMessage('licence-status.terminate.message.save.success');
                    return $this->redirectToRouteAjax('licence', array('licence' => $licenceId));
                }
            }
        }

        $this->getServiceLocator()->get('Helper\Form')->setDefaultDate(
            $form->get('licence-decision')->get('terminateDate')
        );

        return $this->renderDecisionView($form, 'Terminate licence');
    }

    /**
     * If a xNow e.g. curtailNow method has been pressed then redirect.
     *
     * @param null|int $licenceId The licence id.
     * @param null|string $command The command to use.
     * @param null|string $message The message to display
     *
     * @return \Zend\Http\Response A redirection response.
     */
    private function affectImmediate($licenceId = null, $command = null, $message = null)
    {
        $command = $command::create(
            [
                'id' => $licenceId
            ]
        );

        $response = $this->handleCommand($command);

        if ($response->isOk()) {
            $this->flashMessenger()->addSuccessMessage($message);

            return $this->redirectToRouteAjax(
                'licence',
                array(
                    'licence' => $licenceId
                )
            );
        }
    }

    /**
     * Get the decision form.
     *
     * @param null|string $name The form name to try and get.
     * @param null|array $status Licence status rule.
     * @param null|array $keys Keys to map.
     *
     * @return mixed The form.
     */
    private function getDecisionForm($name = null, $status = null, array $keys = array())
    {
        $formHelper = $this->getServiceLocator()->get('Helper\Form');
        $form = $formHelper->createFormWithRequest($name, $this->getRequest());

        if (!is_null($status)) {
            return $form->setData(
                $this->formatDataForFormUpdate(
                    array_map(
                        function ($key) use ($status) {
                            return $status[$key];
                        },
                        $keys
                    )
                )
            );
        }

        $form->get('form-actions')->remove('remove');

        return $form;
    }

    /**
     * Save/update a decision against a licence.
     *
     * @param null|int $licenceId The licence id.
     * @param array $data The data to save.
     * @param array|null $statusRule The licence status record.
     */
    private function saveDecisionForLicence($licenceId = null, array $data = array(), $statusRule = null)
    {
        $data['licence'] = $licenceId;

        if (!is_null($statusRule)) {
            $command = new UpdateLicenceStatusRule();
            $command->exchangeArray(
                array_merge(
                    $data,
                    array(
                        'id' => $statusRule['id'],
                        'version' => $statusRule['version']
                    )
                )
            );

            return $this->handleCommand($command);
        }

        $command = CreateLicenceStatusRule::create($data);

        return $this->handleCommand($command);
    }

    /**
     * Render the view with the form.
     *
     * @param null|\Common\Form\Form The form to render.
     * @param bool Whether tp load the script files.
     *
     * @return string|\Zend\View\Model\ViewModel
     */
    private function renderDecisionView($form = null, $pageTitle = null)
    {
        $view = $this->getViewWithLicence(
            array(
                'form' => $form
            )
        );

        $this->getServiceLocator()->get('Script')->loadFiles(['forms/licence-decision']);

        $view->setTemplate('partials/form');

        return $this->renderView($view, $pageTitle);
    }

    /**
     * Redirect the request to a specific decision.
     *
     * @param null|string $decision The decision.
     * @param null|int $licence The licence id.
     *
     * @return \Zend\Http\Response The redirection
     */
    private function redirectToDecision($decision = null, $licence = null)
    {
        return $this->redirectToRoute(
            'licence/' . $decision . '-licence',
            array(
                'licence' => $licence
            )
        );
    }

    /**
     * Get a licence status.
     *
     * @param int $id The licence status id.
     *
     * @return array $validFormData The licence status data for the form.
     */
    private function getStatusForLicenceById($id)
    {
        $query = LicenceStatusRule::create(
            [
                'id' => $id
            ]
        );

        $response = $this->handleQuery($query);
        if (!$response->isOk()) {
            if ($response->isClientError() || $response->isServerError()) {
                $this->getServiceLocator()->get('Helper\FlashMessenger')->addErrorMessage('unknown-error');
            }

            return $this->notFoundAction();
        }

        return $response->getResult();
    }

    /**
     * Remove the licence status rule record.
     *
     * @param $licence The licence id.
     * @param $licenceStatusId The licence status id.
     * @param $message The message to display.
     *
     * @return mixed
     */
    private function removeLicenceStatusRule($licence, $licenceStatusId, $message)
    {
        $command = DeleteLicenceStatusRule::create(
            [
                'id' => $licenceStatusId
            ]
        );

        $response = $this->handleCommand($command);
        if ($response->isOk()) {
            $this->flashMessenger()->addSuccessMessage($message);

            return $this->redirectToRouteAjax(
                'licence',
                array(
                    'licence' => $licence
                )
            );
        }

        return false;
    }

    /**
     * Return an array that can be set on the form.
     *
     * @param array $licenceDecision The licence decision data.
     *
     * @return array The formatted data
     */
    private function formatDataForFormUpdate(array $licenceDecision = array())
    {
        return array(
            'licence-decision-affect-immediate' => array(
                'immediateAffect' => 'N',
            ),
            'licence-decision' => $licenceDecision
        );
    }
}
