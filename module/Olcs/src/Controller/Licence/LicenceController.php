<?php

/**
 * Licence Controller
 *
 * @author Craig Reasbeck <craig.reasbeck@valtech.co.uk>
 */
namespace Olcs\Controller\Licence;

use Olcs\Controller\AbstractController;
use Olcs\Controller\Traits;

/**
 * Licence Controller
 *
 * @author Craig Reasbeck <craig.reasbeck@valtech.co.uk>
 */
class LicenceController extends AbstractController
{
    use Traits\LicenceControllerTrait,
        Traits\TaskSearchTrait,
        Traits\DocumentSearchTrait;

    public function detailsAction()
    {
        $view = $this->getViewWithLicence();
        $view->setTemplate('licence/index');

        return $this->renderView($view);
    }

    public function casesAction()
    {
        $this->checkForCrudAction('case', [], 'case');

        $view = $this->getViewWithLicence();

        $params = [
            'licence' => $this->params()->fromRoute('licence'),
            'page'    => $this->params()->fromRoute('page', 1),
            'sort'    => $this->params()->fromRoute('sort', 'id'),
            'order'   => $this->params()->fromRoute('order', 'desc'),
            'limit'   => $this->params()->fromRoute('limit', 10),
        ];

        $bundle = array(
            'children' => array(
                'caseType' => array(
                    'properties' => 'ALL'
                )
            )
        );

        $results = $this->makeRestCall('Cases', 'GET', $params, $bundle);

        $view->{'table'} = $this->getTable('case', $results, $params);

        $view->setTemplate('licence/cases');

        return $this->renderView($view);
    }

    public function oppositionAction()
    {
        $view = $this->getViewWithLicence();
        $view->setTemplate('licence/index');

        return $this->renderView($view);
    }

    public function documentsAction()
    {
        $this->pageLayout = 'licence';

        $filters = $this->mapDocumentFilters(
            array('licenceId' => $this->getFromRoute('licence'))
        );

        $table = $this->getDocumentsTable($filters, false);

        $view = $this->getViewWithLicence(
            array(
                'table' => $table->render(),
                'form'  => $this->getDocumentForm($filters),
                'inlineScript' => $this->loadScripts(['documents'])
            )
        );

        $view->setTemplate('licence/documents');
        $view->setTerminal(
            $this->getRequest()->isXmlHttpRequest()
        );

        return $this->renderView($view);
    }

    public function feesAction()
    {
        $view = $this->getViewWithLicence();
        $view->setTemplate('licence/index');

        return $this->renderView($view);
    }

    public function busAction()
    {
        $this->pageLayout = 'bus-list';

        $searchData = array(
            'licence' => $this->getFromRoute('licence'),
            'page' => 1,
            'sort' => 'regNo',
            'order' => 'DESC',
            'limit' => 10
        );

        $filters = array_merge(
            $searchData,
            $this->getRequest()->getQuery()->toArray()
        );

        // if status is set to all
        if (isset($filters['status']) && !$filters['status']) {
            unset($filters['status']);
        }

        $bundle = [
            'children' => [
                'otherServices' => [
                    'properties' => [
                        'serviceNo'
                    ]
                ]
            ]
        ];

        $resultData = $this->makeRestCall('BusReg', 'GET', $filters, $bundle);

        $table = $this->getTable(
            'busreg',
            $resultData,
            array_merge(
                $filters,
                array('query' => $this->getRequest()->getQuery())
            ),
            true
        );

        $form = $this->getForm('bus-reg-list');
        $form->remove('csrf'); //we never post
        $form->setData($filters);

        $this->setTableFilters($form);

        $view = $this->getViewWithLicence(
            array(
                'table' => $table,
                'inlineScript' => $this->loadScripts(['bus-reg-list'])
            )
        );

        $view->setTemplate('licence/processing');

        $view->setTerminal(
            $this->getRequest()->isXmlHttpRequest()
        );

        return $this->renderView($view);
    }

    /**
     * This method is to assist the heirachical nature of zend
     * navigation when parent pages need to also be siblings
     * from a breadcrumb and navigation point of view.
     *
     * @codeCoverageIgnore
     * @return \Zend\Http\Response
     */
    public function indexJumpAction()
    {
        return $this->redirect()->toRoute('licence/details/overview', [], [], true);
    }
}
