<?php

namespace Admin\Controller;

use Common\Controller\Plugin\Redirect;
use Common\Controller\Traits\GenericRenderView;
use Common\Service\Cqrs\Response;
use Dvsa\Olcs\Transfer\Command\CommandInterface;
use Dvsa\Olcs\Transfer\Query\QueryInterface;
use Laminas\Mvc\Controller\AbstractActionController as LaminasAbstractActionController;
use Laminas\View\HelperPluginManager;
use Laminas\View\Model\ViewModel;
use Olcs\Controller\Interfaces\LeftViewProvider;

/**
 * Abstract Controller
 *
 * @author Rob Caiger <rob@clocal.co.uk>
 *
 * @method Response handleQuery(QueryInterface $query)
 * @method Response handleCommand(CommandInterface $query)
 * @method Redirect redirect()
 */
abstract class AbstractController extends LaminasAbstractActionController implements LeftViewProvider
{
    use GenericRenderView;

    protected HelperPluginManager $viewHelperPluginManager;

    public function __construct(HelperPluginManager $viewHelperPluginManager)
    {
        $this->viewHelperPluginManager = $viewHelperPluginManager;
    }

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
        $this->viewHelperPluginManager->get('placeholder')
            ->getContainer('navigationId')->set($id);
    }
}
