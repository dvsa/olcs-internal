<?php

namespace Olcs\Listener\RouteParam;

use Common\Exception\ResourceNotFoundException;
use Common\Service\Cqrs\Command\CommandSenderAwareInterface;
use Common\Service\Cqrs\Command\CommandSenderAwareTrait;
use Common\Service\Cqrs\Query\QuerySenderAwareInterface;
use Common\Service\Cqrs\Query\QuerySenderAwareTrait;
use Olcs\Event\RouteParam;
use Olcs\Listener\RouteParams;
use Laminas\EventManager\EventManagerInterface;
use Laminas\EventManager\ListenerAggregateInterface;
use Laminas\EventManager\ListenerAggregateTrait;
use Laminas\ServiceManager\FactoryInterface;
use Laminas\ServiceManager\ServiceLocatorInterface;
use Laminas\ServiceManager\ServiceLocatorAwareTrait;
use Common\View\Helper\PluginManagerAwareTrait as ViewHelperManagerAwareTrait;
use Laminas\View\Model\ViewModel;

/**
 * Organisation Furniture
 *
 * @author Rob Caiger <rob@clocal.co.uk>
 */
class OrganisationFurniture implements
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
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $this->setQuerySender($serviceLocator->get('QuerySender'));
        $this->setCommandSender($serviceLocator->get('CommandSender'));
        $this->setViewHelperManager($serviceLocator->get('ViewHelperManager'));

        return $this;
    }

    public function attach(EventManagerInterface $events)
    {
        $this->listeners[] = $events->attach(
            RouteParams::EVENT_PARAM . 'organisation',
            [$this, 'onOrganisation'],
            1
        );
    }

    /**
     * @param RouteParam $e
     */
    public function onOrganisation(RouteParam $e)
    {
        $id = $e->getValue();

        $organisation = $this->getOrganisation($id);
        $placeholder = $this->getViewHelperManager()->get('placeholder');

        $placeholder->getContainer('horizontalNavigationId')->set('operator');
        $placeholder->getContainer('organisationIsMlh')->set($organisation['organisationIsMlh']);
        $placeholder->getContainer('isMlh')->set('');

        $right = new ViewModel();
        $right->setTemplate('sections/operator/partials/right');
        $placeholder->getContainer('right')->set($right);

        if ($organisation['isUnlicensed']) {
            $placeholder->getContainer('pageSubtitle')->set($organisation['licence']['licNo']);
            $right->setVariable('hideQuickActions', true);
        }

        $pageTitle = !empty($organisation['name']) ? $organisation['name'] : '';

        $placeholder->getContainer('pageTitle')->set($pageTitle);
    }

    /**
     * Get the Organisation data
     *
     * @param int $id
     *
     * @return array Organisation date
     * @throws ResourceNotFoundException
     */
    private function getOrganisation($id)
    {
        // for performance reasons this query should be the same as the one in OrganisationFurniture
        $response = $this->getQuerySender()->send(
            \Dvsa\Olcs\Transfer\Query\Organisation\People::create(['id' => $id])
        );

        if (!$response->isOk()) {
            throw new ResourceNotFoundException("Organisation id [$id] not found");
        }

        return $response->getResult();
    }
}
