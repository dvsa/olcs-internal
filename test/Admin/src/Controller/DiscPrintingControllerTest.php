<?php
/**
 * Disc Printing Controller test
 *
 * @author Alex Peshkov <alex.peshkov@valtech.co.uk>
 */

namespace AdminTest\Controller;

/**
 * Disc Printing Controller test
 *
 * @author Alex Peshkov <alex.peshkov@valtech.co.uk>
 */
class DiscPrintingControllerTest extends AbstractAdminControllerTest
{
    protected $controllerName = '\Admin\Controller\DiscPrintingController';

    protected $isPost = false;

    protected $isXmlHttpRequest = false;

    protected $mockMethods = ['params', 'loadScripts', 'makeRestCall'];

    protected $allParams = [
        'niFlag' => 'N',
        'operatorType' => 'lcat_gv',
        'licenceType' => 'ltyp_r',
        'startNumberEnterend' => 2,
        'discSequence' => 1,
        'discPrefix' => 'OK',
        'isSuccessfull' => null,
        'endNumber' => 3
    ];
    protected $discPrefixes = [
        1 => 'OK',
        2 => 'OB'
    ];

    protected $discsToPrint = [
        ['id' => 1, 'version' => 1],
        ['id' => 2, 'version' => 1]
    ];

    protected $formPost = [
        'operator-location' => [
            'niFlag' => 'N'
        ],
        'operator-type' => [
            'goodsOrPsv' => 'lcat_gv'
        ],
        'licence-type' => [
            'licenceType' => 'ltyp_r'
        ],
        'prefix' => [
            'discSequence' => 1
        ],
        'discs-numbering' => [
            'startNumber' => 2,
            'endNumber' => 3,
            'totalPages' => 1,
            'originalEndNumber' => 3
        ]
    ];

    protected $needMockGetPost = true;

    protected $needAnException = false;

    /**
     * Set up
     */
    public function setUpAction($params = null, $data = [])
    {
        parent::setUpAction('index', null, $data);

        $mockUri = $this->getMock('\StdClass', ['getPath']);
        $mockUri->expects($this->any())
                ->method('getPath')
                ->will($this->returnValue('/'));

        $mockRequestMethods = ['getUri', 'isPost', 'isXmlHttpRequest'];
        if ($this->needMockGetPost) {
            $mockGetPost = $this->getMock('\Zend\Stdlib\Parameters');
            $mockGetPost->expects($this->any())
                    ->method('toArray')
                    ->will($this->returnValue($params ? $params : $this->allParams));
            $mockRequestMethods[] = 'getPost';
        }
        $mockRequest = $this->getMock('\Zend\Http\Request', $mockRequestMethods);
        $mockRequest->expects($this->any())
                ->method('getUri')
                ->will($this->returnValue($mockUri));

        $mockRequest->expects($this->any())
                ->method('isPost')
                ->will($this->returnValue($this->isPost));

        if ($this->needMockGetPost) {
            $mockRequest->expects($this->any())
                    ->method('getPost')
                    ->will($this->returnValue($mockGetPost));
        }

        $mockRequest->expects($this->any())
                ->method('isXmlHttpRequest')
                ->will($this->returnValue($this->isXmlHttpRequest));

        $this->controller->expects($this->any())
            ->method('getRequest')
            ->will($this->returnValue($mockRequest));

        $mockDiscSequence = $this->getMock(
            '\StdClass',
            ['fetchListOptions', 'getDiscNumber', 'setNewStartNumber', 'getDiscPrefix']
        );
        $mockDiscSequence->expects($this->any())
                ->method('fetchListOptions')
                ->will($this->returnValue($this->discPrefixes));

        $mockDiscSequence->expects($this->any())
                ->method('getDiscNumber')
                ->will($this->returnValue(2));

        $mockDiscSequence->expects($this->any())
                ->method('getDiscPrefix')
                ->will($this->returnValue('OK'));

        $mockGoodsDisc = $this->getMock(
            '\StdClass',
            ['getDiscsToPrint', 'setIsPrintingOffAndAssignNumber', 'setIsPrintingOff', 'setIsPrintingOn']
        );
        $mockGoodsDisc->expects($this->any())
                ->method('getDiscsToPrint')
                ->will($this->returnValue($this->discsToPrint));

        if ($this->needAnException) {
            $mockGoodsDisc->expects($this->any())
                 ->method('setIsPrintingOff')
                 ->will($this->throwException(new \Exception));
        }
        if (!empty($data)) {
            $post = new \Zend\Stdlib\Parameters($data);
            $this->controller->getRequest()->setMethod('post')->setPost($post);
        }

        $this->serviceManager->setService('Admin\Service\Data\DiscSequence', $mockDiscSequence);
        $this->serviceManager->setService('Admin\Service\Data\GoodsDisc', $mockGoodsDisc);

    }

