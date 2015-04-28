<?php

namespace OlcsTest;

use Zend\Mvc\Service\ServiceManagerConfig;
use Zend\ServiceManager\ServiceManager;
use Mockery as m;

error_reporting(E_ALL | E_STRICT);
date_default_timezone_set('Europe/London');
chdir(dirname(__DIR__));

/**
 * Test bootstrap, for setting up autoloading
 */
class Bootstrap
{
    protected static $config = array();

    public static function init()
    {
        ini_set('memory_limit', '1G');
        // Setup the autloader
        $loader = static::initAutoloader();

        $loader->addPsr4('OlcsTest\\', __DIR__ . '/Olcs/src');
        $loader->addPsr4('CliTest\\', __DIR__ . '/Cli/src');
        $loader->addPsr4('AdminTest\\', __DIR__ . '/Admin/src');
        $loader->addPsr4('CommonTest\\', __DIR__ . '/../vendor/olcs/OlcsCommon/test/Common/src/Common');
        $loader->addPsr4('OlcsComponentTest\\', __DIR__ . '/Component');

        // Grab the application config
        // set to CLI config for the moment as the module needs to be enabled to test it
        $config = include dirname(__DIR__) . '/config/application.cli.config.php';

        self::$config = $config;

        self::getRealServiceManager();
    }

    /**
     * Changed this method to return a mock
     *
     * @return \Zend\ServiceManager\ServiceManager
     */
    public static function getServiceManager()
    {
        $sm = m::mock('\Zend\ServiceManager\ServiceManager')
            ->makePartial()
            ->setAllowOverride(true);

        // inject a real string helper
        $sm->setService('Helper\String', new \Common\Service\Helper\StringHelperService());

        return $sm;
    }

    /**
     * Added this method for backwards compatibility
     *
     * @return \Zend\ServiceManager\ServiceManager
     */
    public static function getRealServiceManager()
    {
        // @todo When we fix all unit tests so that all dependencies are mocked, adding this line in should
        // speed up the tests and reduce memory usage
        //return m::mock('\Zend\ServiceManager\ServiceManager')->makePartial();

        $serviceManager = new ServiceManager(new ServiceManagerConfig());
        $serviceManager->setService('ApplicationConfig', self::$config);
        $serviceManager->get('ModuleManager')->loadModules();
        $serviceManager->setAllowOverride(true);

        // Mess up the backend, so any real rest calls will fail
        $config = $serviceManager->get('Config');
        $config['service_api_mapping']['endpoints']['backend'] = 'http://some-fake-backend/';
        $serviceManager->setService('Config', $config);

        return $serviceManager;
    }

    protected static function initAutoloader()
    {
        require('init_autoloader.php');

        return $loader;
    }
}

Bootstrap::init();
