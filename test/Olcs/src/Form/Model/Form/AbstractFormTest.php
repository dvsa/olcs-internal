<?php

namespace OlcsTest\Form\Model\Form;

use Dvsa\OlcsTest\FormTester\AbstractFormTest as BaseAbstract;
use Dvsa\OlcsTest\Bootstrap;

/**
 * Class AbstractFormTest
 * @package OlcsTest\Form\Model\Form
 */
abstract class AbstractFormTest extends BaseAbstract
{
    /**
     * @return \Laminas\ServiceManager\ServiceManager
     */
    protected function getServiceManager()
    {
        return Bootstrap::getRealServiceManager();
    }
}
