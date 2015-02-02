<?php

namespace Olcs\Listener\RouteParam;

use Olcs\Event\RouteParam;
use Olcs\Listener\RouteParams;
use Zend\EventManager\EventManagerInterface;
use Zend\EventManager\ListenerAggregateInterface;
use Zend\EventManager\ListenerAggregateTrait;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

use Zend\ServiceManager\ServiceLocatorAwareTrait;
use Zend\View\Helper\Navigation\PluginManager as ViewHelperManager;
use Common\View\Helper\PluginManagerAwareTrait as ViewHelperManagerAwareTrait;

/**
 * Class Cases
 * @package Olcs\Listener\RouteParam
 */
class BusRegId implements ListenerAggregateInterface, FactoryInterface
{
    use ListenerAggregateTrait;
    use ViewHelperManagerAwareTrait;
    use ServiceLocatorAwareTrait;

    protected $busRegService;

    /**
     * @var LicenceService
     */
    protected $licenceService;

    /**
     * @param \Common\Service\Data\Licence $licenceService
     */
    public function setLicenceService($licenceService)
    {
        $this->licenceService = $licenceService;
    }

    /**
     * @return \Common\Service\Data\Licence
     */
    public function getLicenceService()
    {
        return $this->licenceService;
    }

    /**
     * Attach one or more listeners
     *
     * Implementors may add an optional $priority argument; the EventManager
     * implementation will pass this to the aggregate.
     *
     * @param EventManagerInterface $events
     *
     * @return void
     */
    public function attach(EventManagerInterface $events)
    {
        $this->listeners[] = $events->attach(RouteParams::EVENT_PARAM . 'busRegId', [$this, 'onBusRegId'], 1);
    }

    /**
     * @param RouteParam $e
     */
    public function onBusRegId(RouteParam $e)
    {
        $context = $e->getContext();
        $busReg = $this->getBusRegService()->fetchOne($e->getValue());

        $title = $this->getPageTitle($busReg);
        $subTitle = $this->getSubTitle($busReg);

        $this->getViewHelperManager()->get('headTitle')->prepend($busReg['regNo']);

        $placeholder = $this->getViewHelperManager()->get('placeholder');

        $placeholder->getContainer('busReg')->set($busReg);

        $placeholder->getContainer('status')->set(
            $this->getStatusArray(
                $busReg['status']['id'],
                $busReg['status']['description']
            )
        );

        $placeholder->getContainer('pageTitle')->append($title);
        $placeholder->getContainer('pageSubtitle')->append($subTitle);

        if ($busReg['isShortNotice'] == 'N') {
            $navigationPlugin = $this->getViewHelperManager()->get('Navigation')->__invoke('navigation');
            $navigationPlugin->findOneBy('id', 'licence_bus_short')->setVisible(false);
        }

        // if we already have licence data, no sense in getting it again.
        if (isset($busReg['licence']['id'])) {
            $this->getLicenceService()->setData($busReg['licence']['id'], $busReg['licence']);
            if (!isset($context['licence'])) {
                $e->getTarget()->trigger('licence', $busReg['licence']['id']);
            }
        }
    }

    public function getPageTitle($busReg)
    {
        $urlPlugin = $this->getViewHelperManager()->get('Url');
        $licUrl = $urlPlugin->__invoke('licence/bus', ['licence' => $busReg['licence']['id']], [], true);
        return '<a href="' . $licUrl . '">' . $busReg['licence']['licNo'] . '</a>' . '/' . $busReg['routeNo'];
    }

    public function getSubTitle($busReg)
    {
        return $busReg['licence']['organisation']['name'] . ', Variation ' . $busReg['variationNo'];
    }

    /**
     * Get status array.
     *
     * @param $statusKey
     * @param $statusString
     *
     * @return array
     */
    public function getStatusArray($statusKey, $statusString)
    {
        $map = [
            'breg_s_admin'        => 'grey',
            'breg_s_registered'   => 'green',
            'breg_s_refused'      => 'grey',
            'breg_s_cancellation' => 'orange',
            'breg_s_withdrawn'    => 'grey',
            'breg_s_var'          => 'orange',
            'breg_s_cns'          => 'grey',
            'breg_s_cancelled'    => 'grey',
            'breg_s_new'          => 'orange'
        ];

        $status = [
            'colour' => $map[$statusKey],
            'value' => $statusString,
        ];

        return $status;
    }

    /**
     * @return mixed
     */
    public function getBusRegService()
    {
        return $this->busRegService;
    }

    /**
     * @param mixed $busRegService
     */
    public function setBusRegService($busRegService)
    {
        $this->busRegService = $busRegService;
    }

    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return mixed
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $this->setViewHelperManager($serviceLocator->get('ViewHelperManager'));
        $this->setServiceLocator($serviceLocator);

        $this->setLicenceService($serviceLocator->get('DataServiceManager')->get('Common\Service\Data\Licence'));
        $this->setBusRegService($serviceLocator->get('DataServiceManager')->get('Generic\Service\Data\BusReg'));

        return $this;
    }
}
