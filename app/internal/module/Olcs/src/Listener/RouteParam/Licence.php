<?php

namespace Olcs\Listener\RouteParam;

use Olcs\Event\RouteParam;
use Olcs\Listener\RouteParams;
use Zend\EventManager\EventManagerInterface;
use Zend\EventManager\ListenerAggregateInterface;
use Zend\EventManager\ListenerAggregateTrait;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Mvc\Router\RouteStackInterface;
use Common\View\Helper\PluginManagerAwareTrait as ViewHelperManagerAwareTrait;
use Common\Service\Entity\LicenceEntityService;
use Common\Service\Entity\LicenceStatusRuleEntityService;

/**
 * Class Licence
 * @package Olcs\Listener\RouteParam
 */
class Licence implements ListenerAggregateInterface, FactoryInterface
{
    use ListenerAggregateTrait,
        ViewHelperManagerAwareTrait;

    /**
     * @var \Common\Service\Data\Licence
     */
    protected $licenceService;

    /**
     * @var \Common\Service\Entity\LicenceStatusRuleEntityService
     */
    protected $licenceStatusService;

    /**
     * @var \Common\Service\Helper\LicenceStatusHelperService
     */
    protected $licenceStatusHelperService;

    /**
     * @var \Zend\Navigation\Navigation
     */
    protected $navigationService;

    /**
     * @var RouteStackInterface
     */
    protected $router;

    /**
     * @param \Common\Service\Data\Licence $licenceService
     * @return $this
     */
    public function setLicenceService($licenceService)
    {
        $this->licenceService = $licenceService;
        return $this;
    }

    /**
     * @return \Common\Service\Data\Licence
     */
    public function getLicenceService()
    {
        return $this->licenceService;
    }

    /**
     * @param \Common\Service\Entity\LicenceStatusRuleEntityService $licenceStatusService
     * @return $this
     */
    public function setLicenceStatusService($licenceStatusService)
    {
        $this->licenceStatusService = $licenceStatusService;
        return $this;
    }

    /**
     * @return \Common\Service\Entity\LicenceStatusRuleEntityService
     */
    public function getLicenceStatusService()
    {
        return $this->licenceStatusService;
    }

    /**
     * @param \Common\Service\Helper\LicenceStatusHelperService $licenceStatusHelperService
     * @return $this
     */
    public function setLicenceStatusHelperService($licenceStatusHelperService)
    {
        $this->licenceStatusHelperService = $licenceStatusHelperService;
        return $this;
    }

