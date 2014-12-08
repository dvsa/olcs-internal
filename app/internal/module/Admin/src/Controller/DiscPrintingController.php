<?php
/**
 * Disc Printing Controller
 *
 * @author Alex Peshkov <alex.peshkov@valtech.co.uk>
 */

namespace Admin\Controller;

use Admin\Controller\AbstractController;
use Zend\View\Model\ViewModel;
use Zend\View\Model\JsonModel;
use Common\Service\Entity\LicenceEntityService;

/**
 * Disc Printing Controller
 *
 * @author Alex Peshkov <alex.peshkov@valtech.co.uk>
 */

class DiscPrintingController extends AbstractController
{

    /**
     * Discs on page
     */
    const DISCS_ON_PAGE = 6;

    /**
     * Where we store generated lists of discs in JR
     */
    const STORAGE_PATH = 'discs';

    private $templateParams = [
        'PSV' => [
            'template' => 'PSVDiscTemplate',
            'bookmark' => 'Psv_Disc_Page'
        ],
        'Goods' => [
            'template' => 'GVDiscTemplate',
            'bookmark' => 'Disc_List'
        ]
    ];

    /**
     * Index action
     *
     * @return Zend\ViewModel\ViewModel
     */
    public function indexAction()
    {
        $form = $this->getForm('admin_disc-printing');

        $this->formPost($form, 'processForm');

        $this->pageTitle = 'admin_disc-printing.pageHeader';
        $inlineScripts = ['disc-printing'];
        if ($this->getRequest()->isPost() && $form->isValid()) {
            $inlineScripts[] = 'disc-printing-popup';
        }
        $successStatus = $this->params()->fromRoute('success', null);
        $view = new ViewModel(
            [
                'form' => $form,
                'successStatus' => $successStatus
            ]
        );
        $this->loadScripts($inlineScripts);
        $view->setTemplate('disc-printing/index');
        return $this->renderView($view);
    }

    /**
     * Process form
     *
     * @param array $data
     * @return mixed
     */
    protected function processForm($data)
    {
        $params = $this->getFlattenParams($data);

        // get disc prefix using disc sequence and licence type
        $discSequenceService = $this->getServiceLocator()->get('Admin\Service\Data\DiscSequence');
        $discPrefix = $discSequenceService->getDiscPrefix($params['discSequence'], $params['licenceType']);

        // get discs to print
        if ($params['operatorType'] === LicenceEntityService::LICENCE_CATEGORY_PSV) {

            $this->printDiscsPsv(
                $params['licenceType'],
                $params['startNumber'],
                $discPrefix
            );
        } else {

            $licenceIds = $this->printDiscsGoods(
                $params['niFlag'],
                $params['operatorType'],
                $params['licenceType'],
                $params['startNumber'],
                $discPrefix
            );
        }
    }

    protected function printDiscsPsv($licenceType, $startNo, $discPrefix)
    {
        $discService = $this->getServiceLocator()->get('Admin\Service\Data\PsvDisc');
        $discsToPrint = $discService->getDiscsToPrint(
            $licenceType,
            $discPrefix
        );

        $this->printDiscs(
            'PSV',
            $startNo,
            $discsToPrint
        );

        $queries = [];
        $bookmarks = [];
        foreach ($discsToPrint as $disc) {
            $id = $disc['licence']['id'];

            if (!isset($bookmarks[$id])) {
                $bookmarks[$id] = [
                    'NO_DISCS_PRINTED' => ['count' => 0]
                ];
            }

            $bookmarks[$id]['NO_DISCS_PRINTED']['count'] ++;

            $queries[$id] = [
                'licence' => $id,
                'user'    => $this->getLoggedInUser()
            ];
        }

        $this->getServiceLocator()
            ->get('VehicleList')
            ->setQueryData($queries)
            ->setBookmarkData($bookmarks)
            ->setTemplate('PSVVehiclesList')
            ->setDescription('PSV Vehicle List')
            ->generateVehicleList();

        $discService->setIsPrintingOn($discsToPrint);
    }

    protected function printDiscsGoods($niFlag, $operatorType, $licenceType, $startNo, $discPrefix)
    {
        $discService = $this->getServiceLocator()->get('Admin\Service\Data\GoodsDisc');
        $discsToPrint = $discService->getDiscsToPrint(
            $niFlag,
            $operatorType,
            $licenceType,
            $discPrefix
        );

        $this->printDiscs(
            'Goods',
            $startNo,
            $discsToPrint
        );

        $queries = [];
        foreach ($discsToPrint as $disc) {
            $id = $disc['licenceVehicle']['licence']['id'];
            $queries[$id] = [
                'licence' => $id,
                'user' => $this->getLoggedInUser()
            ];
        }

        // generate vehicle list for all licences which are affected by new discs

        $this->getServiceLocator()
            ->get('VehicleList')
            ->setQueryData($queries)
            ->setTemplate('GVVehiclesList')
            ->setDescription('Goods Vehicle List')
            ->generateVehicleList();

        $discService->setIsPrintingOn($discsToPrint);
    }

