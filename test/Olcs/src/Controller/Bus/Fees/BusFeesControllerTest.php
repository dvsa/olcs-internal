<?php

/**
 * Bus Fees Controller Test
 *
 * @author Ian Lindsay <ian@hemera-business-services.co.uk>
 */
namespace OlcsTest\Controller\Bus\Fees;

use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;

/**
 * Bus Fees Controller Test
 *
 * @author Ian Lindsay <ian@hemera-business-services.co.uk>
 */
class BusFeesControllerTest extends AbstractHttpControllerTestCase
{
    public function setUp()
    {
        $this->setApplicationConfig(
            include __DIR__.'/../../../../../../config/application.config.php'
        );

        $this->controller = $this->getMock(
            '\Olcs\Controller\Bus\Fees\BusFeesController',
            array(
                'getViewWithBusReg',
                'renderView',
                'loadScripts',
                'params',
                'getServiceLocator',
                'getRequest',
                'getForm',
                'getTable',
                'makeRestCall',
                'getService',
                'loadCurrent',
                'redirect',
                'commonPayFeesAction'
            )
        );

        $query = new \Zend\Stdlib\Parameters();
        $request = $this->getMock('\stdClass', ['getQuery', 'isXmlHttpRequest', 'isPost', 'getPost']);
        $request->expects($this->any())
            ->method('getQuery')
            ->will($this->returnValue($query));

        $this->query = $query;
        $this->request = $request;

        $this->controller->expects($this->any())
            ->method('getRequest')
            ->will($this->returnValue($request));

        $this->view = $this->getMock(
            '\Zend\View\Model\ViewModel',
            array(
                'setTemplate'
            )
        );

        parent::setUp();
    }

    /**
     * Test feesAction
     *
     * @dataProvider feesActionProvider
     */
    public function testFeesAction($status, $feeStatus)
    {
        $this->markTestSkipped('TODO');

        $params = $this->getMock('\stdClass', ['fromRoute', 'fromQuery']);

        $params->expects($this->once())
            ->method('fromRoute')
            ->with('licence')
            ->will($this->returnValue(1));

        $params->expects($this->any())
            ->method('fromQuery')
            ->will(
                $this->returnValueMap(
                    [
                        ['status', $status],
                        ['page', 1, 1],
                        ['sort', 'receivedDate', 'receivedDate'],
                        ['order', 'DESC', 'DESC'],
                        ['limit', 10, 10],
                    ]
                )
            );

        $this->controller->expects($this->any())
            ->method('params')
            ->will($this->returnValue($params));

        $this->controller->expects($this->once())
            ->method('loadCurrent')
            ->will($this->returnValue(['routeNo' => 987]));

        $this->controller->expects($this->once())
            ->method('makeRestCall')
            ->will(
                $this->returnValue(
                    [
                        'Results' => [
                            ['id' => 123]
                        ]
                    ]
                )
            );

        $feesParams = [
            'licence' => 1,
            'page'    => '1',
            'sort'    => 'receivedDate',
            'order'   => 'DESC',
            'limit'   => 10,
            'busReg'  => [123]
        ];
        if ($feeStatus) {
            $feesParams['feeStatus'] = $feeStatus;
        }

        $fees = [
            'Results' => [
                [
                    'id' => 1,
                    'invoiceStatus' => 'is',
                    'description' => 'ds',
                    'amount' => 1,
                    'invoicedDate' => '2014-01-01',
                    'receiptNo' => '1',
                    'receivedDate' => '2014-01-01',
                    'feeStatus' => [
                        'id' => 'lfs_ot',
                        'description' => 'd'
                    ]
                ]
            ],
            'Count' => 1
        ];

        $mockFeeService = $this->getMock('\StdClass', ['getFees']);
        $mockFeeService->expects($this->once())
            ->method('getFees')
            ->with($this->equalTo($feesParams))
            ->will($this->returnValue($fees));

        $mockServiceLocator = $this->getMock('\StdClass', ['get']);
        $mockServiceLocator->expects($this->any())
            ->method('get')
            ->with($this->equalTo('Olcs\Service\Data\Fee'))
            ->will($this->returnValue($mockFeeService));

        $this->controller->expects($this->any())
             ->method('getServiceLocator')
             ->will($this->returnValue($mockServiceLocator));

        $mockForm = $this->getMock('\StdClass', ['remove', 'setData']);
        $mockForm->expects($this->once())
            ->method('remove')
            ->with($this->equalTo('csrf'))
            ->will($this->returnValue(true));

        $mockForm->expects($this->once())
            ->method('setData')
            ->will($this->returnValue(true));

        $this->controller->expects($this->once())
            ->method('getForm')
            ->will($this->returnValue($mockForm));

        $mockTable = $this->getMock('\StdClass', ['removeAction']);
        $mockTable->expects($this->once())
            ->method('removeAction')
            ->with($this->equalTo('new'));
        $this->controller->expects($this->once())
            ->method('getTable')
            ->will($this->returnValue($mockTable));

        $this->controller->feesAction();
    }

    /**
     * Data provider
     *
     * @return array
     */
    public function feesActionProvider()
    {
        return [
            ['current', 'IN ["lfs_ot","lfs_wr"]'],
            ['all', ''],
            ['historical', 'IN ["lfs_pd","lfs_w","lfs_cn"]']
        ];
    }

    /**
     * Test feesAction with invalid POST params
     */
    public function testFeesActionWithInvalidPostRedirectsCorrectly()
    {
        $this->request->expects($this->any())
            ->method('isPost')
            ->willReturn(true);

        $this->request->expects($this->any())
            ->method('getPost')
            ->willReturn([]);

        $params = $this->getMock('\stdClass', ['fromRoute']);

        $params->expects($this->any())
            ->method('fromRoute')
            ->will(
                $this->returnValueMap(
                    [
                        ['licence', 1],
                        ['busRegId', 123]
                    ]
                )
            );

        $this->controller->expects($this->any())
            ->method('params')
            ->will($this->returnValue($params));

        $redirect = $this->getMock('\stdClass', ['toRouteAjax']);

        $routeParams = [
            'licence' => 1,
            'busRegId' => 123
        ];

        $redirect->expects($this->once())
            ->method('toRouteAjax')
            ->with('licence/bus-fees', $routeParams)
            ->willReturn('REDIRECT');

        $this->controller->expects($this->once())
            ->method('redirect')
            ->willReturn($redirect);

        $this->assertEquals('REDIRECT', $this->controller->feesAction());
    }

    public function testPayFeesActionWithGet()
    {
        $this->controller->expects($this->once())
            ->method('commonPayFeesAction')
            ->willReturn('stubResponse');

        $this->assertEquals(
            'stubResponse',
            $this->controller->payFeesAction()
        );
    }
}
