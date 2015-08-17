<?php

namespace Olcs\Listener\RouteParam;

use Common\RefData;
use CommonTest\Service\Entity\Schedule41EntityServiceTest;
use Olcs\Event\RouteParam;
use Olcs\Listener\RouteParams;
use Zend\EventManager\EventManagerInterface;
use Zend\EventManager\ListenerAggregateInterface;
use Zend\EventManager\ListenerAggregateTrait;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Common\View\Helper\PluginManagerAwareTrait as ViewHelperManagerAwareTrait;
use Common\Exception\ResourceNotFoundException;

/**
 * Class Application
 * @package Olcs\Listener\RouteParam
 */
class Application implements ListenerAggregateInterface, FactoryInterface
{
    use ListenerAggregateTrait;
    use ViewHelperManagerAwareTrait;

    /**
     * @var \Zend\Navigation\Navigation
     */
    protected $navigationService;

    /**
     * @var \Zend\Navigation\Navigation
     */
    protected $sidebarNavigationService;

    protected $annotationBuilder;

    protected $queryService;

    public function getAnnotationBuilder()
    {
        return $this->annotationBuilder;
    }

    public function getQueryService()
    {
        return $this->queryService;
    }

    public function setAnnotationBuilder($annotationBuilder)
    {
        $this->annotationBuilder = $annotationBuilder;
    }

    public function setQueryService($queryService)
    {
        $this->queryService = $queryService;
    }

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
        $application = $this->getApplication($id);

        $placeholder = $this->getViewHelperManager()->get('placeholder');
        $placeholder->getContainer('application')->set($application);

        $sidebarNav = $this->getSidebarNavigationService();

        $status = $application['status']['id'];

        $showGrantButton = $this->shouldShowGrantButton($status);
        $showWithdrawButton = $this->shouldShowWithdrawButton($status);
        $showRefuseButton = $this->shouldShowRefuseButton($status);
        $showApproveSchedule41Button = $this->shouldShowApproveSchedule41Button($application);
        $showResetSchedule41Button = $this->shouldShowResetSchedule41Button($application);
        $showRefuseSchedule41Button = $this->shouldShowRefuseSchedule41Button($application);

        if ($showGrantButton) {
            $showUndoGrantButton = false;
        } else {
            $showUndoGrantButton = $this->shouldShowUndoGrantButton($application);
        }

        $showNtuButton = $showUndoGrantButton; // display conditions are identical
        $showReviveApplicationButton = $this->shouldShowReviveApplicationButton($status);

        $sidebarNav->findById('application-decisions-grant')->setVisible($showGrantButton);
        $sidebarNav->findById('application-decisions-undo-grant')->setVisible($showUndoGrantButton);
        $sidebarNav->findById('application-decisions-withdraw')->setVisible($showWithdrawButton);
        $sidebarNav->findById('application-decisions-refuse')->setVisible($showRefuseButton);
        $sidebarNav->findById('application-decisions-not-taken-up')->setVisible($showNtuButton);
        $sidebarNav->findById('application-decisions-revive-application')->setVisible($showReviveApplicationButton);
        $sidebarNav->findById('application-decisions-approve-schedule41')->setVisible($showApproveSchedule41Button);
        $sidebarNav->findById('application-decisions-reset-schedule41')->setVisible($showResetSchedule41Button);
        $sidebarNav->findById('application-decisions-refuse-schedule41')->setVisible($showRefuseSchedule41Button);

        $sidebarNav->findById('application-quick-actions')->setVisible($this->shouldShowQuickActions($status));

        if (!$application['canCreateCase']) {
            // hide application case link in the navigation
            $this->getNavigationService()->findOneById('application_case')->setVisible(false);
        }
    }

    /**
     * Get the Application data
     *
     * @param id $id
     * @return array
     * @throws ResourceNotFoundException
     */
    private function getApplication($id)
    {
        $query = $this->getAnnotationBuilder()->createQuery(
            \Dvsa\Olcs\Transfer\Query\Application\Application::create(['id' => $id])
        );

        $response = $this->getQueryService()->send($query);

        if (!$response->isOk()) {
            throw new ResourceNotFoundException("Application id [$id] not found");
        }

        return $response->getResult();
    }

    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return mixed
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $this->setAnnotationBuilder($serviceLocator->get('TransferAnnotationBuilder'));
        $this->setQueryService($serviceLocator->get('QueryService'));
        $this->setViewHelperManager($serviceLocator->get('ViewHelperManager'));
        $this->setNavigationService($serviceLocator->get('Navigation'));
        $this->setSidebarNavigationService($serviceLocator->get('right-sidebar'));

        return $this;
    }

    protected function shouldShowWithdrawButton($status)
    {
        return ($status === \Common\RefData::APPLICATION_STATUS_UNDER_CONSIDERATION);
    }

    protected function shouldShowRefuseButton($status)
    {
        return ($status === \Common\RefData::APPLICATION_STATUS_UNDER_CONSIDERATION);
    }

    protected function shouldShowGrantButton($status)
    {
        return ($status === \Common\RefData::APPLICATION_STATUS_UNDER_CONSIDERATION);
    }

    protected function shouldShowUndoGrantButton($application)
    {
        if (!$application['isVariation']
            && $application['status']['id'] === \Common\RefData::APPLICATION_STATUS_GRANTED
        ) {
            return ($application['goodsOrPsv']['id'] === \Common\RefData::LICENCE_CATEGORY_GOODS_VEHICLE);
        }

        return false;
    }

    protected function shouldShowReviveApplicationButton($status)
    {
        return in_array(
            $status,
            array(
                \Common\RefData::APPLICATION_STATUS_NOT_TAKEN_UP,
                \Common\RefData::APPLICATION_STATUS_WITHDRAWN,
                \Common\RefData::APPLICATION_STATUS_REFUSED,
            )
        );
    }

    protected function shouldShowApproveSchedule41Button($application)
    {
        foreach ($application['s4s'] as $s4) {
            if (
                is_null($s4['outcome']) &&
                $application['status']['id'] == \Common\RefData::APPLICATION_STATUS_UNDER_CONSIDERATION
            ) {
                return true;
            }
        }

        return false;
    }

    protected function shouldShowResetSchedule41Button($application)
    {
        foreach ($application['s4s'] as $s4) {
            if (
                $application['status']['id'] == \Common\RefData::APPLICATION_STATUS_UNDER_CONSIDERATION &&
                $s4['outcome']['id'] === RefData::S4_STATUS_APPROVED
            ) {
                return true;
            }
        }

        return false;
    }

    protected function shouldShowRefuseSchedule41Button($application)
    {
        foreach ($application['s4s'] as $s4) {
            if (
                is_null($s4['outcome']) &&
                $application['status']['id'] == \Common\RefData::APPLICATION_STATUS_UNDER_CONSIDERATION
            ) {
                return true;
            }
        }
    }

    protected function shouldShowQuickActions($status)
    {
        return !in_array(
            $status,
            array(
                \Common\RefData::APPLICATION_STATUS_NOT_TAKEN_UP,
                \Common\RefData::APPLICATION_STATUS_WITHDRAWN,
                \Common\RefData::APPLICATION_STATUS_REFUSED,
                \Common\RefData::APPLICATION_STATUS_VALID,
            )
        );
    }
}
