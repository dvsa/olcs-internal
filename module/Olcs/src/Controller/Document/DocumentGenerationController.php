<?php

/**
 * Document Generation Controller
 *
 * @author Shaun Lizzio <shaun.lizzio@valtech.co.uk>
 * @author Nick Payne <nick.payne@valtech.co.uk>
 */
namespace Olcs\Controller\Document;

use Dvsa\Olcs\Transfer\Command\Document\CreateLetter;
use Dvsa\Olcs\Transfer\Query\Document\TemplateParagraphs;
use Zend\View\Model\ViewModel;

/**
 * Document Generation Controller
 *
 * @author Shaun Lizzio <shaun.lizzio@valtech.co.uk>
 * @author Nick Payne <nick.payne@valtech.co.uk>
 */
class DocumentGenerationController extends AbstractDocumentController
{
    /**
     * Labels for empty select options
     */
    const EMPTY_LABEL = 'Please select';

    public function generateAction()
    {
        $form = $this->generateForm('generate-document', 'processGenerate');

        $this->loadScripts(['generate-document']);

        $view = new ViewModel(['form' => $form]);

        $view->setTemplate('partials/form-with-fm');

        return $this->renderView($view, 'Generate letter');
    }

    public function listTemplateBookmarksAction()
    {
        $form = new \Zend\Form\Form();

        $fieldset = new \Zend\Form\Fieldset();
        $fieldset->setLabel('documents.bookmarks');
        $fieldset->setName('bookmarks');

        $form->add($fieldset);

        $this->addTemplateBookmarks($this->params('id'), $fieldset);

        $view = new ViewModel(['form' => $form]);
        $view->setTemplate('partials/form');
        $view->setTerminal(true);

        return $view;
    }

    /**
     * Wrap the callback with a try/catch to handle any bookmark errors.
     *
     * For this to work, application must be configured with:
     *  'halt_on_error' => true
     * ... otherwise the olcs-logging module swallows errors and we don't get
     * exceptions raised properly :-/
     *
     * @see Olcs\Logging\Helper\LogError::logError
     */
    public function processGenerate($data)
    {
        try {
            return $this->processGenerateDocument($data);
        } catch (\ErrorException $e) {
            $this->getLogger()->warn($e->getMessage());
            $this->getServiceLocator()->get('Helper\FlashMessenger')
                ->addCurrentErrorMessage('Unable to generate the document');
        }
    }

    protected function processGenerateDocument($data)
    {
        $dtoData = [
            'template' => $data['details']['documentTemplate']
        ];

        $response = $this->handleCommand(CreateLetter::create($dtoData));

        print '<pre>';
        print_r($response->getResult());
        exit;

        $template = $this->makeRestCall(
            'DocTemplate',
            'GET',
            $templateId,
            [
                'children' => [
                    'document' => []
                ]
            ]
        );

        // for filenames with space we need to replace " " with "_"
        // otherwise the file is not found
        $identifier = str_replace(" ", "_", $template['document']['identifier']);

        /**
         * 1) read the template from the content store
         */
        $file = $this->getContentStore()->read($identifier);
        if (!$file) {
            // bail out if we couldn't get the template
            throw new \ErrorException("Could not retrieve [$identifier] from content store");
        }

        /**
         * 2) assemble query data for doc service
         */
        $routeParams = $this->params()->fromRoute();

        $queryData = array_merge(
            $data,
            $routeParams,
            [
                'user' => $this->getLoggedInUser()
            ]
        );

        // if both the entityType and the entityId has some values then add it into $queryData
        if (!empty($routeParams['entityType']) && !empty($routeParams['entityId'])) {
            $queryData[$routeParams['entityType']] = $routeParams['entityId'];
        }

        // we need to link certain documents to multiple IDs
        switch ($routeParams['type']) {
            case 'application':
                $queryData['licence'] = $this->getLicenceIdForApplication();
                break;

            case 'case':
                $queryData = array_merge(
                    $queryData,
                    $this->getCaseData()
                );
                break;

            case 'busReg':
                $queryData['licence'] = $routeParams['licence'];
                break;

            default:
                break;
        }

        /**
         * 3) Pass the file into the doc service to extract the relevant
         *    bookmarks out of the file data and return an array of queries
         *    we need to answer in order to populate those bookmarks
         */
        $query = $this->getDocumentService()->getBookmarkQueries(
            $file,
            $queryData
        );

        /**
         * 4) Pass those queries into a custom backend endpoint which knows how to
         *    fetch data for multiple different entities at once and respects the
         *    keys to which they relate (e.g. doesn't trash the bookmark keys)
         */
        if (!empty($query)) {
            $result = $this->makeRestCall('BookmarkSearch', 'GET', [], $query);
        } else {
            // this is to allow templates with empty bookmarks
            $result = [];
        }

        /**
         * 5) We've now got all our dynamic data which we can feedback into
         *    our bookmarks to actually replace tokens with data. This will
         *    give us back a string of content which we can then save
         */
        $content = $this->getDocumentService()->populateBookmarks(
            $file,
            $result
        );

        /**
         * 6) All done; we can now persist our generated document
         *    to a temporary store. We also want to save some metadata
         *    so we can re-populate this form should we come back to it
         */
        $details = json_encode(
            [
                'details' => $data['details'],
                'bookmarks' => $data['bookmarks']
            ]
        );
        $meta = [self::METADATA_KEY => $details];

        $uploader = $this->getUploader();
        $uploader->setFile(
            [
                'content' => $content,
                'meta'    => $meta
            ]
        );

        $storedFile = $uploader->upload(self::TMP_STORAGE_PATH);

        // we don't know what params are needed to satisfy this type's
        // finalise route; so to be safe we supply them all
        $redirectParams = array_merge(
            $routeParams,
            [
                'tmpId' => $storedFile->getIdentifier()
            ]
        );

        return $this->redirectToDocumentRoute($routeParams['type'], 'finalise', $redirectParams);
    }

