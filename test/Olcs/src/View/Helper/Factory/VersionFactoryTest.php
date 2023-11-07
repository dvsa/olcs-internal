<?php

namespace OlcsTest\View\Helper;

use Interop\Container\ContainerInterface;
use Mockery as m;
use Mockery\Adapter\Phpunit\MockeryTestCase as TestCase;
use Olcs\View\Helper\Version;
use OlcsTest\Bootstrap;
use Olcs\View\Helper\Factory\VersionFactory;

/**
 * @covers \OLCS\View\Helper\Factory\VersionFactory
 */
class VersionFactoryTest extends TestCase
{
    protected $serviceManager;

    /**
     * @var array
     */
    protected $config = [];

    public function setUp(): void
    {
        $this->serviceManager = Bootstrap::getRealServiceManager();
        $this->config = $this->serviceManager->get('Config');
    }

    private function overrideConfig(array $config = [])
    {
        $this->serviceManager->setService('Config', $config);
    }

    private function getMockViewServiceLocator()
    {
        $serviceLocator = m::mock(ContainerInterface::class);
        $serviceLocator->shouldReceive('get')
            ->with('Config')
            ->once()
            ->andReturn($this->config);

        return $serviceLocator;
    }

    public function testFactoryWithNoVersionWillReturnNotSpecified()
    {
        $this->config['application_version'] = '';
        $this->overrideConfig($this->config);

        $versionFactory = new VersionFactory();
        $version = $versionFactory->__invoke(
            $this->getMockViewServiceLocator(),
            Version::class
        );

        $this->assertEquals('Not specified', $version->__invoke());
    }

    public function testFactoryWithInvalidVersionWillReturnNotSpecified()
    {
        $this->config['application_version'] = ['number' => '1'];
        $this->overrideConfig($this->config);

        $versionFactory = new VersionFactory();
        $version = $versionFactory->__invoke(
            $this->getMockViewServiceLocator(),
            Version::class
        );

        $this->assertEquals('Not specified', $version->__invoke());
    }

    public function testFactoryWithVersionNumber()
    {
        $this->config['application_version'] = '1.123.111';
        $this->overrideConfig($this->config);

        $versionFactory = new VersionFactory();
        $version = $versionFactory->__invoke(
            $this->getMockViewServiceLocator(),
            Version::class
        );

        $this->assertEquals('1.123.111', $version->__invoke());
    }
}
