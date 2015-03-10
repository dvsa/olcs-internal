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
     * We want to remove the table when adding
     *
     * @param \Zend\Form\Form $form
     * @param string $mode
     */
    protected function alterVehicleFormForLocation($form, $mode)
    {
        // We never want to see the vehicle history table on add
        if ($mode == 'add') {
            $form->remove('vehicle-history-table');
            return;
        }

        $this->getServiceLocator()->get('Helper\Form')->populateFormTable(
            $form->get('vehicle-history-table'),
            $this->getHistoryTable()
        );
    }

    protected function getHistoryTable()
    {
        return $this->getServiceLocator()->get('Table')
            ->prepareTable('lva-vehicles-history', $this->getHistoryTableData());
    }

    protected function getHistoryTableData()
    {
        $licenceVehicleId = $this->params('child_id');

        $vrm = $this->getServiceLocator()->get('Entity\LicenceVehicle')->getVrm($licenceVehicleId);

        return $this->getServiceLocator()->get('Entity\VehicleHistoryView')->getDataForVrm($vrm);
    }

    /**
     * Format the table rows to have interim if its set on the vehicle.
     *
     * @return array $results The results with interim added.
     */
    protected function getTableData()
    {
        $results = parent::getTableData();

        foreach ($results as $licenceVehicle) {
            if (!is_null($licenceVehicle['interimApplication'])) {
                $licenceVehicle['vrm'] .= ' (interim)';
                unset($licenceVehicle['interimApplication']);
            }
        }

        return $results;
    }
}
