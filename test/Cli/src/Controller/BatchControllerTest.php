<?php

/**
 * Batch controller tests
 *
 * @author Mat Evans <mat.evans@valtech.co.uk>
 */
namespace CliTest\Controller;

use Zend\Test\PHPUnit\Controller\AbstractConsoleControllerTestCase;
use Mockery as m;
use OlcsTest\Traits\MockeryTestCaseTrait;
use OlcsTest\Bootstrap;

/**
 * Batch controller tests
 *
 * @author Mat Evans <mat.evans@valtech.co.uk>
 */
class BatchControllerTest extends AbstractConsoleControllerTestCase
{
    use MockeryTestCaseTrait;

    /**
     * @var \Zend\ServiceManager\ServiceLocatorInterface
     */
    private $sm;

    /**
     *
     * @var \Cli\Controller\BatchController
     */
    private $controller;

    public function setUp()
    {
        $this->setApplicationConfig(
            include __DIR__.'/../../../../' . 'config/application.cli.config.php'
        );

        $this->controller = $this->getMock('Cli\Controller\BatchController', ['getRequest']);

        $this->sm = Bootstrap::getServiceManager();

        $this->controller->setServiceLocator($this->sm);

        parent::setUp();
    }

    /**
     * Test verbose parameter turned on
     */
    public function testLicenceStatusVerbose()
    {
        $mockRequest = $this->getMock('StdClass', ['getParam']);
        $mockRequest->expects($this->any())
            ->method('getParam')
            ->will($this->returnValue(true));

        $this->controller->expects($this->any())
            ->method('getRequest')
            ->will($this->returnValue($mockRequest));

        $mockBatchService = m::mock('StdClass');
        $this->sm->setService('BatchLicenceStatus', $mockBatchService);
        $mockBatchService->shouldReceive('setConsoleAdapter')->once();
        $mockBatchService->shouldReceive('processToRevokeCurtailSuspend')->once();
        $mockBatchService->shouldReceive('processToValid')->once();

        $this->controller->licenceStatusAction();
    }

    /**
     * Test verbose parameter turned off
     */
    public function testLicenceStatusNotVerbose()
    {
        $mockRequest = $this->getMock('StdClass', ['getParam']);
        $mockRequest->expects($this->any())
            ->method('getParam')
            ->will($this->returnValue(false));

        $this->controller->expects($this->any())
            ->method('getRequest')
            ->will($this->returnValue($mockRequest));

        $mockBatchService = m::mock('\StdClass');
        $this->sm->setService('BatchLicenceStatus', $mockBatchService);
        $mockBatchService->shouldNotReceive('setConsoleAdapter');
        $mockBatchService->shouldReceive('processToRevokeCurtailSuspend')->once();
        $mockBatchService->shouldReceive('processToValid')->once();

        $this->controller->licenceStatusAction();
    }
}
