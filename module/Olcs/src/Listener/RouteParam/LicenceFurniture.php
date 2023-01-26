<?php

namespace Olcs\Listener\RouteParam;

use Interop\Container\ContainerInterface;
use Common\Exception\ResourceNotFoundException;
use Common\Service\Cqrs\Command\CommandSenderAwareInterface;
use Common\Service\Cqrs\Command\CommandSenderAwareTrait;
use Common\Service\Cqrs\Query\QuerySenderAwareInterface;
use Common\Service\Cqrs\Query\QuerySenderAwareTrait;
use Dvsa\Olcs\Transfer\Query\Licence\Licence as LicenceQuery;
use Olcs\Event\RouteParam;
use Olcs\Listener\RouteParams;
use Laminas\EventManager\EventManagerInterface;
use Laminas\EventManager\ListenerAggregateInterface;
use Laminas\EventManager\ListenerAggregateTrait;
use Laminas\ServiceManager\FactoryInterface;
use Laminas\ServiceManager\ServiceLocatorInterface;
use Common\View\Helper\PluginManagerAwareTrait as ViewHelperManagerAwareTrait;
use Laminas\View\Model\ViewModel;

/**
 * Licence Furniture
 *
 * @author Rob Caiger <rob@clocal.co.uk>
 */
class LicenceFurniture implements
    ListenerAggregateInterface,
    FactoryInterface,
    QuerySenderAwareInterface,
    CommandSenderAwareInterface
{
    use ListenerAggregateTrait,
        ViewHelperManagerAwareTrait,
        QuerySenderAwareTrait,
        CommandSenderAwareTrait;

    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return mixed
     */
    public function createService(ServiceLocatorInterface $serviceLocator) : LicenceFurniture
    {
        return $this->__invoke($serviceLocator, LicenceFurniture::class);
    }

    /**
     * {@inheritdoc}
     */
    public function attach(EventManagerInterface $events, $priority = 1)
    {
        $this->listeners[] = $events->attach(
            RouteParams::EVENT_PARAM . 'licence',
            [$this, 'onLicenceFurniture'],
            $priority
        );
    }

    /**
     * @param RouteParam $e RouteParam event
     *
     * @throws ResourceNotFoundException
     */
    public function onLicenceFurniture(RouteParam $e)
    {
        $id = $e->getValue();
        $response = $this->getQuerySender()->send(LicenceQuery::create(['id' => $id]));

        if (!$response->isOk()) {
            throw new ResourceNotFoundException("Licence id [$id] not found");
        }

        $data = $response->getResult();

        $placeholder = $this->getViewHelperManager()->get('placeholder');

        $placeholder->getContainer('pageTitle')->set($data['licNo']);
        $placeholder->getContainer('pageSubtitle')->set($data['organisation']['name']);
        $placeholder->getContainer('status')->set($data['status']);
        $placeholder->getContainer('horizontalNavigationId')->set('licence');
        $placeholder->getContainer('isMlh')->set($data['isMlh']);

        $right = new ViewModel();
        $right->setTemplate('sections/licence/partials/right');

        $placeholder->getContainer('right')->set($right);
    }

    /**
     * @param ContainerInterface $container
     * @param $requestedName
     * @param array|null $options
     * @return LicenceFurniture
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null) : LicenceFurniture
    {
        $this->setViewHelperManager($container->get('ViewHelperManager'));
        $this->setQuerySender($container->get('QuerySender'));
        $this->setCommandSender($container->get('CommandSender'));
        return $this;
    }
}
