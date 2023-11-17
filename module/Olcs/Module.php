<?php

/**
 * Module
 *
 * @author Someone <someone@valtech.co.uk>
 */

namespace Olcs;

use Laminas\Mvc\ModuleRouteListener;
use Laminas\Mvc\MvcEvent;
use Laminas\Navigation\Service\DefaultNavigationFactory;
use Laminas\View\Model\ViewModel;
use Common\Exception\ResourceNotFoundException;
use Olcs\Listener\HeaderSearch;
use Olcs\Listener\RouteParams;

/**
 * Module
 *
 * @author Someone <someone@valtech.co.uk>
 */
class Module
{
    /**
     * Event to Bootstrap the module
     *
     * @param MvcEvent $e MVC Event
     *
     * @return void
     */
    public function onBootstrap(MvcEvent $e)
    {

        $eventManager = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);

        $viewHelperManager = $e->getApplication()->getServiceManager()->get('ViewHelperManager');
        $placeholder = $viewHelperManager->get('placeholder');

        $placeholder->getContainer('pageTitle')->setSeparator(' / ');
        $placeholder->getContainer('pageSubtitle')->setSeparator(' / ');

        $headTitleHelper = $viewHelperManager->get('headTitle');
        $headTitleHelper->setSeparator(' - ');
        //$headTitleHelper->setDefaultAttachOrder(AbstractContainer::PREPEND);
        $headTitleHelper->append('Olcs');

        $eventManager->attach(
            MvcEvent::EVENT_DISPATCH_ERROR,
            function (MvcEvent $e) {
                $exception = $e->getParam('exception');
                // If something throws an uncaught ResourceNotFoundException in
                // an HTTP context, return a 404
                if (
                    $exception instanceof ResourceNotFoundException
                    && $e->getResponse() instanceof \Laminas\Http\Response
                ) {
                    $model = new ViewModel(
                        [
                            'message'   => $exception->getMessage(),
                            'reason'    => 'error-resource-not-found',
                            'exception' => $exception,
                        ]
                    );
                    $model->setTemplate('error/404');
                    $e->getViewModel()->addChild($model);
                    $e->getResponse()->setStatusCode(404);
                    $e->stopPropagation();
                    return $model;
                }
            }
        );

        $eventManager->attach(MvcEvent::EVENT_DISPATCH, [RouteParams::class, 'onDispatch'], 20);
        $eventManager->attach(MvcEvent::EVENT_DISPATCH, [HeaderSearch::class, 'onDispatch'], 20);

        $eventManager->attach(
            MvcEvent::EVENT_ROUTE,
            function (MvcEvent $e) {
                $routeMatch = $e->getRouteMatch();

                $controllerManager = $e->getApplication()->getServiceManager()->get('ControllerManager');
                $controllerClass = $controllerManager->get($routeMatch->getParam('controller'));
                $controllerFQCN = get_class($controllerClass);

                $container = $e->getApplication()->getServiceManager();
                $config = $container->get('Config');

                /** @var RouteParams $routeParamsListener */
                $routeParamsListener = $container->get(RouteParams::class);

                foreach ($config['route_param_listeners'] as $interface => $listeners) {
                    if (is_a($controllerFQCN, $interface, true)) {
                        foreach ($listeners as $listener) {
                            $listenerInstance = $container->get($listener);
                            $routeParamsListener->getEventManager()->attach(MvcEvent::EVENT_DISPATCH, $listenerInstance);
                        }
                    }
                }
            }
        );
    }

    /**
     * get module config
     *
     * @return array
     */
    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    /**
     * get Autoloader config for this module
     *
     * @return array
     */
    public function getAutoloaderConfig()
    {
        return array(
            'Laminas\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/',
                ),
            ),
        );
    }
}
