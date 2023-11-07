<?php

namespace OlcsTest\Service\Marker;

use Interop\Container\ContainerInterface;
use Mockery as m;
use Mockery\Adapter\Phpunit\MockeryTestCase as TestCase;
use Olcs\Service\Marker\MarkerPluginManager;
use Olcs\Service\Marker\MarkerPluginManagerFactory;

class MarkerPluginManagerFactoryTest extends TestCase
{
    protected $sut;

    public function setUp(): void
    {
        $this->sut = new MarkerPluginManagerFactory();
        parent::setUp();
    }

    public function testInvokeEmptyConfig()
    {
        $mockSl = m::mock(ContainerInterface::class);

        $mockSl->shouldReceive('get')->with('Config')->once()->andReturn(['marker_plugins' => []]);

        $this->assertInstanceOf(MarkerPluginManager::class, $this->sut->__invoke($mockSl, MarkerPluginManager::class));
    }

    public function testInvokeWithConfig()
    {
        $mockSl = m::mock(ContainerInterface::class);

        $mockSl->shouldReceive('get')->with('Config')->once()->andReturn(['marker_plugins' => ['XXX']]);

        $this->assertInstanceOf(MarkerPluginManager::class, $this->sut->__invoke($mockSl, MarkerPluginManager::class));
    }
}
