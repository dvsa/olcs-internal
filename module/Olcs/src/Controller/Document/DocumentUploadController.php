<?php

/**
 * Document Upload Controller
 *
 * @author Jessica Rowbottom <jess.rowbottom@valtech.co.uk>
 * @author Shaun Lizzio <shaun.lizzio@valtech.co.uk>
 * @author Nick Payne <nick.payne@valtech.co.uk>
 */
namespace Olcs\Controller\Document;

use Common\Util\FileContent;
use Dvsa\Olcs\Transfer\Command\Document\CreateDocument;
use Dvsa\Olcs\Transfer\Command\Document\Upload;
use Zend\Form\Form;
use Zend\View\Model\ViewModel;

/**
 * Document Generation Controller
 *
 * @author Jessica Rowbottom <jess.rowbottom@valtech.co.uk>
 * @author Shaun Lizzio <shaun.lizzio@valtech.co.uk>
 * @author Nick Payne <nick.payne@valtech.co.uk>
 */
class DocumentUploadController extends AbstractDocumentController
{
    public function uploadAction()
    {
        if ($this->getRequest()->isPost()) {
            $data = (array)$this->getRequest()->getPost();
            $category = $data['details']['category'];
        } else {
            $type = $this->params()->fromRoute('type');
            $category = $this->getCategoryForType($type);
            $data = [
                'details' => [
                    'category' => $category
                ]
            ];
        }

        // @todo data services for list data are changing as part of another story
        $this->getServiceLocator()
            ->get('DataServiceManager')
            ->get('Olcs\Service\Data\DocumentSubCategory')
            ->setCategory($category);

        $form = $this->generateFormWithData('UploadDocument', 'processUpload', $data);

        $this->loadScripts(['upload-document']);

        $view = new ViewModel(['form' => $form]);

        $view->setTemplate('pages/form');
        return $this->renderView($view, 'Upload document');
    }

    public function processUpload($data, Form $form)
    {
        $routeParams = $this->params()->fromRoute();
        $type = $routeParams['type'];

        $files = $this->getRequest()->getFiles()->toArray();
        $files = $files['details'];

        if (!isset($files['file']) || $files['file']['error'] !== UPLOAD_ERR_OK) {
            // add validation error message to element, with reason upload errored
            $form->get('details')->get('file')->setMessages(['message.file-upload-error.' . $files['file']['error']]);

            return;
        }

        $data = array_merge(
            $data,
            [
                'filename'      => $files['file']['name'],
                'content'       => new FileContent($files['file']['tmp_name']),
                'description'   => $data['details']['description'],
                'category'      => $data['details']['category'],
                'subCategory'   => $data['details']['documentSubCategory'],
                'isExternal'    => false,
                'isReadOnly'    => 'Y'
            ]
        );

        $key = $this->getRouteParamKeyForType($type);
        $data[$type] = $routeParams[$key];

        // we need to link certain documents to multiple IDs
        switch ($type) {
            case 'application':
                $data['licence'] = $this->getLicenceIdForApplication();
                break;

            case 'case':
                $data = array_merge(
                    $data,
                    $this->getCaseData()
                );
                break;

            case 'busReg':
                $data['licence'] = $routeParams['licence'];
                break;

            default:
                break;
        }

        $response = $this->handleCommand(Upload::create($data));

        if ($response->isOk()) {
            $identifier = $response->getResult()['id']['identifier'];

            $routeParams = array_merge(
                $routeParams,
                [
                    'doc' => $identifier
                ]
            );

            return $this->redirectToDocumentRoute($type, null, $routeParams);
        }

        if ($response->isClientError()) {
            $messages = $response->getResult()['messages'];

            if (isset($messages['ERR_MIME'])) {

                $formMessages = [
                    'details' => [
                        'file' => [
                            'ERR_MIME'
                        ]
                    ]
                ];

                $form->setMessages($formMessages);

                return $form;
            }
        }

        $this->getServiceLocator()->get('Helper\FlashMessenger')->addCurrentUnknownError();

        return $form;
    }
}
