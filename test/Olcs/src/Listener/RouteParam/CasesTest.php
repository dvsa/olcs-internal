<?php


namespace OlcsTest\Listener\RouteParam;

use Olcs\Event\RouteParam;
use Olcs\Listener\RouteParams;
use Mockery\Adapter\Phpunit\MockeryTestCase as TestCase;
use Olcs\Listener\RouteParam\Cases;
use Mockery as m;

/**
 * Class CasesTest
 * @package OlcsTest\Listener\RouteParam
 */
class CasesTest extends TestCase
{
    public function testAttach()
    {
        $sut = new Cases();

        $mockEventManager = m::mock('Zend\EventManager\EventManagerInterface');
        $mockEventManager->shouldReceive('attach')->once()
            ->with(RouteParams::EVENT_PARAM . 'case', [$sut, 'onCase'], 1);

        $sut->attach($mockEventManager);
    }

    public function testOnCase()
    {
        $caseId = 1;
        $case = [
            'id' => $caseId,
            'closedDate' => '2014-01-01',
            'caseType' => [
                'id' => 'case_t_lic'
            ]
        ];
        $case = new \Olcs\Data\Object\Cases($case);
        $status = ['colour' => 'Grey', 'value' => 'Closed'];

        $event = new RouteParam();
        $event->setValue($caseId);

        $mockNavigationService = m::mock('Zend\Navigation\Navigation');
        $mockNavigationService->shouldReceive('findOneById')
            ->with('case_details_serious_infringement')->andReturnSelf();
        $mockNavigationService->shouldReceive('findOneById')->with('case_processing_decisions')->andReturnSelf();
        $mockNavigationService->shouldReceive('setVisible')->with(false);

        $mockCaseService = m::mock('Olcs\Service\Data\Cases');
        $mockCaseService->shouldReceive('fetchCaseData')->with($caseId)->andReturn($case);

        $mockContainer = m::mock('Zend\View\Helper\Placeholder\Container');
        $mockContainer->shouldReceive('prepend')->with('Case ' . $caseId);
        $mockContainer->shouldReceive('append')->with('Case ' . $caseId);
        $mockContainer->shouldReceive('append')->with('Case subtitle');
        $mockContainer->shouldReceive('set')->with($case);

        $mockContainer->shouldReceive('set')->with($status);

        $mockPlaceholder = m::mock('Zend\View\Helper\Placeholder');
        $mockPlaceholder->shouldReceive('getContainer')->with('pageTitle')->andReturn($mockContainer);
        $mockPlaceholder->shouldReceive('getContainer')->with('pageSubtitle')->andReturn($mockContainer);
        $mockPlaceholder->shouldReceive('getContainer')->with('case')->andReturn($mockContainer);

        $mockPlaceholder->shouldReceive('getContainer')->with('status')->andReturn($mockContainer);

        $mockViewHelperManager = m::mock('Zend\View\HelperPluginManager');
        $mockViewHelperManager->shouldReceive('get')->with('placeholder')->andReturn($mockPlaceholder);
        $mockViewHelperManager->shouldReceive('get')->with('headTitle')->andReturn($mockContainer);

        $sut = new Cases();
        $sut->setCaseService($mockCaseService);
        $sut->setNavigationService($mockNavigationService);
        $sut->setViewHelperManager($mockViewHelperManager);
        $sut->onCase($event);
    }

