<?php

namespace Admin\Controller;

use Olcs\Controller\Interfaces\LeftViewProvider;
use Laminas\Mvc\Controller\AbstractActionController as LaminasAbstractActionController;
use Common\Controller\Traits\GenericRenderView;
use Laminas\View\Model\ViewModel;

/**
 * Abstract Controller
 *
 * @author Rob Caiger <rob@clocal.co.uk>
 *
 * @method \Common\Service\Cqrs\Response handleQuery(\Dvsa\Olcs\Transfer\Query\QueryInterface $query)
 * @method \Common\Service\Cqrs\Response handleCommand(\Dvsa\Olcs\Transfer\Command\CommandInterface $query)
 * @method \Common\Controller\Plugin\Redirect redirect()
 */
abstract class AbstractController extends LaminasAbstractActionController implements LeftViewProvider
{
    use GenericRenderView;

    /**
     * Get Left View
     *
     * @return ViewModel
     */
    public function getLeftView()
    {
        $view = new ViewModel();
        $view->setTemplate('admin/sections/admin/partials/left');

        return $view;
    }

    /**
     * Set Navigation Id
     *
     * @param string $id Nav Id
     *
     * @return void
     */
    protected function setNavigationId($id)
    {
        $this->getServiceLocator()->get('viewHelperManager')->get('placeholder')
            ->getContainer('navigationId')->set($id);
    }
}
