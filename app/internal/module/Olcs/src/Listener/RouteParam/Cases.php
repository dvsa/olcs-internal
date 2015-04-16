<?php

namespace Olcs\Listener\RouteParam;

use Olcs\Event\RouteParam;
use Olcs\Listener\RouteParams;
use Zend\EventManager\EventManagerInterface;
use Zend\EventManager\ListenerAggregateInterface;
use Zend\EventManager\ListenerAggregateTrait;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\View\Helper\Navigation\PluginManager as ViewHelperManager;
use Common\Service\Data\Licence as LicenceService;
use Olcs\Service\Data\Cases as CaseService;
use Common\View\Helper\PluginManagerAwareTrait as ViewHelperManagerAwareTrait;

/**
 * Class Cases
 * @package Olcs\Listener\RouteParam
 */
class Cases implements ListenerAggregateInterface, FactoryInterface
{
    use ListenerAggregateTrait;
    use ViewHelperManagerAwareTrait;

    /**
     * @var LicenceService
     */
    protected $licenceService;

    /**
     * @var ApplicationService
     */
    protected $applicationService;

    /**
     * @var CaseService
     */
    protected $caseService;

    /**
     * @var NavigationService
     */
    protected $navigationService;

    /**
     * @param \Olcs\Service\Data\Cases $caseService
     */
    public function setCaseService($caseService)
    {
        $this->caseService = $caseService;
    }

    /**
     * @return \Olcs\Service\Data\Cases
     */
    public function getCaseService()
    {
        return $this->caseService;
    }

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
     * @param \Common\Service\Data\Application $applicationService
     */
    public function setApplicationService($applicationService)
    {
        $this->applicationService = $applicationService;
    }

    /**
     * @return \Common\Service\Data\Application
     */
    public function getApplicationService()
    {
        return $this->applicationService;
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
     */
    public function setNavigationService($navigationService)
    {
        $this->navigationService = $navigationService;
    }

    /**
     * Gets the sidebar nav
     *
     * @return \Zend\Navigation\Navigation
     */
    public function getSidebarNavigation()
    {
        return $this->sidebarNavigationService;
    }

    /**
     * @param \Zend\Navigation\Navigation $sidebarNavigationService
     */
    public function setSidebarNavigationService($sidebarNavigationService)
    {
        $this->sidebarNavigationService = $sidebarNavigationService;
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
        $this->listeners[] = $events->attach(RouteParams::EVENT_PARAM . 'case', [$this, 'onCase'], 1);
    }

    /**
     * @param RouteParam $e
     */
    public function onCase(RouteParam $e)
    {
        $context = $e->getContext();
        $case = $this->getCaseService()->fetchCaseData($e->getValue());

        $this->getViewHelperManager()->get('headTitle')->prepend('Case ' . $case['id']);

        $placeholder = $this->getViewHelperManager()->get('placeholder');
        $placeholder->getContainer('pageTitle')->append('Case ' . $case['id']);
        $placeholder->getContainer('pageSubtitle')->append('Case subtitle');

        $placeholder->getContainer('case')->set($case);

        $status = [
            'colour' => $case['closedDate'] !== null ? 'Grey' : 'Orange',
            'value' => $case['closedDate'] !== null ? 'Closed' : 'Open',
        ];

        $placeholder->getContainer('status')->set($status);

        // if we already have licence data, no sense in getting it again.
        if (isset($case['licence']['id'])) {
            $this->getLicenceService()->setData($case['licence']['id'], $case['licence']);

            // Trigger the licence now - it won't trigger twice.
            $e->getTarget()->trigger('licence', $case['licence']['id']);
        }

        // if we already have application data, no sense in getting it again.
        if (isset($case['application']['id'])) {
            $this->getApplicationService()->setData($case['application']['id'], $case['application']);

            // Trigger the application now - it won't trigger twice.
            $e->getTarget()->trigger('application', $case['application']['id']);
        }

        // If we have a transportManager, get it here.
        if ($case->isTm()) {
            $this->getNavigationService()->findOneById('case_opposition')->setVisible(false);
            $this->getNavigationService()->findOneById('case_details_legacy_offence')->setVisible(false);
            $this->getNavigationService()->findOneById('case_details_annual_test_history')->setVisible(false);
            $this->getNavigationService()->findOneById('case_details_prohibitions')->setVisible(false);
            $this->getNavigationService()->findOneById('case_details_statements')->setVisible(false);
            $this->getNavigationService()->findOneById('case_details_conditions_undertakings')->setVisible(false);
            $this->getNavigationService()->findOneById('case_details_impounding')->setVisible(false);
            $this->getNavigationService()->findOneById('case_processing_in_office_revocation')->setVisible(false);

            // Trigger the transportManager now - it won't trigger twice.
            $e->getTarget()->trigger('transportManager', $case['transportManager']['id']);

            if (!empty($case['tmDecisions'])) {
                $sidebarNav = $this->getSidebarNavigation();
                $sidebarNav->findOneById('case-decisions-transport-manager-repute-not-lost')->setVisible(false);
                $sidebarNav->findOneById('case-decisions-transport-manager-declare-unfit')->setVisible(false);
                $sidebarNav->findOneById('case-decisions-transport-manager-no-further-action')->setVisible(false);
            }
        } else {
            $this->getNavigationService()->findOneById('case_details_serious_infringement')->setVisible(false);
            $this->getNavigationService()->findOneById('case_processing_decisions')->setVisible(false);
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
        $this->setCaseService($serviceLocator->get('DataServiceManager')->get('Olcs\Service\Data\Cases'));
        $this->setLicenceService($serviceLocator->get('DataServiceManager')->get('Common\Service\Data\Licence'));
        $this->setApplicationService(
            $serviceLocator->get('DataServiceManager')->get('Common\Service\Data\Application')
        );
        $this->setNavigationService($serviceLocator->get('Navigation'));
        $this->setSidebarNavigationService($serviceLocator->get('right-sidebar'));

        return $this;
    }
}