    protected function printDiscs($type, $startNo, $discsToPrint)
    {
        $bookmark = $this->templateParams[$type]['bookmark'];
        $filename = $this->templateParams[$type]['template'] . '.rtf';
        $template = '/templates/' . $this->templateParams[$type]['template'] . '.rtf';

        foreach ($discsToPrint as $disc) {
            $queryData[] = $disc['id'];
        }

        $documentService = $this->getServiceLocator()->get('Document');

        $file = $this->getServiceLocator()
            ->get('ContentStore')
            ->read($template);

        $query = $documentService->getBookmarkQueries($file, $queryData);

        $result = $this->makeRestCall('BookmarkSearch', 'GET', [], $query);

        $discNumber = (int)$startNo;

        // NB the loop-by-reference here
        foreach ($result[$bookmark] as &$row) {
            $row['discNo'] = $discNumber ++;
        }

        $content = $documentService->populateBookmarks($file, $result);

        $uploader = $this->getServiceLocator()
            ->get('FileUploader')
            ->getUploader();

        $uploader->setFile(['content' => $content]);

        $filePath = $this->getServiceLocator()
            ->get('Helper\Date')
            ->getDate('YmdHis') . '_' . $filename;

        $storedFile = $uploader->upload(
            // @TODO: must swap these back, see note below
            //self::STORAGE_PATH,
            'documents',
            $filePath
        );

        /**
         * @TODO: this *is* temporary; we just need some way of exposing the
         * generated document so the content of it can be QA'd until the print
         * scheduling logic is built.
         *
         * a future story will care about $storedFile->getIdentifier() because
         * it will want to enqueue a message for the print scheduler to actually
         * print the template out, for which it'll need the JackRabbit name...
         */
        $data = [
            'identifier'          => $storedFile->getIdentifier(),
            'description'         => $type . ' Disc List',
            'filename'            => $type . '_Disc_List.rtf',
            'fileExtension'       => 'doc_rtf',
            'licence'             => 7, // hard coded simply so we can demo against *something*
            'category'            => 1, // ditto
            'documentSubCategory' => 6, // ditto
            'isDigital'           => true,
            'isReadOnly'          => true,
            'issuedDate'          => $this->getServiceLocator()->get('Helper\Date')->getDate('Y-m-d H:i:s'),
            'size'                => $storedFile->getSize()
        ];

        $this->makeRestCall(
            'Document',
            'POST',
            $data
        );
        /**
         * End of temporary persistence logic
         */
    }

    /**
     * Alter form when we have all data set
     *
     * @param Zend\Form\Form $form
     * @return Zend\Form\Form
     */
    protected function alterFormBeforeValidation($form)
    {

        // we don't need Special Restricted Licence for discs numbering
        $licenceTypes = $form->get('licence-type')->get('licenceType')->getValueOptions();
        unset($licenceTypes['ltyp_sr']);
        $form->get('licence-type')->get('licenceType')->setValueOptions($licenceTypes);

        return $form;
    }

    /**
     * Alter form when we have all data set
     *
     * @param Zend\Form\Form $form
     * @return Zend\Form\Form
     */
    protected function postSetFormData($form)
    {

        // populate disc prefixes
        $discPrefixes = $this->populateDiscPrefixes();
        $form->get('prefix')->get('discSequence')->setValueOptions($discPrefixes);

        // get requered values from form
        $niFlag = $form->get('operator-location')->get('niFlag')->getValue();
        $licenceType = $form->get('licence-type')->get('licenceType')->getValue();
        $operatorType = $form->get('operator-type')->get('goodsOrPsv')->getValue();
        $discSequence = $form->get('prefix')->get('discSequence')->getValue();
        $startNumberEntered = $form->get('discs-numbering')->get('startNumber')->getValue();

        // get disc prefix using disc sequence and licence type
        $discSequenceService = $this->getServiceLocator()->get('Admin\Service\Data\DiscSequence');
        $discPrefix = $discSequenceService->getDiscPrefix($discSequence, $licenceType);

        // set up start number validator
        $goodsDiscNumberValidator = $this->getServiceLocator()->get('goodsDiscStartNumberValidator');
        $numbering = $this->processDiscNumbering(
            $niFlag,
            $licenceType,
            $operatorType,
            $discPrefix,
            $discSequence,
            $startNumberEntered
        );
        if (isset($numbering['startNumber'])) {
            $goodsDiscNumberValidator->setOriginalStartNumber($numbering['startNumber']);
        }

        $startNumberValidatorChain = $form
                                        ->getInputFilter()
                                        ->get('discs-numbering')
                                        ->get('startNumber')
                                        ->getValidatorChain();
        $startNumberValidatorChain->attach($goodsDiscNumberValidator);

        if (isset($numbering['endNumber'])) {
            $form->get('discs-numbering')->get('endNumber')->setValue($numbering['endNumber']);
        }
        if (isset($numbering['endNumberIncreased'])) {
            $form->get('discs-numbering')->get('endNumberIncreased')->setValue($numbering['endNumberIncreased']);
        }
        if (isset($numbering['totalPages'])) {
            $form->get('discs-numbering')->get('totalPages')->setValue($numbering['totalPages']);
        }

        return $form;
    }

