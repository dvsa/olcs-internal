<?php

/**
 * Operator people controller tests
 *
 * @author Alex Peshkov <alex.peshkov@valtech.co.uk>
 */
namespace OlcsTest\Controller\Operator;

use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;

/**
 * Operator people controller tests
 *
 * @author Alex Peshkov <alex.peshkov@valtech.co.uk>
 */
class OperatorPeopleControllerTest extends AbstractHttpControllerTestCase
{
    /**
     * @var string
     */
    protected $controllerName = '\Olcs\Controller\Operator\OperatorPeopleController';

    /**
     * @var array
     */
    protected $mockMethods = ['getViewWithOrganisation', 'renderView'];

    /**
     * Set up
     */
    public function setUp()
    {
        parent::setUp();
        $this->setApplicationConfig(
            include __DIR__.'/../../../../../config/application.config.php'
        );

        $this->controller = $this->getMock($this->controllerName, $this->mockMethods);
    }

    /**
     * Test index action
     *
     * @group operatorPeopleController
     */
    public function testIndexAction()
    {
        $mockView = $this->getMock('Zend\View\Model\ViewModel', ['setTemplate']);
        $mockView->expects($this->once())
            ->method('setTemplate')
            ->with('pages/placeholder')
            ->will($this->returnSelf());

        $this->controller->expects($this->once())
            ->method('getViewWithOrganisation')
            ->will($this->returnValue($mockView));

        $this->controller->expects($this->once())
            ->method('renderView')
            ->with($this->equalTo($mockView))
            ->will($this->returnValue($mockView));

        $response = $this->controller->indexAction();

        $this->assertInstanceOf('Zend\View\Model\ViewModel', $response);
    }
}