    /**
     * @return \Common\Service\Helper\LicenceStatusHelperService
     */
    public function getLicenceStatusHelperService()
    {
        return $this->licenceStatusHelperService;
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
    public function getNavigationService()
    {
        return $this->navigationService;
    }

    /**
     * @param \Zend\Mvc\Router\RouteStackInterface $router
     * @return $this
     */
    public function setRouter($router)
    {
        $this->router = $router;
        return $this;
    }

    /**
     * @return \Zend\Mvc\Router\RouteStackInterface
     */
    public function getRouter()
    {
        return $this->router;
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
        $this->listeners[] = $events->attach(RouteParams::EVENT_PARAM . 'licence', array($this, 'onLicence'), 1);
    }

    /**
     * @param RouteParam $e
     */
    public function onLicence(RouteParam $e)
    {
        $licenceId = $e->getValue();

        $this->getLicenceService()->setId($licenceId); //set default licence id for use in forms
        $licence = $this->getLicenceService()->fetchLicenceData($licenceId);

        $this->getViewHelperManager()->get('placeholder')
            ->getContainer('licence')
            ->set($licence);

        $this->showHideDecisionButtons($licence);
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
        $this->setLicenceService($serviceLocator->get('DataServiceManager')->get('Common\Service\Data\Licence'));
        $this->setLicenceStatusService($serviceLocator->get('Entity\LicenceStatusRule'));
        $this->setLicenceStatusHelperService($serviceLocator->get('Helper\LicenceStatus'));
        $this->setNavigationService($serviceLocator->get('right-sidebar'));
        $this->setRouter($serviceLocator->get('Router'));

        return $this;
    }


    /**
     * @param array $licence licence data
     */
    protected function showHideDecisionButtons($licence)
    {
        /** @var Zend\Navigation\Navigation */
        $sidebarNav = $this->getNavigationService();

        $this->showHideVariationButton($licence, $sidebarNav);
        $this->showHidePrintButton($licence, $sidebarNav);
        $this->showHideCurtailButton($licence, $sidebarNav);
        $this->showHideSurrenderButton($licence, $sidebarNav);
        $this->showHideTerminateButton($licence, $sidebarNav);
    }

    /**
     * @param array $licence licence data
     * @param Zend\Navigation\Navigation $sidebarNav side bar navigation object
     * @return boolean whether 'Create variation' button is shown or not
     */
    protected function showHideVariationButton($licence, $sidebarNav)
    {
        // If the licence type is special restricted we can't create a variation
        if ($licence['licenceType']['id'] == LicenceEntityService::LICENCE_TYPE_SPECIAL_RESTRICTED) {
            $sidebarNav->findById('licence-quick-actions-create-variation')->setVisible(0);
            return false;
        }

        return true;
    }

    /**
     * @param array $licence licence data
     * @param Zend\Navigation\Navigation $sidebarNav side bar navigation object
     * @return boolean whether 'Print' button is shown or not
     */
    protected function showHidePrintButton($licence, $sidebarNav)
    {
        $printStatuses = [
            LicenceEntityService::LICENCE_STATUS_VALID,
            LicenceEntityService::LICENCE_STATUS_CURTAILED,
            LicenceEntityService::LICENCE_STATUS_SUSPENDED
        ];
        if (!in_array($licence['status']['id'], $printStatuses)) {
            $sidebarNav->findById('licence-quick-actions-print-licence')->setVisible(0);
            return false;
        }

        return true;
    }

    /**
     * @param array $licence licence data
     * @param Zend\Navigation\Navigation $sidebarNav side bar navigation object
     * @return boolean whether 'Curtail' button is shown or not
     */
    protected function showHideCurtailButton($licence, $sidebarNav)
    {
        if ($licence['status']['id'] !== LicenceEntityService::LICENCE_STATUS_VALID) {
            $sidebarNav->findById('licence-decisions-curtail')->setVisible(0);
            $sidebarNav->findById('licence-decisions-revoke')->setVisible(0);
            $sidebarNav->findById('licence-decisions-suspend')->setVisible(0);
        }

        $licenceStatusService = $this->getLicenceStatusService();
        $pendingDecisions = $licenceStatusService->getPendingChangesForLicence(
            return false;
        }

        if (!is_null($pendingDecisions)) {
            $sidebarNav = $this->getServiceLocator()->get('right-sidebar');
            $sidebarNav->findById('licence-decisions-curtail')->setVisible(0);
            $sidebarNav->findById('licence-decisions-revoke')->setVisible(0);
            $sidebarNav->findById('licence-decisions-suspend')->setVisible(0);
            return false;
        }

        return true;
    }

    /**
     * @param array $licence licence data
     * @param Zend\Navigation\Navigation $sidebarNav side bar navigation object
     * @return boolean whether 'Surrender' button is shown or not
     */
    protected function showHideSurrenderButton($licence, $sidebarNav)
    {
        // The surrender button is never shown if the licence is not valid
        if ($licence['status']['id'] !== LicenceEntityService::LICENCE_STATUS_VALID) {
            $sidebarNav->findById('licence-decisions-surrender')->setVisible(0);
            return false;
        }

        // The surrender button is only applicable for Goods licences
        if ($licence['goodsOrPsv']['id'] != LicenceEntityService::LICENCE_CATEGORY_GOODS_VEHICLE) {
            $sidebarNav->findById('licence-decisions-surrender')->setVisible(0);
            return false;
        }

        // The surrender button is hidden if there is a queued revocation,
        // curtailment or suspension
        $helper = $this->getLicenceStatusHelperService();
        if ($helper->hasQueuedRevocationCurtailmentSuspension($licence['id'])) {
            $sidebarNav->findById('licence-decisions-surrender')->setVisible(0);
            return false;
        }

        return true;
    }

    /**
     * @param array $licence licence data
     * @param Zend\Navigation\Navigation $sidebarNav side bar navigation object
     * @return boolean whether 'Terminate' button is shown or not
     */
    protected function showHideTerminateButton($licence, $sidebarNav)
    {
        // The terminate button is never shown if the licence is not valid
        if ($licence['status']['id'] !== LicenceEntityService::LICENCE_STATUS_VALID) {
            $sidebarNav->findById('licence-decisions-terminate')->setVisible(0);
            return false;
        }

        // The terminate button is only applicable for PSV licences
        if ($licence['goodsOrPsv']['id'] != LicenceEntityService::LICENCE_CATEGORY_PSV) {
            $sidebarNav->findById('licence-decisions-terminate')->setVisible(0);
            return false;
        }

        // The terminate button is hidden if there is a queued revocation,
        // curtailment or suspension
        $helper = $this->getLicenceStatusHelperService();
        if ($helper->hasQueuedRevocationCurtailmentSuspension($licence[['id']])) {
            $sidebarNav->findById('licence-decisions-terminate')->setVisible(0);
            return false;
        }

        return true;
    }

}
