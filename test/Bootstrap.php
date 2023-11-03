<?php
namespace OlcsTest;

use Common\Service\Translator\TranslationLoader;
use Mockery as m;
use Laminas\I18n\Translator\LoaderPluginManager;
use Laminas\ServiceManager\ServiceManager;
use Laminas\Loader\AutoloaderFactory;
use RuntimeException;

date_default_timezone_set('Europe/London');
error_reporting(E_ALL & ~E_USER_DEPRECATED);
chdir(__DIR__);

/**
 * Test bootstrap, for setting up autoloading
 */
class Bootstrap
{
    protected static $serviceManager;

    public static function init()
    {
        ini_set('memory_limit', '500M');
        static::initAutoloader();

        // use ModuleManager to load this module and it's dependencies
        $config = include __DIR__.'/../config/application.config.php';

        $serviceManager = new ServiceManager([]);
        $serviceManager->setService('ApplicationConfig', $config);

        // If we want to a mock a service, we can.  But default services apply.
        $serviceManager->setAllowOverride(true);

        $mockTranslationLoader = m::mock(TranslationLoader::class);
        $mockTranslationLoader->shouldReceive('load')->andReturn(['default' => ['en_GB' => []]]);
        $mockTranslationLoader->shouldReceive('loadReplacements')->andReturn([]);
        $serviceManager->setService(TranslationLoader::class, $mockTranslationLoader);

        $pluginManager = new LoaderPluginManager($serviceManager);
        $pluginManager->setService(TranslationLoader::class, $mockTranslationLoader);
        $serviceManager->setService('TranslatorPluginManager', $pluginManager);

        static::$serviceManager = $serviceManager;
    }

    public static function chroot()
    {
        $rootPath = dirname(static::findParentPath('module'));
        chdir($rootPath);
    }

    public static function getServiceManager()
    {
        return static::$serviceManager;
    }

    public static function getRealServiceManager()
    {
        return static::$serviceManager;
    }

    protected static function initAutoloader()
    {
        $vendorPath = static::findParentPath('vendor');

        if (file_exists($vendorPath.'/autoload.php')) {
            include $vendorPath . '/autoload.php';
        }

        if (! class_exists('Laminas\Loader\AutoloaderFactory')) {
            throw new RuntimeException(
                'Unable to load ZF2. Run `php composer.phar install`'
            );
        }

        AutoloaderFactory::factory(
            [
                'Laminas\Loader\StandardAutoloader' => [
                    'autoregister_zf' => true,
                    'namespaces' => [
                        __NAMESPACE__ => __DIR__ . '/' . __NAMESPACE__,
                        'OlcsTest\\' => __DIR__ . '/Olcs/src',
                        'AdminTest\\' => __DIR__ . '/Admin/src',
                        'OlcsComponentTest\\' => __DIR__ . '/Component'
                    ],
                ],
            ]
        );
    }

    protected static function findParentPath($path)
    {
        $dir = __DIR__;
        $previousDir = '.';
        while (!is_dir($dir . '/' . $path)) {
            $dir = dirname($dir);
            if ($previousDir === $dir) {
                return false;
            }
            $previousDir = $dir;
        }
        return $dir . '/' . $path;
    }
}

Bootstrap::init();
Bootstrap::chroot();