    public function testOnCaseTriggersLicence()
    {
        $caseId = 1;
        $case = [
            'id' => $caseId,
            'licence' => ['id' => 4],
            'application' => ['id' => 66],
            'closedDate' => null,
            'caseType' => [
                'id' => 'case_t_lic'
            ],
            'transportManager' => ['id' => 3],
            'tmDecisions' => [
                0 => [
                    'data' => 'data'
                ]
            ]
        ];
        $case = new \Olcs\Data\Object\Cases($case);

        $mockTarget = m::mock('Olcs\Listener\RouteParams');
        $mockTarget->shouldReceive('trigger')->with('licence', 4);
        $mockTarget->shouldReceive('trigger')->with('transportManager', 3);
        $mockTarget->shouldReceive('trigger')->once()->with('application', 66);

        $event = new RouteParam();
        $event->setValue($caseId);
        $event->setTarget($mockTarget);

        $mockNavigationService = m::mock('Zend\Navigation\Navigation');
        $mockNavigationService->shouldReceive('findOneById')->once()
            ->with('case_opposition')->andReturnSelf();
        $mockNavigationService->shouldReceive('findOneById')->once()
            ->with('case_details_legacy_offence')->andReturnSelf();
        $mockNavigationService->shouldReceive('findOneById')->once()
            ->with('case_details_annual_test_history')->andReturnSelf();
        $mockNavigationService->shouldReceive('findOneById')->once()
            ->with('case_details_prohibitions')->andReturnSelf();
        $mockNavigationService->shouldReceive('findOneById')->once()
            ->with('case_details_statements')->andReturnSelf();
        $mockNavigationService->shouldReceive('findOneById')->once()
            ->with('case_details_conditions_undertakings')->andReturnSelf();
        $mockNavigationService->shouldReceive('findOneById')->once()
            ->with('case_details_impounding')->andReturnSelf();
        $mockNavigationService->shouldReceive('findOneById')->once()
            ->with('case_processing_in_office_revocation')->andReturnSelf();
        $mockNavigationService->shouldReceive('setVisible')->times(8)->with(false);

        $mockSidebarNavigationService = m::mock('Zend\Navigation\Navigation');
        $mockSidebarNavigationService
            ->shouldReceive('findOneById')
            ->with('case-decisions-transport-manager-repute-not-lost')
            ->andReturnSelf();
        $mockSidebarNavigationService
            ->shouldReceive('findOneById')
            ->with('case-decisions-transport-manager-declare-unfit')
            ->andReturnSelf();
        $mockSidebarNavigationService
            ->shouldReceive('findOneById')
            ->with('case-decisions-transport-manager-no-further-action')
            ->andReturnSelf();
        $mockSidebarNavigationService->shouldReceive('setVisible')->with(false);

        $mockCaseService = m::mock('Olcs\Service\Data\Cases');
        $mockCaseService->shouldReceive('fetchCaseData')->with($caseId)->andReturn($case);

        $mockContainer = m::mock('Zend\View\Helper\Placeholder\Container');
        $mockContainer->shouldIgnoreMissing();

        $mockPlaceholder = m::mock('Zend\View\Helper\Placeholder');
        $mockPlaceholder->shouldReceive('getContainer')->withAnyArgs()->andReturn($mockContainer);

        $mockViewHelperManager = m::mock('Zend\View\HelperPluginManager');
        $mockViewHelperManager->shouldReceive('get')->with('placeholder')->andReturn($mockPlaceholder);
        $mockViewHelperManager->shouldReceive('get')->with('headTitle')->andReturn($mockContainer);
        $mockViewHelperManager->shouldReceive('get')->with('Navigation')->andReturn($mockNavigationService);
        $mockViewHelperManager->shouldReceive('get')->with('right-sidebar')->andReturn($mockSidebarNavigationService);

        $mockLicenceService = m::mock('Common\Service\Data\Licence');
        $mockLicenceService->shouldReceive('setData')->with(4, ['id' => 4]);

        $mockApplicationService = m::mock('Common\Service\Data\Application');
        $mockApplicationService->shouldReceive('setData')->with(66, ['id' => 66]);

        $sut = new Cases();
        $sut->setCaseService($mockCaseService);
        $sut->setNavigationService($mockNavigationService);
        $sut->setSidebarNavigationService($mockSidebarNavigationService);
        $sut->setViewHelperManager($mockViewHelperManager);
        $sut->setLicenceService($mockLicenceService);
        $sut->setApplicationService($mockApplicationService);
        $sut->onCase($event);
    }