    /**
     * Test index action
     * @group discPrinting
     */
    public function testIndexAction()
    {

        $this->setUpAction();

        $mockParams = $this->getMock('\StdClass', ['fromRoute']);
        $mockParams->expects($this->once())
            ->method('fromRoute')
            ->will($this->returnValue(null));

        $this->controller->expects($this->once())
            ->method('params')
            ->will($this->returnValue($mockParams));

        $response = $this->controller->indexAction();

        // Make sure we get a view not a response
        $this->assertInstanceOf('Zend\View\Model\ViewModel', $response);
    }

    /**
     * Test index action with POST
     * @group discPrinting
     */
    public function testIndexActionWithPost()
    {

        $this->isPost = true;
        $this->needMockGetPost = false;

        $this->setUpAction(null, $this->formPost);

        $this->controller->setEnabledCsrf(false);

        $restResult = [
            'Disc_List' => [
                ['foo' => 'bar']
            ]
        ];
        $this->controller->expects($this->once())
            ->method('makeRestCall')
            ->with('BookmarkSearch')
            ->willReturn($restResult);

        $documentMock = $this->getMock(
            '\stdClass',
            ['getBookmarkQueries', 'populateBookmarks']
        );

        $this->controller->expects($this->any())
            ->method('getLoggedInUser')
            ->will($this->returnValue(123));

        $file = new \Dvsa\Jackrabbit\Data\Object\File();
        $file->setMimeType('application/rtf');
        $file->setContent('dummy content');

        $contentStoreMock = $this->getMock('\stdClass', ['read']);
        $contentStoreMock->expects($this->once())
            ->method('read')
            ->with('/templates/GVDiscTemplate.rtf')
            ->will($this->returnValue($file));

        // disc IDs we expect to query against
        $queryData = [1, 2];

        $documentMock->expects($this->once())
            ->method('getBookmarkQueries')
            ->with($file, $queryData);

        $resultData = array(
            'Disc_List' => array(
                array(
                    'foo' => 'bar',
                    'discNo' => 2
                )
            )
        );

        $documentMock->expects($this->once())
            ->method('populateBookmarks')
            ->with($file, $resultData)
            ->will($this->returnValue('replaced content'));

        $fileStoreMock = $this->getMock(
            '\stdClass',
            [
                'setFile',
                'upload'
            ]
        );

        $mockFileUploader = $this->getMock('\stdClass', ['getUploader']);
        $mockFileUploader->expects($this->any())
            ->method('getUploader')
            ->will($this->returnValue($fileStoreMock));

        $fileData = ['content' => 'replaced content'];
        $fileStoreMock->expects($this->once())
            ->method('setFile')
            ->with($fileData);

        $this->serviceManager->setService('Document', $documentMock);
        $this->serviceManager->setService('ContentStore', $contentStoreMock);
        $this->serviceManager->setService('FileUploader', $mockFileUploader);

        $mockParams = $this->getMock('\StdClass', ['fromRoute']);
        $mockParams->expects($this->once())
            ->method('fromRoute')
            ->will($this->returnValue(null));

        $this->controller->expects($this->once())
            ->method('params')
            ->will($this->returnValue($mockParams));

        $response = $this->controller->indexAction();

        // Make sure we get a view not a response
        $this->assertInstanceOf('Zend\View\Model\ViewModel', $response);
    }

    /**
     * Test disc prefixes list action
     * @group discPrinting
     */
    public function testDiscPrefixesListAction()
    {
        $this->setUpAction();
        $response = $this->controller->discPrefixesListAction();
        $this->assertInstanceOf('Zend\View\Model\JsonModel', $response);
        $result = json_decode($response->serialize(), true);
        $this->assertEquals(is_array($result), true);
        $this->assertEquals(count($result), 2);
        $this->assertEquals($result, [['value' => 1, 'label' => 'OK'], ['value' => 2, 'label' => 'OB']]);
    }

    /**
     * Test disc prefixes list action with bad params
     * @group discPrinting
     */
    public function testDiscPrefixesListActionWithBadParams()
    {
        $this->allParams['niFlag'] = 'N';
        unset($this->allParams['operatorType']);
        $this->setUpAction($this->allParams);

        $response = $this->controller->discPrefixesListAction();
        $this->assertInstanceOf('Zend\View\Model\JsonModel', $response);
        $result = json_decode($response->serialize(), true);
        $this->assertEquals(is_array($result), true);
        $this->assertEquals(count($result), 0);
    }

