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
        $view = $this->getViewWithLicence();
        $view->setTemplate('licence/index');

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
        // @NOTE only supported action thus far is to
        // generate a document, so no need to check anything
        // other than post as there's no other action to take
        if ($this->getRequest()->isPost()) {
            $action = strtolower($this->params()->fromPost('action'));

            $params = [
                'licence' => $this->getFromRoute('licence')
            ];

            return $this->redirect()->toRoute(
                'licence/documents/generate',
                $params
            );
        }

        $this->pageLayout = 'licence';

        $filters = $this->mapDocumentFilters(
            array('licenceId' => $this->getFromRoute('licence'))
        );

        $view = $this->getViewWithLicence(
            array(
                'table' => $this->getDocumentsTable($filters),
                'form'  => $this->getDocumentForm($filters),
                'inlineScript' => $this->loadScripts(['table-actions'])
            )
        );

        $view->setTemplate('licence/documents');
        $view->setTerminal(
            $this->getRequest()->isXmlHttpRequest()
        );

        return $this->renderView($view);
    }

    public function processingAction()
    {
        if ($this->getRequest()->isPost()) {
            $action = strtolower($this->params()->fromPost('action'));
            if ($action === 'create task') {
                $action = 'add';
            }

            $params = [
                'licence' => $this->getFromRoute('licence'),
                'action'  => $action
            ];

            if ($action !== 'add') {
                $id = $this->params()->fromPost('id');

                // @NOTE: edit doesn't allow multi IDs, but other
                // actions (like reassign) might, hence why we have
                // an explicit check here
                if ($action === 'edit') {
                    if (!is_array($id) || count($id) !== 1) {
                        throw new \Exception('Please select a single task to edit');
                    }
                    $id = $id[0];
                }

                $params['task'] = $id;
            }

            return $this->redirect()->toRoute(
                'licence/task_action',
                $params
            );
        }

        $this->pageLayout = 'licence';

        $filters = $this->mapTaskFilters(
            array('licenceId' => $this->getFromRoute('licence'))
        );

        $table = $this->getTaskTable($filters, false);

        // the table's nearly all good except we don't want
        // a couple of columns
        $table->removeColumn('name');
        $table->removeColumn('link');

        $view = $this->getViewWithLicence(
            array(
                'table' => $table->render(),
                'form'  => $this->getTaskForm($filters),
                'inlineScript' => $this->loadScripts(['table-actions', 'tasks'])
            )
        );

        $view->setTemplate('licence/processing');
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
        $view = $this->getViewWithLicence();
        $view->setTemplate('licence/index');

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
