<?php

/**
 * Document Relink Controller
 *
 * @author Alex Peshkov <alex.peshkov@valtech.co.uk>
 */
namespace Olcs\Controller\Document;

use Zend\View\Model\ViewModel;
use Zend\Http\Response;
use Dvsa\Olcs\Transfer\Command\Document\CopyDocument;
use Dvsa\Olcs\Transfer\Command\Document\MoveDocument;
use Olcs\Data\Mapper\DocumentRelink as DocumentRelinkMapper;

/**
 * Document Relink Controller
 *
 * @author Alex Peshkov <alex.peshkov@valtech.co.uk>
 */
class DocumentRelinkController extends AbstractDocumentController
{
    private $labels = [
        'application' => 'Application ID',
        'busReg' => 'Bus registaration No',
        'case' => 'Case ID',
        'licence' => 'Licence No',
        'irfo' => 'IRFO ID',
        'transportManager' => 'Transport manager ID'
    ];

    /**
     * Performs a copy or move document(s) and redirect back to index
     */
    public function relinkAction()
    {
        $this->loadScripts(['forms/relink-document']);
        $ids = $this->params()->fromRoute('tmpId');
        $type = $this->params()->fromRoute('type');
        $form = $this->getRelinkForm($type, $ids);
        $post = (array) $this->getRequest()->getPost();
        $form->setData($post);
        $this->alterForm($form);

        if ($this->getRequest()->isPost() && $form->isValid()) {
            $res = $this->processRelink($post, $form);
            if ($res instanceof Response) {
                return $res;
            }
        }
        return $this->getRelinkView($form);
    }

    protected function getRelinkView($form)
    {
        $translator = $this->getServiceLocator()->get('translator');
        $view = new ViewModel(['form' => $form]);

        $view->setTemplate('partials/form-with-fm');

        return $this->renderView($view, $translator->translate('internal.documents.relink_documents'));
    }

    protected function getRelinkForm($type, $ids)
    {
        $formHelper = $this->getServiceLocator()->get('Helper\Form');
        $form = $formHelper->createFormWithRequest('DocumentRelink', $this->getRequest());
        $form->get('document-relink-details')->get('type')->setValue($type);
        $form->get('document-relink-details')->get('ids')->setValue($ids);
        return $form;
    }

    protected function processRelink($post, $form)
    {
        $routeParams = $this->params()->fromRoute();
        $type = $routeParams['type'];
        if (isset($post['form-actions']['copy'])) {
            $dto = CopyDocument::class;
            $message = 'internal.documents.documents_copied';
        } elseif (isset($post['form-actions']['move'])) {
            $dto = MoveDocument::class;
            $message = 'internal.documents.documents_moved';
        } else {
            return $this->redirectToDocumentRoute($type, null, $routeParams, true);
        }
        $data = DocumentRelinkMapper::mapFromForm($post);
        $response = $this->handleCommand($dto::create($data));

        if ($response->isOk()) {
            $this->getServiceLocator()->get('Helper\FlashMessenger')->addSuccessMessage($message);
            return $this->redirectToDocumentRoute($type, null, $routeParams, true);
        }
        if ($response->isServerError()) {
            $this->getServiceLocator()->get('Helper\FlashMessenger')->addErrorMessage('unknown-error');
        }
        if ($response->isClientError()) {
            $errors = DocumentRelinkMapper::mapFromErrors($form, $response->getResult());
            foreach ($errors as $error) {
                $this->getServiceLocator()->get('Helper\FlashMessenger')->addErrorMessage($error);
            }
        }
    }

    protected function alterForm($form)
    {
        $type = $form->get('document-relink-details')->get('type')->getValue();
        $form->get('document-relink-details')->get('targetId')->setLabel($this->labels[$type]);
    }
}
