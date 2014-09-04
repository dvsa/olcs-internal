<?php

namespace Olcs\Controller\Document;

use Zend\View\Model\ViewModel;

use Dvsa\Jackrabbit\Data\Object\File;

class DocumentUploadController extends DocumentController
{
    private function fetchTmpData()
    {
        $path = self::TMP_STORAGE_PATH . '/' . $this->params()->fromRoute('tmpId');
        $meta = $this->getContentStore()
            ->readMeta($path);

        return json_decode($meta['metadataProperties']['meta:data'], true);
    }

    public function finaliseAction()
    {
        $data = $this->fetchTmpData();

        $url = sprintf(
            '<a href="%s">Download</a>',
            $this->url()->fromRoute(
                'fetch_tmp_document',
                ['path' => $this->params()->fromRoute('tmpId')]
            )
        );

        $data = [
            'category' => $data['details']['category'],
            'subCategory' => $data['details']['documentSubCategory'],
            'template' => $data['details']['documentTemplate'],
            'link' => $url
        ];
        $form = $this->generateFormWithData(
            'finalise-document',
            'processUpload',
            $data
        );
        $view = new ViewModel(
            [
                'form' => $form
            ]
        );
        // @TODO obviously, don't re-use this template; make a generic one if appropriate
        $view->setTemplate('task/add-or-edit');
        return $this->renderView($view, 'Amend letter');
    }


    public function processUpload($data)
    {
        $data = $this->fetchTmpData();

        // @TODO wrap this in more abstract methods if poss
        $files = $this->getRequest()->getFiles()->toArray();
        $uploader = $this->getUploader();
        $uploader->setFile($files['file']);
        $key = $uploader->upload(self::FULL_STORAGE_PATH);

        $templateName = 'a-template'; // @TODO from template...
        $fileExt = 'rtf';
        $fileName = date('YmdHi') . '_' . $templateName . '.' . $fileExt;

        $data = [
            'identifier'          => $key,
            'description'         => $templateName,
            'licence'             => $this->params()->fromRoute('licence'),
            'filename'            => $fileName,
            'fileExtension'       => $fileExt,
            'category'            => $data['details']['category'],
            'documentSubCategory' => $data['details']['documentSubCategory']
        ];

        // @TODO delete the tmp file
        $this->makeRestCall(
            'Document',
            'POST',
            $data
        );

        // @TODO hardcoding the return URL isn't appropriate here; we may well
        // generate docs from a non licencing section (do we? Need to check)
        return $this->redirect()->toRoute(
            'licence/details/overview',
            ['licence' => $this->params()->fromRoute('licence')]
        );
    }
}