    protected function alterFormBeforeValidation($form)
    {
        $categories = $this->getListDataFromBackend(
            'Category',
            ['isDocCategory' => true],
            'description',
            'id',
            false
        );

        $entityType = $this->getFromRoute('entityType');
        $categoryMapType
            = !empty($entityType) ? $this->getFromRoute('entityType') : $this->params('type');

        $defaultData = [
            'details' => [
                'category' => $this->getCategoryForType($categoryMapType)
            ]
        ];
        $data = [];
        $filters = [];
        $subCategories = ['' => self::EMPTY_LABEL];
        $docTemplates = ['' => self::EMPTY_LABEL];

        if ($this->getRequest()->isPost()) {
            $data = (array)$this->getRequest()->getPost();
        } elseif ($this->params('tmpId')) {
            $data = $this->fetchTmpData();
            $this->removeTmpData();
        }

        $data = array_merge($defaultData, $data);

        $details = isset($data['details']) ? $data['details'] : [];

        $filters['category'] = $details['category'];
        $filters['isDoc'] = true;

        $subCategories = $this->getListDataFromBackend(
            'SubCategory',
            $filters,
            'subCategoryName'
        );

        if (isset($details['documentSubCategory'])) {
            $filters['subCategory'] = $details['documentSubCategory'];
            $docTemplates = $this->getListDataFromBackend(
                'DocTemplate',
                $filters
            );
        }

        $selects = [
            'details' => [
                'category' => $categories,
                'documentSubCategory' => $subCategories,
                'documentTemplate' => $docTemplates
            ]
        ];

        foreach ($selects as $fieldset => $inputs) {
            foreach ($inputs as $name => $options) {
                $form->get($fieldset)
                    ->get($name)
                    ->setValueOptions($options);
            }
        }

        if (isset($details['documentTemplate'])) {
            $this->addTemplateBookmarks(
                $details['documentTemplate'],
                $form->get('bookmarks')
            );
        }

        $form->setData($data);

        return $form;
    }

    public function downloadTmpAction()
    {
        return $this->getUploader()->download(
            $this->params('id'),
            $this->params('filename'),
            self::TMP_STORAGE_PATH
        );
    }

    /**
     * @NOTE Migrated
     */
    private function addTemplateBookmarks($id, $fieldset)
    {
        if (empty($id)) {
            return;
        }

        $response = $this->handleQuery(TemplateParagraphs::create(['id' => $id]));

        if (!$response->isOk()) {
            return;
        }

        $result = $response->getResult();

        $bookmarks = $result['docTemplateBookmarks'];

        foreach ($bookmarks as $bookmark) {

            $bookmark = $bookmark['docBookmark'];

            if (!empty($bookmark['description'])) {
                $description = $bookmark['description'];
            } else {
                $description = $bookmark['name'];
            }

            $element = new \Common\Form\Elements\InputFilters\MultiCheckboxEmpty;
            $element->setLabel($description);
            $element->setName($bookmark['name']);
            // user-supplied bookmarks are *all* optional
            $element->setOptions(['required' => false]);

            $options = [];
            foreach ($bookmark['docParagraphBookmarks'] as $paragraph) {

                $paragraph = $paragraph['docParagraph'];
                $options[$paragraph['id']] = $paragraph['paraTitle'];
            }
            $element->setValueOptions($options);

            $fieldset->add($element);
        }
    }

    protected function getListDataFromBackend(
        $entity,
        $filters = array(),
        $titleField = 'description',
        $keyField = 'id',
        $showAll = self::EMPTY_LABEL
    ) {
        return parent::getListDataFromBackend($entity, $filters, $titleField, $keyField, $showAll);
    }
}