    /**
     * Get disc prefixes
     *
     * @return Zend\ViewModel\JsonModel
     */
    public function discPrefixesListAction()
    {
        return new JsonModel($this->populateDiscPrefixes());
    }

    /**
     * Get disc numbering data
     *
     * @return Zend\ViewModel\JsonModel
     */
    public function discNumberingAction()
    {
        $params = $this->getFlattenParams();
        $flProcess = true;
        $viewResults = [];

        // checking params which needed to calculate goods discs start/end numbers,
        // we can't process further without having it defined
        if (!$params['niFlag'] || ($params['niFlag'] == 'N' && !$params['operatorType']) || !$params['licenceType'] ||
            !$params['discSequence'] || !$params['discPrefix']) {
            $flProcess = false;
        }

        // calculate start and end numbers, number of pages
        if ($flProcess) {
            $viewResults = $this->processDiscNumbering(
                $params['niFlag'],
                $params['licenceType'],
                $params['operatorType'],
                $params['discPrefix'],
                $params['discSequence'],
                $params['startNumber']
            );
        }

        return new JsonModel($viewResults);
    }

    /**
     * Fetch disc numbering data, validate start number and increase numbers if necessary
     *
     * @param string $niFlag
     * @param string $licenceType
     * @param string $operatorType
     * @param string $discPrefix
     * @param string $discSequence
     * @param int $startNumberEntered
     * @return array
     */
    protected function processDiscNumbering(
        $niFlag,
        $licenceType,
        $operatorType,
        $discPrefix,
        $discSequence,
        $startNumberEntered = null
    ) {
        $retv = [];

        if (!$niFlag || !$licenceType || ($niFlag == 'N' && !$operatorType) || !$discPrefix || !$discSequence) {
            return $retv;
        }
        $discSequenceService = $this->getServiceLocator()->get('Admin\Service\Data\DiscSequence');

        // calculate start and end numbers, number of pages
        $retv['startNumber'] = $discSequenceService->getDiscNumber($discSequence, $licenceType);

        if ($operatorType === LicenceEntityService::LICENCE_CATEGORY_PSV) {
            $psvDiscService = $this->getServiceLocator()->get('Admin\Service\Data\PsvDisc');
            $retv['discsToPrint'] = count(
                $psvDiscService->getDiscsToPrint($licenceType, $discPrefix)
            );
        } else {
            $goodsDiscService = $this->getServiceLocator()->get('Admin\Service\Data\GoodsDisc');
            $retv['discsToPrint'] = count(
                $goodsDiscService->getDiscsToPrint($niFlag, $operatorType, $licenceType, $discPrefix)
            );
        }

        $retv['endNumber'] = (int) ($retv['discsToPrint'] ? $retv['startNumber'] + $retv['discsToPrint'] - 1 : 0);
        $retv['originalEndNumber'] = $retv['endNumber'];
        $originalStartNumber = $retv['startNumber'];

        // if we received start number this means that user changed this value and we need to validate it
        // do not allow to decrease start number
        if ($startNumberEntered && $startNumberEntered < $retv['startNumber']) {
            $retv['error'] = 'Decreasing the start number is not permitted';
        } elseif ($startNumberEntered && $startNumberEntered > $retv['startNumber']) {
            // increasing start and end numbers
            $delta = $startNumberEntered - $retv['startNumber'];
            $retv['startNumber'] = $startNumberEntered;
            $retv['endNumber'] += $delta;
        }
        /*
         * we have two end numbers, one original, which calculated based on start number entered by user
         * and another one calculated by rounding up to nearest integer divided by 6. that's because
         * there are numbers already printed on the discs pages, 6 discs pere page, and even we need to print
         * only one disc, other numbers will be used voided.
         */
        $retv['endNumberIncreased'] = $retv['endNumber'];
        if ($retv['endNumber']) {
            $retv['endNumber'] =
                $retv['startNumber'] +
                $retv['discsToPrint'] +
                ((6 - $retv['discsToPrint'] % self::DISCS_ON_PAGE) % 6) - 1;
        }
        $retv['totalPages'] = $retv['discsToPrint'] ?
            (ceil(($retv['endNumber'] - $originalStartNumber) / self::DISCS_ON_PAGE)) -
            (floor(($retv['startNumber'] - $originalStartNumber) / self::DISCS_ON_PAGE)) :
            0;

        return $retv;
    }