    /*public function testOnCaseSetsDataButDoesNotTriggerLicence()
    {
        $caseId = 1;
        $case = [
            'id' => $caseId,
            'licence' => ['id' => 4],
            'closedDate' => null,
            'caseType' => [
                'id' => 'case_t_lic'
            ]
        ];
        $case = new \Olcs\Data\Object\Cases($case);

        $mockTarget = m::mock('Olcs\Listener\RouteParams');

        $event = new RouteParam();
        $event->setValue($caseId);
        $event->setTarget($mockTarget);
        $event->setContext(['licence' => 7]);

        $mockNavigationService = m::mock('Zend\Navigation\Navigation');
        $mockNavigationService->shouldReceive('findOneById')
            ->with('case_details_serious_infringement')->andReturnSelf();
        $mockNavigationService->shouldReceive('setVisible')->with(0);

        $mockCaseService = m::mock('Olcs\Service\Data\Cases');
        $mockCaseService->shouldReceive('fetchCaseData')->with($caseId)->andReturn($case);

        $mockContainer = m::mock('Zend\View\Helper\Placeholder\Container');
        $mockContainer->shouldIgnoreMissing();

        $mockPlaceholder = m::mock('Zend\View\Helper\Placeholder');
        $mockPlaceholder->shouldReceive('getContainer')->withAnyArgs()->andReturn($mockContainer);

        $mockViewHelperManager = m::mock('Zend\View\HelperPluginManager');
        $mockViewHelperManager->shouldReceive('get')->with('placeholder')->andReturn($mockPlaceholder);
        $mockViewHelperManager->shouldReceive('get')->with('headTitle')->andReturn($mockContainer);

        $mockLicenceService = m::mock('Common\Service\Data\Licence');
        $mockLicenceService->shouldReceive('setData')->with(4, ['id' => 4]);

        $sut = new Cases();
        $sut->setCaseService($mockCaseService);
        $sut->setNavigationService($mockNavigationService);
        $sut->setViewHelperManager($mockViewHelperManager);
        $sut->setLicenceService($mockLicenceService);
        $sut->onCase($event);
    }*/

    public function testCreateService()
    {
        $mockCaseService = m::mock('Olcs\Service\Data\Cases');
        $mockNavigationService = m::mock('Zend\Navigation\Navigation');
        $mockLicenceService = m::mock('Common\Service\Data\Licence');
        $mockApplicationService = m::mock('Common\Service\Data\Application');
        $mockViewHelperManager = m::mock('Zend\View\HelperPluginManager');

        $mockSl = m::mock('Zend\ServiceManager\ServiceLocatorInterface');
        $mockSl->shouldReceive('get')->with('ViewHelperManager')->andReturn($mockViewHelperManager);
        $mockSl->shouldReceive('get')->with('DataServiceManager')->andReturnSelf();
        $mockSl->shouldReceive('get')->with('Olcs\Service\Data\Cases')->andReturn($mockCaseService);
        $mockSl->shouldReceive('get')->with('Common\Service\Data\Licence')->andReturn($mockLicenceService);
        $mockSl->shouldReceive('get')->with('Common\Service\Data\Application')->andReturn($mockApplicationService);
        $mockSl->shouldReceive('get')->with('Navigation')->andReturn($mockNavigationService);
        $mockSl->shouldReceive('get')->with('right-sidebar')->andReturn($mockNavigationService);

        $sut = new Cases();
        $service = $sut->createService($mockSl);

        $this->assertSame($sut, $service);
        $this->assertSame($mockCaseService, $sut->getCaseService());
        $this->assertSame($mockLicenceService, $sut->getLicenceService());
        $this->assertSame($mockApplicationService, $sut->getApplicationService());
        $this->assertSame($mockNavigationService, $sut->getNavigationService());
        $this->assertSame($mockNavigationService, $sut->getSidebarNavigation());
        $this->assertSame($mockViewHelperManager, $sut->getViewHelperManager());
    }
}
