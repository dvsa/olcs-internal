<?php

/**
 * Description of OlcsIndexControllerTest
 *
 * @author adminmwc
 */

namespace OlcsTest\Controller;

use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;

class OlcsIndexControllerTest  extends AbstractHttpControllerTestCase
{
    public function setUp()
    {
        $this->setApplicationConfig(
            include __DIR__.'/../../../../' . 'config/application.config.php'
        );
        $this->controller = $this->getMock(
            '\Olcs\Controller\IndexController',
            array(
                'makeRestCall',
                'getLoggedInUser',
                'buildTable',
                'getRequest',
                'getForm',
                'loadScripts',
                'params'
            )
        );

        $query = new \Zend\Stdlib\Parameters();
        $request = new \Zend\Http\Request();
        $request->setQuery($query);

        $this->query = $query;

        $this->controller->expects($this->any())
            ->method('getRequest')
            ->will($this->returnValue($request));

        parent::setUp();
    }

    public function testIndexActionWithNoQueryUsesDefaultParams()
    {
        $this->controller->expects($this->any())
            ->method('getLoggedInUser')
            ->will($this->returnValue(1));

        $expectedParams = array(
            'owner' => 1,
            'team'  => 2,
            'date'  => 'today',
            'status' => 'open',
            'sort' => 'actionDate',
            'order' => 'ASC',
            'page' => 1,
            'limit' => 10,
            // @NOTE: I don't like the date variable here, maybe use
            // DateTime and a mock instead
            'actionDate' => '<= ' . date('Y-m-d')
        );
        $this->controller->expects($this->at(2))
            ->method('makeRestCall')
            ->with('TaskSearchView', 'GET', $expectedParams)
            ->will($this->returnValue([]));

        $this->controller->expects($this->once())
            ->method('buildTable')
            ->with(
                'tasks',
                [],
                array_merge(
                    $expectedParams,
                    array('query' => $this->query)
                )
            );

        $form = $this->getMock('\stdClass', ['get', 'setValueOptions', 'remove', 'setData']);

        $form->expects($this->any())
            ->method('get')
            ->will($this->returnSelf());

        $this->controller->expects($this->once())
            ->method('getForm')
            ->will($this->returnValue($form));

        $response = [
            'Results' => [
                [
                    'id' => 123,
                    'name' => 'foo'
                ]
            ]
        ];

        $altResponse = [
            'Results' => [
                [
                    'id' => 123,
                    'description' => 'foo'
                ]
            ]
        ];

        $standardListData = [
            'limit' => 100,
            'sort' => 'name'
        ];

        $altListData = [
            'limit' => 100,
            'sort' => 'description'
        ];

        $extendedListData = [
            'owner' => 1,
            'team'  => 2,
            'date'  => 'today',
            'status' => 'open',
            'sort' => 'name',
            'order' => 'ASC',
            'page' => 1,
            'limit' => 100,
            'actionDate' => '<= ' . date('Y-m-d')
        ];

        $this->controller->expects($this->at(6))
            ->method('makeRestCall')
            ->with('Team', 'GET', $standardListData)
            ->will($this->returnValue($response));

        $this->controller->expects($this->at(7))
            ->method('makeRestCall')
            ->with('User', 'GET', $extendedListData)
            ->will($this->returnValue($response));

        $this->controller->expects($this->at(8))
            ->method('makeRestCall')
            ->with('Category', 'GET', $altListData)
            ->will($this->returnValue($altResponse));

        $this->controller->expects($this->at(9))
            ->method('makeRestCall')
            ->with('TaskSubCategory', 'GET', $extendedListData)
            ->will($this->returnValue($response));

        $view = $this->controller->indexAction();
        list($header, $content) = $view->getChildren();

        $this->assertEquals('Home', $header->getVariable('pageTitle'));
        $this->assertEquals('Subtitle', $header->getVariable('pageSubTitle'));
    }

    public function testTaskFilterActionInvalidType()
    {
        $params = $this->getMock('\stdClass', ['fromRoute']);

        $this->controller->expects($this->any())
            ->method('params')
            ->will($this->returnValue($params));

        $params->expects($this->at(0))
            ->method('fromRoute')
            ->with('type')
            ->will($this->returnValue('invalid'));

        try {
            $this->controller->taskFilterAction();
        } catch (\Exception $e) {
            $this->assertEquals('Invalid task filter key: invalid', $e->getMessage());
            return;
        }

        $this->fail('Expected exception not raised');
    }

    public function testTaskFilterActionValidType()
    {
        $params = $this->getMock('\stdClass', ['fromRoute']);

        $this->controller->expects($this->any())
            ->method('params')
            ->will($this->returnValue($params));

        $params->expects($this->at(0))
            ->method('fromRoute')
            ->with('type')
            ->will($this->returnValue('users'));

        $params->expects($this->at(1))
            ->method('fromRoute')
            ->with('value')
            ->will($this->returnValue('123'));

        $params = [
            'team' => '123',
            'limit' => 100,
            'sort' => 'name'
        ];

        $response = [
            'Results' => [
                [
                    'id' => 123,
                    'name' => 'foo'
                ],
                [
                    'id' => 456,
                    'name' => 'bar'
                ]
            ]
        ];
        $this->controller->expects($this->at(2))
            ->method('makeRestCall')
            ->with('User', 'GET', $params)
            ->will($this->returnValue($response));

        $expectedData = [
            [
                'value' => '',
                'label' => 'All'
            ], [
                'value' => 123,
                'label' => 'foo'
            ], [
                'value' => 456,
                'label' => 'bar'
            ]
        ];

        $json = $this->controller->taskFilterAction();
        $this->assertEquals($expectedData, $json->getVariables());
    }
}
