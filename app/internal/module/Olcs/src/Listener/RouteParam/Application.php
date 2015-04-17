<?php

namespace Olcs\Listener\RouteParam;

use Olcs\Event\RouteParam;
use Olcs\Listener\RouteParams;
use Zend\EventManager\EventManagerInterface;
use Zend\EventManager\ListenerAggregateInterface;
use Zend\EventManager\ListenerAggregateTrait;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Common\View\Helper\PluginManagerAwareTrait as ViewHelperManagerAwareTrait;
use Common\Service\Data\ApplicationAwareTrait;
use Common\Service\Entity\ApplicationEntityService;
use Common\Service\Entity\LicenceEntityService;
use Common\Exception\ResourceNotFoundException;

/**
 * Class Application
 * @package Olcs\Listener\RouteParam
 */
class Application implements ListenerAggregateInterface, FactoryInterface
{
    use ListenerAggregateTrait;
    use ApplicationAwareTrait;
    use ViewHelperManagerAwareTrait;

    /**
     * @var \Zend\Navigation\Navigation
     */
    protected $navigationService;

    /**
     * @var \Zend\Navigation\Navigation
     */
    protected $sidebarNavigationService;

    /**
     * @var \Common\Service\Entity\ApplicationEntityService
     */
    protected $applicationEntityService;

    /**
     * @return \Zend\Navigation\Navigation
     */
    public function getNavigationService()
    {
        return $this->navigationService;
    }

    /**
     * @param \Zend\Navigation\Navigation $navigationService
     * @return $this
     */
    public function setNavigationService($navigationService)
    {
        $this->navigationService = $navigationService;
        return $this;
    }

    /**
     * @return \Zend\Navigation\Navigation
     */
    public function getSidebarNavigationService()
    {
        return $this->sidebarNavigationService;
    }

    /**
     * @param \Zend\Navigation\Navigation $sidebarNavigationService
     * @return $this
     */
    public function setSidebarNavigationService($sidebarNavigationService)
    {
        $this->sidebarNavigationService = $sidebarNavigationService;
        return $this;
    }

    /**
     * @return \Zend\Navigation\Navigation
     */
    public function getApplicationEntityService()
    {
        return $this->applicationEntityService;
    }

    /**
     * @param \Zend\Navigation\Navigation $applicationEntityService
     * @return $this
     */
    public function setApplicationEntityService($applicationEntityService)
    {
        $this->applicationEntityService = $applicationEntityService;
        return $this;
    }

    /**
     * Attach one or more listeners
     *
     * Implementers may add an optional $priority argument; the EventManager
     * implementation will pass this to the aggregate.
     *
     * @param EventManagerInterface $events
     *
     * @return void
     */
    public function attach(EventManagerInterface $events)
    {
        $this->listeners[] = $events->attach(RouteParams::EVENT_PARAM . 'application', [$this, 'onApplication'], 1);
    }

    /**
     * @param RouteParam $e
     */
    public function onApplication(RouteParam $e)
    {
        $id = $e->getValue();

        $this->getApplicationService()->setId($id);
        $application = $this->getApplicationService()->fetchData($id);

        if (!$application) {
            throw new ResourceNotFoundException("Application id [$id] not found");
        }

        $placeholder = $this->getViewHelperManager()->get('placeholder');
        $placeholder->getContainer('application')->set($application);

        $sidebarNav = $this->getSidebarNavigationService();

        $status = $this->getApplicationEntityService()->getStatus($id);

        $showGrantButton = $this->shouldShowGrantButton($status);
        $showWithdrawButton = $this->shouldShowWithdrawButton($status);
        $showRefuseButton = $this->shouldShowRefuseButton($status);

        if ($showGrantButton) {
            $showUndoGrantButton = false;
        } else {
            $showUndoGrantButton = $this->shouldShowUndoGrantButton($id, $status);
        }

        $showNtuButton = $showUndoGrantButton; // display conditions are identical
        $showReviveApplicationButton = $this->shouldShowReviveApplicationButton($status);

        $sidebarNav->findById('application-decisions-grant')->setVisible($showGrantButton);
        $sidebarNav->findById('application-decisions-undo-grant')->setVisible($showUndoGrantButton);
        $sidebarNav->findById('application-decisions-withdraw')->setVisible($showWithdrawButton);
        $sidebarNav->findById('application-decisions-refuse')->setVisible($showRefuseButton);
        $sidebarNav->findById('application-decisions-not-taken-up')->setVisible($showNtuButton);
        $sidebarNav->findById('application-decisions-revive-application')->setVisible($showReviveApplicationButton);

        if (!$this->getApplicationService()->canHaveCases($id)) {
            // hide application case link in the navigation
            $this->getNavigationService()->findOneById('application_case')->setVisible(false);
        }
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
        $this->setApplicationService(
            $serviceLocator->get('DataServiceManager')->get('Common\Service\Data\Application')
        );
        $this->setApplicationEntityService($serviceLocator->get('Entity\Application'));
        $this->setNavigationService($serviceLocator->get('Navigation'));
        $this->setSidebarNavigationService($serviceLocator->get('right-sidebar'));

        return $this;
    }

    protected function shouldShowWithdrawButton($status)
    {
        return ($status === ApplicationEntityService::APPLICATION_STATUS_UNDER_CONSIDERATION);
    }

    protected function shouldShowRefuseButton($status)
    {
        return ($status === ApplicationEntityService::APPLICATION_STATUS_UNDER_CONSIDERATION);
    }

    protected function shouldShowGrantButton($status)
    {
        return ($status === ApplicationEntityService::APPLICATION_STATUS_UNDER_CONSIDERATION);
    }

    protected function shouldShowUndoGrantButton($applicationId, $status)
    {
        $applicationType = $this->getApplicationEntityService()->getApplicationType($applicationId);

        if ($applicationType === ApplicationEntityService::APPLICATION_TYPE_NEW
            && $status === ApplicationEntityService::APPLICATION_STATUS_GRANTED
        ) {
            $category = $this->getApplicationEntityService()->getCategory($applicationId);

            return ($category === LicenceEntityService::LICENCE_CATEGORY_GOODS_VEHICLE);
        }

        return false;
    }

    protected function shouldShowReviveApplicationButton($status)
    {
        return in_array(
            $status,
            array(
                ApplicationEntityService::APPLICATION_STATUS_NOT_TAKEN_UP,
                ApplicationEntityService::APPLICATION_STATUS_WITHDRAWN,
                ApplicationEntityService::APPLICATION_STATUS_REFUSED
            )
        );
    }
}
