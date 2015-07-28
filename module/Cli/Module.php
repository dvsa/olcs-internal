<?php

/**
 * Cli Module
 *
 * @author Mat Evans <mat.evans@valtech.co.uk>
 */
namespace Cli;

use Zend\Mvc\MvcEvent;
use Zend\ModuleManager\Feature\ConsoleUsageProviderInterface;
use Zend\Console\Adapter\AdapterInterface as ConsoleAdapterInterface;

/**
 * Cli Module
 *
 * @author Mat Evans <mat.evans@valtech.co.uk>
 */
class Module implements ConsoleUsageProviderInterface
{
    /**
     * Display CLI usage
     *
     * @param ConsoleAdapterInterface $console
     *
     * @return array
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function getConsoleUsage(ConsoleAdapterInterface $console)
    {
        return array(
            // Describe available commands
            'batch-licence-status [--verbose|-v]' => 'Process licence status change rules',
            'inspection-request-email [--verbose|-v]' => 'Process inspection request emails',
            'process-inbox [--verbose|-v]' => 'Process inbox documents',
            'batch-cns  [--verbose|-v] [--dryrun|-d]' => 'Process Licences for Continuation Not Sought',
            // Describe parameters
            array( '--verbose|-v', '(optional) turn on verbose mode'),
            array( '--dryrun|-d', '(optional) dryrun, nothing is actually changed'),

            'process-queue' => 'Process the queue',
            array( '--type|-t', '(optional) queue message type to process'),
        );
    }

    /**
     * @inheritDoc
     */
    public function onBootstrap(MvcEvent $event)
    {
        // block session saving, as it is unnecessary for cli
        $handler = new \Cli\Session\NullSaveHandler();
        $manager = new \Zend\Session\SessionManager();
        $manager->setSaveHandler($handler);
        \Zend\Session\Container::setDefaultManager($manager);
    }

    public function getConfig()
    {
        $base = include __DIR__ . '/config/module.config.php';
        return $base;
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/',
                ),
            ),
        );
    }
}
