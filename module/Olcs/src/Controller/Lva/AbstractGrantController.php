<?php

/**
 * Abstract Internal Grant Controller
 *
 * @author Dan Eggleston <dan@stolenegg.com>
 */
namespace Olcs\Controller\Lva;

use Common\Controller\Lva\AbstractController;
use Dvsa\Olcs\Transfer\Query\Application\Grant;
use Zend\Form\Form;
use Zend\View\Model\ViewModel;
use Dvsa\Olcs\Transfer\Command\Application\Grant as AppGrantCmd;
use Dvsa\Olcs\Transfer\Command\Variation\Grant as VarGrantCmd;

/**
 * Abstract Internal Grant Controller
 *
 * @author Dan Eggleston <dan@stolenegg.com>
 */
abstract class AbstractGrantController extends AbstractController
{
    protected $lva;
    protected $location;
    protected $grantCommandMap = [
        'application' => AppGrantCmd::class,
        'variation' => VarGrantCmd::class
    ];

    public function grantAction()
    {
        $id = $this->params('application');

        if ($this->isButtonPressed('cancel') || $this->isButtonPressed('overview')) {
            $this->getServiceLocator()->get('Helper\FlashMessenger')->addWarningMessage('application-not-granted');
            return $this->redirectToOverview($id);
        }

        $formHelper = $this->getServiceLocator()->get('Helper\Form');
        $form = $formHelper->createFormWithRequest('Grant', $this->getRequest());

        $result = $this->handleQuery(Grant::create(['id' => $id]))->getResult();

        if (!$result['canHaveInspectionRequest']) {
            $formHelper->remove($form, 'inspection-request-details');
            $formHelper->remove($form, 'inspection-request-confirm');

            if ($result['canGrant']) {
                $form->get('messages')->get('message')->setValue('confirm-grant-application');
            }
        }

        if (!$result['canGrant']) {
            $formHelper->remove($form, 'form-actions->grant');
            $this->addMessages($form, $result['reasons']);
            return $this->renderForm($form);
        }

        if (!$this->getRequest()->isPost()) {
            return $this->renderForm($form);
        }

        $postData = (array)$this->getRequest()->getPost();

        $form->setData($postData);

        $dtoClass = $this->grantCommandMap[$this->lva];
        $dtoData = ['id' => $id];

        if (isset($postData['inspection-request-confirm']['createInspectionRequest'])) {
            $value = $postData['inspection-request-confirm']['createInspectionRequest'];
            $dtoData['shouldCreateInspectionRequest'] = $value == 'Y' ? 'Y' : 'N';
        }

        if (isset($postData['inspection-request-grant-details']['dueDate'])) {
            $dtoData['dueDate'] = $postData['inspection-request-grant-details']['dueDate'];
        }

        if (isset($postData['inspection-request-grant-details']['caseworkerNotes'])) {
            $dtoData['notes'] = $postData['inspection-request-grant-details']['caseworkerNotes'];
        }

        $response = $this->handleCommand($dtoClass::create($dtoData));

        if ($response->isOk()) {
            $this->getServiceLocator()->get('Helper\FlashMessenger')
                ->addSuccessMessage('application-granted-successfully');

            return $this->redirectToOverview($id);
        }

        if ($response->isClientError()) {
            $this->mapErrors($form, $response->getResult()['messages']);
            return $this->renderForm($form);
        }

        $this->getServiceLocator()->get('Helper\FlashMessenger')->addCurrentErrorMessage('unknown-error');

        return $this->renderForm($form);
    }

    protected function mapErrors(Form $form, array $errors)
    {
        $formMessages = [];

        if (isset($errors['shouldCreateInspectionRequest'])) {

            foreach ($errors['shouldCreateInspectionRequest'] as $key => $message) {
                $formMessages['inspection-request-confirm']['createInspectionRequest'][] = $message;
            }

            unset($errors['shouldCreateInspectionRequest']);
        }

        if (isset($errors['dueDate'])) {

            foreach ($errors['dueDate'][0] as $key => $message) {
                $formMessages['inspection-request-grant-details']['dueDate'][] = $message;
            }

            unset($errors['dueDate']);
        }

        if (!empty($errors)) {
            $fm = $this->getServiceLocator()->get('Helper\FlashMessenger');

            foreach ($errors as $error) {
                $fm->addCurrentErrorMessage($error);
            }
        }

        $form->setMessages($formMessages);
    }

    protected function renderForm($form)
    {
        $id = $this->params('application');

        $formHelper = $this->getServiceLocator()->get('Helper\Form');

        $message = $form->get('messages')->get('message')->getValue();

        if (empty($message)) {
            $formHelper->remove($form, 'messages');
        }

        $formHelper->remove($form, 'form-actions->overview');

        $this->getServiceLocator()->get('Script')->loadFiles(['forms/confirm-grant']);

        if ($this->getRequest()->isXmlHttpRequest()) {
            return $this->renderModalWithForm($form, 'internal.inspection-request.form.title.grant');
        }

        return $this->render(
            'grant_application',
            $form,
            [
                'route' => 'lva-'.$this->lva,
                'routeParams' => ['application' => $id],
            ]
        );
    }

    /**
     * Add feedback messages as to why validation failed
     *
     * @todo improve the appearance of these messages
     *
     * @param \Common\Form\Form $form
     * @param array $reasons
     */
    protected function addMessages($form, $reasons)
    {
        $messages = [];

        $translator = $this->getServiceLocator()->get('Helper\Translation');

        foreach ($reasons as $reason => $info) {
            if (in_array($reason, ['application-grant-error-sections', 'variation-grant-error-sections'])) {

                $sections = [];
                foreach ($info as $section) {
                    $sections[] = $translator->translate('lva.section.title.' . $section);
                }

                $messages[] = $translator->translateReplace($reason, [implode(', ', $sections)]);
            } else {
                $messages[] = $translator->translate($reason);
            }
        }

        $form->get('messages')->get('message')->setValue(implode('<br>', $messages));
    }

    /**
     * Check for redirect
     *
     * @param int $lvaId
     * @return null|\Zend\Http\Response
     */
    protected function checkForRedirect($lvaId)
    {
        // no-op to avoid LVA predispatch magic kicking in
    }

    /**
     * Render modal window with form
     *
     * @param \Common\Form\Form $form
     * @param string $title
     * @return \Common\Form\Form
     */
    protected function renderModalWithForm($form, $title)
    {
        $view = new ViewModel(['form' => $form]);
        $view->setTemplate('partials/form');

        $layout = new ViewModel(['pageTitle' => $title]);
        $layout->setTemplate('layout/ajax')->setTerminal(true)->addChild($view, 'content');

        return $layout;
    }

    /**
     * Redirect to overview
     *
     * @return \Zend\Http\Response
     */
    protected function redirectToOverview($id)
    {
        return $this->redirect()->toRouteAjax('lva-' . $this->lva, ['application' => $id]);
    }
}