    /**
     * Test disc numbering action with bad params
     * @group discPrinting
     */
    public function testDiscNumberingActionWithBadParams()
    {
        unset($this->allParams['operatorType']);
        $this->setUpAction($this->allParams);
        $response = $this->controller->discNumberingAction();
        $this->assertInstanceOf('Zend\View\Model\JsonModel', $response);
        $result = json_decode($response->serialize(), true);
        $this->assertEquals(is_array($result), true);
        $this->assertEquals(count($result), 0);

    }

    /**
     * Test disc numbering action
     * @group discPrinting
     */
    public function testDiscNumberingAction()
    {
        $expectedNumbering = [
            'startNumber' => 2,
            'discsToPrint' => 2,
            'endNumber' => 7,
            'originalEndNumber' => 4,
            'totalPages' => 1
        ];
        $this->setUpAction();
        $response = $this->controller->discNumberingAction();
        $this->assertInstanceOf('Zend\View\Model\JsonModel', $response);
        $result = json_decode($response->serialize(), true);
        $this->assertEquals(is_array($result), true);
        $this->assertEquals($result, $expectedNumbering);
    }

    /**
     * Test disc numbering action with increasing start number
     * @group discPrinting
     */
    public function testDiscNumberingActionWithIncreasingStartNumber()
    {
        $expectedNumbering = [
            'startNumber' => 3,
            'discsToPrint' => 2,
            'endNumber' => 8,
            'originalEndNumber' => 4,
            'totalPages' => 1
        ];
        $this->allParams['startNumberEntered'] = 3;
        $this->setUpAction($this->allParams);
        $response = $this->controller->discNumberingAction();
        $this->assertInstanceOf('Zend\View\Model\JsonModel', $response);
        $result = json_decode($response->serialize(), true);
        $this->assertEquals(is_array($result), true);
        $this->assertEquals($result, $expectedNumbering);
    }

    /**
     * Test disc numbering action with decreasing start number
     * @group discPrinting
     */
    public function testDiscNumberingActionWithDecreasingStartNumber()
    {
        $this->allParams['startNumberEntered'] = 1;
        $this->setUpAction($this->allParams);
        $response = $this->controller->discNumberingAction();
        $this->assertInstanceOf('Zend\View\Model\JsonModel', $response);
        $result = json_decode($response->serialize(), true);
        $this->assertEquals(is_array($result), true);
        $this->assertEquals(isset($result['error']), true);
        $this->assertEquals($result['error'], 'Decreasing the start number is not permitted');
    }

    /**
     * Test confirm disc printing
     * @group discPrinting
     */
    public function testConfirmDiscPrintingAction()
    {
        $this->allParams['isSuccessfull'] = true;
        $this->setUpAction($this->allParams);
        $response = $this->controller->confirmDiscPrintingAction();
        $this->assertInstanceOf('Zend\View\Model\JsonModel', $response);
        $result = json_decode($response->serialize(), true);
        $this->assertEquals(is_array($result), true);
        $this->assertEquals(isset($result['status']), false);
    }

    /**
     * Test confirm disc printing unsuccessfull
     * @group discPrinting
     */
    public function testConfirmDiscPrintingActionUnsuccessfull()
    {
        $this->allParams['isSuccessfull'] = false;
        $this->setUpAction($this->allParams);
        $response = $this->controller->confirmDiscPrintingAction();
        $this->assertInstanceOf('Zend\View\Model\JsonModel', $response);
        $result = json_decode($response->serialize(), true);
        $this->assertEquals(is_array($result), true);
        $this->assertEquals(isset($result['status']), false);
    }

    /**
     * Test confirm disc printing with exception
     * @group discPrinting
     */
    public function testConfirmDiscPrintingActionWithException()
    {
        $this->needAnException = true;
        $this->setUpAction();
        $response = $this->controller->confirmDiscPrintingAction();
        $this->assertInstanceOf('Zend\View\Model\JsonModel', $response);
        $result = json_decode($response->serialize(), true);
        $this->assertEquals(is_array($result), true);
        $this->assertEquals($result['status'], 500);
    }

    /**
     * Test index action with POST with bad params
     * @group discPrinting
     */
    public function testIndexActionWithPostWithBadParams()
    {

        $this->isPost = true;
        $this->needMockGetPost = false;

        $this->setUpAction(null, []);

        $this->controller->setEnabledCsrf(false);

        $mockParams = $this->getMock('\StdClass', ['fromRoute']);
        $mockParams->expects($this->once())
            ->method('fromRoute')
            ->will($this->returnValue(null));

        $this->controller->expects($this->once())
            ->method('params')
            ->will($this->returnValue($mockParams));

        $response = $this->controller->indexAction();

        // Make sure we get a view not a response
        $this->assertInstanceOf('Zend\View\Model\ViewModel', $response);
    }
}
