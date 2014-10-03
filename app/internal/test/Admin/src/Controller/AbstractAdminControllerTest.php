<?php
/**
 * Abstract Admin Controller test
 *
 * @author Alex Peshkov <alex.peshkov@valtech.co.uk>
 */

namespace AdminTest\Controller;

use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;

use OlcsTest\Bootstrap;

/**
 * Abstract Admin Controller test
 *
 * @author Alex Peshkov <alex.peshkov@valtech.co.uk>
 */
abstract class AbstractAdminControllerTest extends AbstractHttpControllerTestCase
{
    protected $mockMethods = [];

    protected $serviceManager;

    protected $isPost = false;

    /**
     * Set up action
     */
    public function setUpAction()
    {
        $methods = array_merge($this->mockMethods, ['getView', 'getRequest']);
        $this->controller = $this->getMock($this->controllerName, $methods);

        $this->serviceManager = Bootstrap::getServiceManager();
        $this->serviceManager->setAllowOverride(true);
        $this->controller->setServiceLocator($this->serviceManager);

    }
}