    /**
     * Get list of disc prefixes
     *
     * @param string $niFlag
     * @param string $operatorType
     * @param string $licenceType
     * @return array
     */
    protected function getDiscPrefixes($niFlag, $operatorType, $licenceType)
    {
        $discSequenceService = $this->getServiceLocator()->get('Admin\Service\Data\DiscSequence');
        $prefixes = $discSequenceService->fetchListOptions(
            [
            'niFlag' => $niFlag,
            'goodsOrPsv' => $operatorType,
            'licenceType' => $licenceType
            ]
        );

        $retv = array();

        // sort prefixes alphabetically by label
        asort($prefixes);

        foreach ($prefixes as $id => $result) {
            $retv[] = array(
                'value' => $id,
                'label' => $result
            );
        }
        return $retv;
    }

    /**
     * Check parameters and populate disc prefixes
     *
     * @return array
     */
    protected function populateDiscPrefixes()
    {
        $params = $this->getFlattenParams();
        if (($params['niFlag'] == 'N' && !$params['operatorType']) || !$params['licenceType']) {
            return [];
        }

        return $this->getDiscPrefixes($params['niFlag'], $params['operatorType'], $params['licenceType']);
    }

    /**
     * Flatten provided array or parameters
     *
     * @param array $data
     * @return array
     */
    protected function getFlattenParams($data = null)
    {
        $params = is_array($data) ? $data : $this->getRequest()->getPost()->toArray();
        $flattenParams = [];
        $flattenParams['niFlag'] =
            isset($params['operator-location']['niFlag']) ? $params['operator-location']['niFlag'] :
            (isset($params['niFlag']) ? $params['niFlag'] : '');
        $flattenParams['operatorType'] =
            isset($params['operator-type']['goodsOrPsv']) ? $params['operator-type']['goodsOrPsv'] :
            (isset($params['operatorType']) ? $params['operatorType'] : '');
        $flattenParams['licenceType'] =
            isset($params['licence-type']['licenceType']) ? $params['licence-type']['licenceType'] :
            (isset($params['licenceType']) ? $params['licenceType'] : '');
        $flattenParams['startNumber'] =
            isset($params['discs-numbering']['startNumber']) ? $params['discs-numbering']['startNumber'] :
            (isset($params['startNumberEntered']) ? $params['startNumberEntered'] : null);
        $flattenParams['discSequence'] =
            isset($params['prefix']['discSequence']) ? $params['prefix']['discSequence'] :
            (isset($params['discSequence']) ? $params['discSequence'] : '');
        $flattenParams['discPrefix'] =isset($params['discPrefix']) ? $params['discPrefix'] : '';
        $flattenParams['isSuccessfull'] =isset($params['isSuccessfull']) ? $params['isSuccessfull'] : '';
        $flattenParams['endNumber'] =isset($params['endNumber']) ? $params['endNumber'] : '';

        return $flattenParams;
    }

    /**
     * Confirm disc printing
     *
     */
    public function confirmDiscPrintingAction()
    {
        $params = $this->getFlattenParams();
        $retv = [];
        $discSequenceService = $this->getServiceLocator()->get('Admin\Service\Data\DiscSequence');

        if ($params['operatorType'] === LicenceEntityService::LICENCE_CATEGORY_PSV) {
            $discService = $this->getServiceLocator()->get('Admin\Service\Data\PsvDisc');
            $discsToPrint = $discService->getDiscsToPrint(
                $params['licenceType'],
                $params['discPrefix']
            );
        } else {
            $discService = $this->getServiceLocator()->get('Admin\Service\Data\GoodsDisc');
            $discsToPrint = $discService->getDiscsToPrint(
                $params['niFlag'],
                $params['operatorType'],
                $params['licenceType'],
                $params['discPrefix']
            );
        }

        try {
            if ($params['isSuccessfull']) {
                $discService->setIsPrintingOffAndAssignNumber($discsToPrint, $params['startNumber']);
                $discSequenceService->setNewStartNumber(
                    $params['licenceType'],
                    $params['discSequence'],
                    $params['endNumber'] + 1
                );
            } else {
                $discService->setIsPrintingOff($discsToPrint);
            }
        } catch (\Exception $e) {
            $retv['status'] = 500;
        }
        return new JsonModel($retv);
    }
}
