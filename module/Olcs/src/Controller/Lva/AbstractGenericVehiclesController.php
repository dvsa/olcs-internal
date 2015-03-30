<?php

/**
 * Abstract Generic Vehicles Goods Controller
 *
 * @author Rob Caiger <rob@clocal.co.uk>
 */
namespace Olcs\Controller\Lva;

use Common\Controller\Lva\AbstractVehiclesGoodsController;
use Common\Service\Data\CategoryDataService;

/**
 * Abstract Generic Vehicles Goods Controller
 *
 * @author Rob Caiger <rob@clocal.co.uk>
 */
abstract class AbstractGenericVehiclesController extends AbstractVehiclesGoodsController
{
    /**
     * Print vehicles action
     */
    public function printVehiclesAction()
    {
        $documentService = $this->getServiceLocator()->get('Document');

        $file = $this->getServiceLocator()
            ->get('ContentStore')
            ->read('/templates/GVVehiclesList.rtf');

        $queryData = [
            'licence' => $this->getLicenceId(),
            'user' => $this->getServiceLocator()->get('Entity\User')->getCurrentUser()
        ];

        $query = $documentService->getBookmarkQueries($file, $queryData);

        $result = $this->getServiceLocator()->get('Entity\BookmarkSearch')->searchQuery($query);

        $content = $documentService->populateBookmarks($file, $result);

        $uploader = $this->getServiceLocator()
            ->get('FileUploader')
            ->getUploader();

        $uploader->setFile(['content' => $content]);

        $uploadedFile = $uploader->upload();

        $fileName = date('YmdHi') . '_' . 'Goods_Vehicle_List.rtf';

        // @NOTE: not pretty, but this will be absorbed into all the LVA rework anyway in which
        // this is solved
        $lvaType = $this->lva;

        $data = [
            $lvaType        => $this->getIdentifier(),
            'identifier'    => $uploadedFile->getIdentifier(),
            'description'   => 'Goods Vehicle List',
            'filename'      => $fileName,
            'fileExtension' => 'doc_rtf',
            'category'      => CategoryDataService::CATEGORY_LICENSING,
            'subCategory'   => CategoryDataService::DOC_SUB_CATEGORY_LICENCE_VEHICLE_LIST,
            'isDigital'     => true,
            'isReadOnly'    => true,
            'issuedDate'    => date('Y-m-d H:i:s'),
            'size'          => $uploadedFile->getSize()
        ];

        $this->getServiceLocator()->get('Entity\Document')->save($data);

        /**
         * rather than have to go off and fetch the file again, just
         * update the content of the one we got back earlier from JR
         * and serve it directly
         */
        $file->setContent($content);

        return $uploader->serveFile($file, $fileName);
    }

    /**
     * Format the table rows to have interim if its set on the vehicle.
     *
     * @return array $results The results with interim added.
     */
    protected function getTableData()
    {
        $results = parent::getTableData();

        array_walk(
            $results,
            function (&$vehicle) {
                if (!is_null($vehicle['interimApplication'])) {
                    $vehicle['vrm'] .= ' (interim)';
                    unset($vehicle['interimApplication']);
                }
            }
        );

        return $results;
    }
}
