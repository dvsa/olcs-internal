<?php

/**
 * Document Upload Controller
 *
 * @author Nick Payne <nick.payne@valtech.co.uk>
 */
namespace Olcs\Controller\Document;

use Zend\View\Model\ViewModel;
use Common\Service\File\Exception as FileException;

/**
 * Document Upload Controller
 *
 * @author Nick Payne <nick.payne@valtech.co.uk>
 */
class DocumentFinaliseController extends AbstractDocumentController
{
    public function finaliseAction()
    {
        $routeParams = $this->params()->fromRoute();
        if ($this->isButtonPressed('back')) {
            return $this->redirectToDocumentRoute($routeParams['type'], 'generate', $routeParams);
        }
        $data = $this->fetchTmpData();

        $entities = [
            'Category' => 'category',
            'SubCategory' => 'documentSubCategory',
            'DocTemplate' => 'documentTemplate'
        ];

        $lookups = [];
        foreach ($entities as $entity => $key) {
            $result = $this->makeRestCall(
                $entity,
                'GET',
                ['id' => $data['details'][$key]]
            );
            if ($entity === 'SubCategory') {
                $lookups[$key] = $result['subCategoryName'];
            } else {
                $lookups[$key] = $result['description'];
            }
        }

        $templateName = $lookups['documentTemplate'];

        $url = sprintf(
            '<a href="%s">%s</a>',
            $this->url()->fromRoute(
                'fetch_tmp_document',
                [
                    'id' => $routeParams['tmpId'],
                    'filename' => $this->formatFilename($templateName) . '.rtf'
                ]
            ),
            $templateName
        );

        $data = [
            'category'    => $lookups['category'],
            'subCategory' => $lookups['documentSubCategory'],
            'template'    => $url
        ];

        $form = $this->generateFormWithData(
            'finalise-document',
            'processUpload',
            $data
        );

        $view = new ViewModel(['form' => $form]);
        $view->setTemplate('partials/form');
        return $this->renderView($view, 'Amend letter');
    }

    public function processUpload($data)
    {
        $routeParams = $this->params()->fromRoute();
        $type = $routeParams['type'];

        $data = $this->fetchTmpData();

        $files = $this->getRequest()->getFiles()->toArray();

        if (!isset($files['file']) || $files['file']['error'] !== UPLOAD_ERR_OK) {
            // @TODO this needs to be handled better; by the time we get here we
            // should *know* that our files are valid
            $this->addErrorMessage('Sorry; there was a problem uploading the file. Please try again.');
            return $this->redirectToDocumentRoute($type, 'finalise', $routeParams);
        }

        $uploader = $this->getUploader();
        $uploader->setFile($files['file']);

        try {
            $file = $uploader->upload();
        } catch (FileException $ex) {
            $this->addErrorMessage('The document store is unavailable. Please try again later');
            return $this->redirectToDocumentRoute($type, 'finalise', $routeParams);
        }

        $template = $this->makeRestCall(
            'DocTemplate',
            'GET',
            ['id' => $data['details']['documentTemplate']]
        );

        $templateName = $template['description'];

        $fileName = $this->getDocumentTimestamp()
            . '_' . $this->formatFilename($templateName)
            . '.' . $file->getExtension();

        $data = [
            'identifier'    => $file->getIdentifier(),
            'description'   => $templateName,
            'filename'      => $fileName,
            'category'      => $data['details']['category'],
            'subCategory'   => $data['details']['documentSubCategory'],
            'isExternal'    => false,
            'isReadOnly'    => true,
            'issuedDate'    => $this->getServiceLocator()->get('Helper\Date')->getDate('Y-m-d H:i:s'),
            'size'          => $file->getSize()
        ];

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
                $data['licence'] = $this->getFromRoute('licence');
                break;

            default:
                break;
        }

        $data[$type] = $routeParams[$this->getRouteParamKeyForType($type)];

        $this->makeRestCall(
            'Document',
            'POST',
            $data
        );

        $this->removeTmpData();

        return $this->redirectToDocumentRoute($type, null, $routeParams);
    }
}
