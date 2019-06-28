<?php

namespace Olcs\Controller\IrhpPermits;

use Common\Util\IsEcmtId;
use Olcs\Controller\Traits as ControllerTraits;

/**
 * IRHP Docs Controller
 *
 * @author Ian Lindsay <ian@hemera-business-services.co.uk>
 * @author Dan Eggleston <dan@stolenegg.com>
 * @author Andy Newton <andy@vitri.ltd>
 */
class IrhpDocsController extends AbstractIrhpPermitController
{
    use ControllerTraits\DocumentActionTrait,
        ControllerTraits\DocumentSearchTrait;

    /**
     * Get configured document form
     *
     * @see \Olcs\Controller\Traits\DocumentActionTrait
     * @return \Zend\Form\FormInterface
     */
    protected function getConfiguredDocumentForm()
    {
        $filters = $this->getDocumentFilters();
        $form = $this->getDocumentForm($filters);

        return $form;
    }

    /**
     * Table to use
     *
     * @see \Olcs\Controller\Traits\DocumentSearchTrait
     * @return string
     */
    protected function getDocumentTableName()
    {
        return 'documents';
    }

    /**
     * Route (prefix) for document action redirects
     *
     * @see \Olcs\Controller\Traits\DocumentActionTrait
     * @return string
     */
    protected function getDocumentRoute()
    {
        return 'licence/irhp-docs';
    }

    /**
     * Route params for document action redirects
     *
     * @see \Olcs\Controller\Traits\DocumentActionTrait
     * @return array
     */
    protected function getDocumentRouteParams()
    {
        return [
            'permitid' => $this->getFromRoute('permitid'),
            'licence' => $this->getFromRoute('licence')
        ];
    }

    /**
     * Get document filters
     *
     * @return array
     */
    private function getDocumentFilters()
    {
        $appId = $this->getFromRoute('permitid');
        $attrName = IsEcmtId::isEcmtId($appId) ? 'ecmtPermitApplication' : 'irhpApplication';

        return $this->mapDocumentFilters(
            [
                'showDocs' => 'tsw_self_only',
                "$attrName" => $appId,
            ]
        );
    }


    /**
     * Get view model for document action
     *
     * @see \Olcs\Controller\Traits\DocumentActionTrait
     * @return \Zend\View\Model\ViewModel
     */
    protected function getDocumentView()
    {
        $filters = $this->getDocumentFilters();

        return $this->getView(
            [
                'table' => $this->getDocumentsTable($filters)
            ]
        );
    }
}
