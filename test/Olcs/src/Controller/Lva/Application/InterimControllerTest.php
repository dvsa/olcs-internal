<?php

/**
 * Interim Controller Test
 *
 * @author Alex Peshkov <alex.peshkov@valtech.co.uk>
 */
namespace OlcsTest\Controller\Lva\Application;

use OlcsTest\Bootstrap;
use Mockery as m;
use Common\Service\Entity\ApplicationEntityService;
use Common\Service\Data\FeeTypeDataService;
use Mockery\Adapter\Phpunit\MockeryTestCase;
use Common\Service\Entity\FeeEntityService;
use Zend\View\Model\ViewModel;
use Common\Service\Entity\CommunityLicEntityService;

/**
 * Interim Controller Test
 *
 * @author Alex Peshkov <alex.peshkov@valtech.co.uk>
 */
class InterimControllerTest extends MockeryTestCase
{
    protected $mockForm;

    protected $mockFormHelper;

    protected $mockApplicationService;

    protected $mockRequest;

    protected $sm;

    protected $sut;

    protected $interimData = [
        'operatingCentres' => 'operatingCentres',
        'licenceVehicles' => 'licenceVehicles',
        'id' => 1,
        'version' => 1,
        'interimReason' => 'reason',
        'interimStart' => '2014/01/01',
        'interimEnd' => '2015/01/01',
        'interimAuthVehicles' => 10,
        'interimAuthTrailers' => 20,
        'interimStatus' => ['id' => ApplicationEntityService::INTERIM_STATUS_REQUESTED],
        'licence' => ['id' => 1]
    ];

    public function setUp()
    {
        $this->sut = m::mock('\Olcs\Controller\Lva\Application\InterimController')
            ->makePartial()
            ->shouldAllowMockingProtectedMethods();

        $this->sm = Bootstrap::getServiceManager();
        $this->sut->setServiceLocator($this->sm);

    }

    /**
     * Test index action
     *
     * @group interimController
     */
    public function testIndexAction()
    {
        $applicationId = 1;
        $this->mockGetForm($applicationId);

        $data = [
            'data' => $this->interimData,
            'requested' => [
                'interimRequested' => 'Y'
            ]
        ];

        $data['data']['interimStatus'] = $data['data']['interimStatus']['id'];
        unset($data['data']['operatingCentres']);
        unset($data['data']['licenceVehicles']);
        unset($data['data']['licence']);

        $this->mockRequest = m::mock()
            ->shouldReceive('isPost')
            ->andReturn(false)
            ->getMock();

        $this->mockForm
            ->shouldReceive('setData')
            ->with($data)
            ->getMock();

        $this->sut
            ->shouldReceive('isButtonPressed')
            ->with('cancel')
            ->andReturn(false)
            ->shouldReceive('getRequest')
            ->andReturn($this->mockRequest)
            ->shouldReceive('render')
            ->with('interim', $this->mockForm)
            ->andReturn('view');

        $this->sut
            ->shouldReceive('isButtonPressed')
            ->with('reprint')
            ->andReturn(false);

        $this->sut
            ->shouldReceive('alterForm')
            ->with($this->mockForm, $this->interimData)
            ->andReturn($this->mockForm);

        $mockScript = m::mock()
            ->shouldReceive('loadFiles')
            ->with(['forms/interim'])
            ->getMock();
        $this->sm->setService('Script', $mockScript);

        $this->assertEquals('view', $this->sut->indexAction());
    }

    /**
     * Mock get form
     *
     */
    protected function mockGetForm($applicationId)
    {
        $this->mockForm = m::mock('Zend\Form\Form')
            ->shouldReceive('get')
            ->with('operatingCentres')
            ->andReturn('operatingCentresElement')
            ->once()
            ->shouldReceive('get')
            ->with('vehicles')
            ->andReturn('vehiclesElement')
            ->once()
            ->getMock();

        $this->mockFormHelper = m::mock()
            ->shouldReceive('createForm')
            ->with('Interim')
            ->andReturn($this->mockForm)
            ->shouldReceive('populateFormTable')
            ->with('operatingCentresElement', 'ocTable', 'operatingCentres')
            ->once()
            ->shouldReceive('populateFormTable')
            ->with('vehiclesElement', 'vehiclesTable', 'vehicles')
            ->once()
            ->getMock();
        $this->sm->setService('Helper\Form', $this->mockFormHelper);

        $this->mockApplicationService = m::mock()
            ->shouldReceive('getDataForInterim')
            ->with($applicationId)
            ->andReturn($this->interimData)
            ->getMock();
        $this->sm->setService('Entity\Application', $this->mockApplicationService);

        $mockPluginManager = m::mock()
            ->shouldReceive('get')
            ->with('url')
            ->andReturn('url')
            ->getMock();

        $mockTable = m::mock()
            ->shouldReceive('buildTable')
            ->with('interim.operatingcentres', 'operatingCentres', ['url' => 'url'], false)
            ->andReturn('ocTable')
            ->once()
            ->shouldReceive('buildTable')
            ->with('interim.vehicles', 'licenceVehicles', ['url' => 'url'], false)
            ->andReturn('vehiclesTable')
            ->once()
            ->getMock();
        $this->sm->setService('Table', $mockTable);

        $this->sut
            ->shouldReceive('getIdentifier')
            ->andReturn($applicationId)
            ->shouldReceive('getPluginManager')
            ->andReturn($mockPluginManager)
            ->getMock();

        return $this->mockForm;
    }

    /**
     * Test index action with cancel button pressed
     *
     * @group interimController
     */
    public function testIndexActionWithCancel()
    {
        $this->sut
            ->shouldReceive('isButtonPressed')
            ->with('cancel')
            ->andReturn(true);

        $this->mockRedirectToOverview();

        $this->assertInstanceOf('\Zend\Http\PhpEnvironment\Response', $this->sut->indexAction());
    }

    /**
     * Test index action set interim
     *
     * @group interimController
     */
    public function testIndexActionSetInterim()
    {
        $applicationId = 1;
        $data = [
            'data' => $this->interimData,
            'requested' => [
                'interimRequested' => 'Y'
            ]
        ];

        $data['data']['interimStatus'] = $data['data']['interimStatus']['id'];
        unset($data['data']['operatingCentres']);
        unset($data['data']['licenceVehicles']);
        unset($data['data']['licence']);
        $data['operatingCentres'] = ['id' => [1, 2]];
        $data['licenceVehicles'] = ['id' => [1, 2]];

        $this->mockRequest = m::mock()
            ->shouldReceive('isPost')
            ->andReturn(true)
            ->shouldReceive('getPost')
            ->andReturn($data)
            ->getMock();

        $this->mockGetForm($applicationId);

        $this->mockApplicationService
            ->shouldReceive('saveInterimData')
            ->with($data, true)
            ->getMock();

        $this->mockForm
            ->shouldReceive('setData')
            ->with($data)
            ->shouldReceive('getData')
            ->andReturn($data)
            ->shouldReceive('get')
            ->with('data')
            ->once()
            ->andReturn(
                m::mock()
                ->shouldReceive('get')
                ->with('interimStatus')
                ->once()
                ->andReturn(
                    m::mock()
                    ->shouldReceive('getValue')
                    ->andReturn(ApplicationEntityService::INTERIM_STATUS_REQUESTED)
                    ->getMock()
                )
                ->getMock()
            )
            ->shouldReceive('get')
            ->with('requested')
            ->once()
            ->andReturn(
                m::mock()
                ->shouldReceive('get')
                ->with('interimRequested')
                ->andReturn(
                    m::mock()
                    ->shouldReceive('getValue')
                    ->andReturn('Y')
                    ->getMock()
                )
                ->getMock()
            )
            ->shouldReceive('isValid')
            ->andReturn(true)
            ->getMock();

        $this->sut
            ->shouldReceive('getRequest')
            ->andReturn($this->mockRequest)
            ->shouldReceive('isButtonPressed')
            ->with('cancel')
            ->andReturn(false)
            ->once()
            ->shouldReceive('isButtonPressed')
            ->with('save')
            ->andReturn(true)
            ->once()
            ->shouldReceive('params')
            ->andReturn(
                m::mock()
                ->shouldReceive('fromPost')
                ->andReturn([])
                ->getMock()
            )
            ->shouldReceive('isButtonPressed')
            ->with('confirm')
            ->andReturn(false)
            ->getMock();

        $this->sut
            ->shouldReceive('isButtonPressed')
            ->with('reprint')
            ->andReturn(false);

        $this->sut
            ->shouldReceive('alterForm')
            ->with($this->mockForm, $this->interimData)
            ->andReturn($this->mockForm);

        $this->mockRedirectToOverview(true);

        $this->mockCreateInterimFee($applicationId);

        $this->assertInstanceOf('\Zend\Http\PhpEnvironment\Response', $this->sut->indexAction());
    }

    /**
     * Test index action to unset interim
     *
     * @group interimController
     */
    public function testIndexActionUnsetInterim()
    {
        $applicationId = 1;
        $data = [
            'data' => $this->interimData,
            'requested' => [
                'interimRequested' => 'Y'
            ]
        ];

        $data['data']['interimStatus'] = $data['data']['interimStatus']['id'];
        unset($data['data']['operatingCentres']);
        unset($data['data']['licenceVehicles']);
        unset($data['data']['licence']);

        $dataToSave = [
            'data' => [
                'id' => 1,
                'version' => 1
            ]
        ];

        $this->mockRequest = m::mock()
            ->shouldReceive('isPost')
            ->andReturn(true)
            ->shouldReceive('getPost')
            ->andReturn($data)
            ->getMock();

        $this->mockGetForm($applicationId);

        $this->mockApplicationService
            ->shouldReceive('saveInterimData')
            ->with($dataToSave, false)
            ->getMock();

        $this->mockForm
            ->shouldReceive('setData')
            ->with($data)
            ->shouldReceive('get')
            ->with('data')
            ->andReturn(
                m::mock()
                ->shouldReceive('get')
                ->with('interimStatus')
                ->once()
                ->andReturn(
                    m::mock()
                    ->shouldReceive('getValue')
                    ->andReturn(ApplicationEntityService::INTERIM_STATUS_REQUESTED)
                    ->getMock()
                )
                ->shouldReceive('get')
                ->with('id')
                ->once()
                ->andReturn(
                    m::mock()
                    ->shouldReceive('getValue')
                    ->andReturn(1)
                    ->getMock()
                )
                ->shouldReceive('get')
                ->with('version')
                ->once()
                ->andReturn(
                    m::mock()
                    ->shouldReceive('getValue')
                    ->andReturn(1)
                    ->getMock()
                )
                ->getMock()
            )
            ->shouldReceive('get')
            ->with('requested')
            ->once()
            ->andReturn(
                m::mock()
                ->shouldReceive('get')
                ->with('interimRequested')
                ->andReturn(
                    m::mock()
                    ->shouldReceive('getValue')
                    ->andReturn('')
                    ->getMock()
                )
                ->getMock()
            )
            ->getMock();

        $this->sut
            ->shouldReceive('getRequest')
            ->andReturn($this->mockRequest)
            ->shouldReceive('isButtonPressed')
            ->with('cancel')
            ->andReturn(false)
            ->once()
            ->shouldReceive('isButtonPressed')
            ->with('save')
            ->andReturn(true)
            ->once()
            ->shouldReceive('params')
            ->andReturn(
                m::mock()
                ->shouldReceive('fromPost')
                ->andReturn([])
                ->getMock()
            )
            ->shouldReceive('isButtonPressed')
            ->with('confirm')
            ->andReturn(false)
            ->getMock();

        $this->sut
            ->shouldReceive('isButtonPressed')
            ->with('reprint')
            ->andReturn(false);

        $this->mockForm->shouldReceive('get')
            ->with('form-actions')
            ->andReturn(
                m::mock()->shouldReceive('remove')
                    ->with('reprint')
                    ->getMock()
            );

        $this->mockRedirectToOverview(true);

        $this->mockCancelInterimFee($applicationId);

        $this->assertInstanceOf('\Zend\Http\PhpEnvironment\Response', $this->sut->indexAction());
    }

    public function testReprintAction()
    {
        $this->sut
            ->shouldReceive('isButtonPressed')
            ->with('reprint')
            ->andReturn(true);

        $this->sm->setService(
            'Helper\Interim',
            m::mock()
                ->shouldReceive('printInterimDocument')
                ->getMock()
        );

        $this->sut->shouldReceive('flashMessenger')
            ->andReturn(
                m::mock()->shouldReceive('addSuccessMessage')
                    ->with('The interim document has been generated')
                    ->getMock()
            );

        $this->mockRedirectToOverview(true);

        $this->assertInstanceOf('\Zend\Http\PhpEnvironment\Response', $this->sut->indexAction());
    }

    /**
     * Mock redirect to overview method
     *
     * @param bool $success
     */
    public function mockRedirectToOverview($success = false)
    {
        $this->sut
            ->shouldReceive('getIdentifierIndex')
            ->andReturn('application')
            ->shouldReceive('getIdentifier')
            ->andReturn(1)
            ->shouldReceive('redirect')
            ->andReturn(
                m::mock('\Zend\Http\PhpEnvironment\Response')
                ->shouldReceive('toRouteAjax')
                ->with(
                    'lva-application',
                    [
                        'application' => 1,
                        'action' => ''
                    ]
                )
                ->andReturnSelf()
                ->getMock()
            )
            ->getMock();

        if ($success) {
            $this->sut
                ->shouldReceive('addSuccessMessage')
                ->with('internal.interim.interim_details_saved')
                ->getMock();
        }
    }

    /**
     * Test index action with invaid form
     *
     * @group interimController
     */
    public function testIndexActionInvalidForm()
    {
        $this->mockRequest = m::mock()
            ->shouldReceive('isPost')
            ->andReturn(true)
            ->shouldReceive('getPost')
            ->andReturn([])
            ->getMock();

        $this->mockForm = $this->mockInterimForm(false);

        $this->sut
            ->shouldReceive('getRequest')
            ->andReturn($this->mockRequest)
            ->shouldReceive('isButtonPressed')
            ->with('cancel')
            ->andReturn(false)
            ->once()
            ->shouldReceive('isButtonPressed')
            ->with('save')
            ->andReturn(true)
            ->once()
            ->shouldReceive('getForm')
            ->andReturn($this->mockForm)
            ->shouldReceive('render')
            ->andReturn('view')
            ->shouldReceive('params')
            ->andReturn(
                m::mock()
                ->shouldReceive('fromPost')
                ->andReturn([])
                ->getMock()
            )
            ->shouldReceive('isButtonPressed')
            ->with('confirm')
            ->andReturn(false)
            ->getMock();

        $mockScript = m::mock()
            ->shouldReceive('loadFiles')
            ->with(['forms/interim'])
            ->getMock();
        $this->sm->setService('Script', $mockScript);

        $mockApplicationService = m::mock();
        $this->sm->setService('Entity\Application', $mockApplicationService);

        $this->assertEquals('view', $this->sut->indexAction());
    }

    /**
     * Mock create interim fee
     *
     */
    public function mockCreateInterimFee($applicationId)
    {
        $this->sut
            ->shouldReceive('getIdentifier')
            ->andReturn($applicationId);

        $mockApplicationProcessingService = m::mock()
            ->shouldReceive('getFeeTypeForApplication')
            ->with($applicationId, FeeTypeDataService::FEE_TYPE_GRANTINT)
            ->andReturn(['id' => 181])
            ->shouldReceive('createFee')
            ->with($applicationId, 1, FeeTypeDataService::FEE_TYPE_GRANTINT)
            ->getMock();
        $this->sm->setService('Processing\Application', $mockApplicationProcessingService);

        $mockFeeService = m::mock()
            ->shouldReceive('getFeeByTypeStatusesAndApplicationId')
            ->with(
                181,
                [FeeEntityService::STATUS_OUTSTANDING, FeeEntityService::STATUS_WAIVE_RECOMMENDED],
                $applicationId
            )
            ->andReturn(null)
            ->getMock();
        $this->sm->setService('Entity\Fee', $mockFeeService);

    }

    /**
     * Mock cancel interim fee
     *
     */
    public function mockCancelInterimFee($applicationId)
    {
        $this->sut
            ->shouldReceive('getIdentifier')
            ->andReturn($applicationId);

        $mockApplicationProcessingService = m::mock()
            ->shouldReceive('getFeeTypeForApplication')
            ->with($applicationId, FeeTypeDataService::FEE_TYPE_GRANTINT)
            ->andReturn(['id' => 181])
            ->getMock();
        $this->sm->setService('Processing\Application', $mockApplicationProcessingService);

        $mockFeeService = m::mock()
            ->shouldReceive('getFeeByTypeStatusesAndApplicationId')
            ->with(
                181,
                [FeeEntityService::STATUS_OUTSTANDING, FeeEntityService::STATUS_WAIVE_RECOMMENDED],
                $applicationId
            )
            ->andReturn([['id' => 1]])
            ->shouldReceive('cancelByIds')
            ->with([1])
            ->getMock();
        $this->sm->setService('Entity\Fee', $mockFeeService);
    }

    /**
     * Test index action with interim status in-forced
     *
     * @group interimController
     */
    public function testIndexActionWithStatusInForced()
    {
        $applicationId = 1;
        $this->interimData['interimStatus'] = ['id' => ApplicationEntityService::INTERIM_STATUS_INFORCE];
        $this->mockGetForm($applicationId);

        $data = [
            'data' => $this->interimData,
            'requested' => [
                'interimRequested' => 'Y'
            ]
        ];

        $data['data']['interimStatus'] = $data['data']['interimStatus']['id'];
        unset($data['data']['operatingCentres']);
        unset($data['data']['licenceVehicles']);
        unset($data['data']['licence']);

        $this->mockRequest = m::mock()
            ->shouldReceive('isPost')
            ->andReturn(false)
            ->getMock();

        $this->mockForm
            ->shouldReceive('setData')
            ->with($data)
            ->getMock();

        $this->mockFormHelper
            ->shouldReceive('remove')
            ->with($this->mockForm, 'form-actions->grant')
            ->shouldReceive('remove')
            ->with($this->mockForm, 'form-actions->refuse')
            ->getMock();

        $this->sut
            ->shouldReceive('isButtonPressed')
            ->with('cancel')
            ->andReturn(false)
            ->shouldReceive('getRequest')
            ->andReturn($this->mockRequest)
            ->shouldReceive('render')
            ->with('interim', $this->mockForm)
            ->andReturn('view');

        $mockScript = m::mock()
            ->shouldReceive('loadFiles')
            ->with(['forms/interim'])
            ->getMock();
        $this->sm->setService('Script', $mockScript);

        $this->assertEquals('view', $this->sut->indexAction());
    }

    /**
     * Test index action with display confirm modal for grant
     *
     * @group interimController
     */
    public function testIndexActionDisplayConfirmModalForGrant()
    {
        $this->mockForm = $this->mockInterimForm()
            ->shouldReceive('getData')
            ->andReturn('formData')
            ->getMock();

        $this->sut
            ->shouldReceive('isButtonPressed')
            ->with('cancel')
            ->andReturn(false)
            ->shouldReceive('getInterimForm')
            ->andReturn($this->mockForm)
            ->shouldReceive('getRequest')
            ->andReturn(
                m::mock()
                ->shouldReceive('isPost')
                ->andReturn(true)
                ->shouldReceive('getPost')
                ->andReturn([])
                ->getMock()
            )
            ->shouldReceive('params')
            ->andReturn(
                m::mock()
                ->shouldReceive('fromPost')
                ->andReturn(['custom' => 'grant'])
                ->getMock()
            )
            ->shouldReceive('isButtonPressed')
            ->with('confirm')
            ->andReturn(false)
            ->shouldReceive('isButtonPressed')
            ->with('save')
            ->once()
            ->andReturn(false)
            ->shouldReceive('isButtonPressed')
            ->with('grant')
            ->once()
            ->andReturn(true)
            ->shouldReceive('getExistingFees')
            ->andReturn(false)
            ->shouldReceive('confirm')
            ->with('message', true, 'grant')
            ->andReturn(new ViewModel());

        $this->sm->setService(
            'translator',
            m::mock()
            ->shouldReceive('translate')
            ->with('internal.interim.form.grant_confirm')
            ->andReturn('message')
            ->getMock()
        );

        $this->sm->setService(
            'Entity\Application',
            m::mock()
            ->shouldReceive('saveInterimData')
            ->with('formData', true)
            ->getMock()
        );

        $this->assertInstanceOf('Zend\View\Model\ViewModel', $this->sut->indexAction());
    }

    /**
     * Test index action with display confirm modal for grant
     *
     * @group interimController
     */
    public function testGetConfirmForm()
    {

        $this->sm->setService(
            'Helper\Form',
            m::mock()
            ->shouldReceive('createForm')
            ->with('Confirm')
            ->andReturn('form')
            ->getMock()
        );

        $this->assertEquals('form', $this->sut->getForm('Confirm'));
    }

    /**
     * Test granting interim where form is not valid
     *
     * @group interimController
     */
    public function testFormNotValidBeforeGrant()
    {
        $this->mockForm = $this->mockInterimForm(false);

        $this->sut
            ->shouldReceive('isButtonPressed')
            ->with('cancel')
            ->andReturn(false)
            ->shouldReceive('getInterimForm')
            ->andReturn($this->mockForm)
            ->shouldReceive('getRequest')
            ->andReturn(
                m::mock()
                ->shouldReceive('isPost')
                ->andReturn(true)
                ->shouldReceive('getPost')
                ->andReturn([])
                ->getMock()
            )
            ->shouldReceive('params')
            ->andReturn(
                m::mock()
                ->shouldReceive('fromPost')
                ->andReturn(['custom' => 'grant'])
                ->getMock()
            )
            ->shouldReceive('isButtonPressed')
            ->with('confirm')
            ->andReturn(false)
            ->shouldReceive('isButtonPressed')
            ->with('save')
            ->once()
            ->andReturn(false)
            ->shouldReceive('isButtonPressed')
            ->with('grant')
            ->once()
            ->andReturn(true)
            ->shouldReceive('getExistingFees')
            ->andReturn(false)
            ->shouldReceive('render')
            ->with('interim', $this->mockForm)
            ->andReturn(new ViewModel());

        $this->sm->setService(
            'translator',
            m::mock()
            ->shouldReceive('translate')
            ->with('internal.interim.form.grant_confirm')
            ->andReturn('message')
            ->getMock()
        );

        $this->sm->setService(
            'Entity\Application',
            m::mock()
        );

        $this->sm->setService(
            'Script',
            m::mock()
            ->shouldReceive('loadFiles')
            ->with(['forms/interim'])
            ->getMock()
        );

        $this->assertInstanceOf('Zend\View\Model\ViewModel', $this->sut->indexAction());
    }

    /**
     * Test granting interim where fees exists
     *
     * @group interimController
     */
    public function testGrantInterimFeesExists()
    {
        $this->mockForm = $this->mockInterimForm();

        $this->sut
            ->shouldReceive('isButtonPressed')
            ->with('cancel')
            ->andReturn(false)
            ->shouldReceive('getInterimForm')
            ->andReturn($this->mockForm)
            ->shouldReceive('getRequest')
            ->andReturn(
                m::mock()
                ->shouldReceive('isPost')
                ->andReturn(true)
                ->shouldReceive('getPost')
                ->andReturn([])
                ->getMock()
            )
            ->shouldReceive('params')
            ->andReturn(
                m::mock()
                ->shouldReceive('fromPost')
                ->andReturn(['custom' => 'grant'])
                ->getMock()
            )
            ->shouldReceive('isButtonPressed')
            ->with('confirm')
            ->andReturn(false)
            ->shouldReceive('isButtonPressed')
            ->with('save')
            ->once()
            ->andReturn(false)
            ->shouldReceive('isButtonPressed')
            ->with('grant')
            ->once()
            ->andReturn(true)
            ->shouldReceive('getExistingFees')
            ->andReturn(true)
            ->shouldReceive('addErrorMessage')
            ->with('internal.interim.form.grant_not_allowed')
            ->shouldReceive('redirect')
            ->andReturn(
                m::mock()
                ->shouldReceive('refreshAjax')
                ->andReturn('redirect')
                ->getMock()
            );

        $this->sm->setService(
            'translator',
            m::mock()
            ->shouldReceive('translate')
            ->with('internal.interim.form.grant_confirm')
            ->andReturn('message')
            ->getMock()
        );

        $this->sm->setService(
            'Entity\Application',
            m::mock()
        );

        $this->assertEquals('redirect', $this->sut->indexAction());
    }

    /**
     * Test process interim granting
     *
     * @group interimController
     */
    public function testIndexActionWithProcessInterimGranting()
    {
        $this->mockForm = $this->mockInterimForm();

        $this->sut
            ->shouldReceive('isButtonPressed')
            ->with('cancel')
            ->andReturn(false)
            ->shouldReceive('getInterimForm')
            ->andReturn($this->mockForm)
            ->shouldReceive('getRequest')
            ->andReturn(
                m::mock()
                ->shouldReceive('isPost')
                ->andReturn(true)
                ->shouldReceive('getPost')
                ->andReturn([])
                ->getMock()
            )
            ->shouldReceive('params')
            ->andReturn(
                m::mock()
                ->shouldReceive('fromPost')
                ->andReturn(['custom' => 'grant'])
                ->getMock()
            )
            ->shouldReceive('isButtonPressed')
            ->with('confirm')
            ->once()
            ->andReturn(true)
            ->shouldReceive('confirm')
            ->with('message', true, 'grant')
            ->andReturn(true)
            ->shouldReceive('isButtonPressed')
            ->with('cancel')
            ->andReturn(false)
            ->shouldReceive('getInterimData')
            ->andReturn('interimData')
            ->shouldReceive('addSuccessMessage')
            ->with('internal.interim.form.interim_granted')
            ->shouldReceive('getIdentifier')
            ->andReturn(1)
            ->shouldReceive('redirectToOverview')
            ->andReturn('redirect');

        $this->sm->setService(
            'translator',
            m::mock()
            ->shouldReceive('translate')
            ->with('internal.interim.form.grant_confirm')
            ->andReturn('message')
            ->getMock()
        );

        $this->sm->setService(
            'Helper\Interim',
            m::mock()
            ->shouldReceive('grantInterim')
            ->with(1)
            ->getMock()
        );

        $this->assertEquals('redirect', $this->sut->indexAction());
    }

    /**
     * Test process interim granting with cancel
     *
     * @group interimController
     */
    public function testIndexActionWithProcessInterimGrantingWithCancel()
    {
        $this->mockForm = $this->mockInterimForm();

        $this->sut
            ->shouldReceive('isButtonPressed')
            ->with('cancel')
            ->andReturn(false)
            ->once()
            ->shouldReceive('getInterimForm')
            ->andReturn($this->mockForm)
            ->shouldReceive('getRequest')
            ->andReturn(
                m::mock()
                ->shouldReceive('isPost')
                ->andReturn(true)
                ->shouldReceive('getPost')
                ->andReturn([])
                ->getMock()
            )
            ->shouldReceive('params')
            ->andReturn(
                m::mock()
                ->shouldReceive('fromPost')
                ->andReturn(['custom' => 'grant'])
                ->getMock()
            )
            ->shouldReceive('isButtonPressed')
            ->with('confirm')
            ->once()
            ->andReturn(true)
            ->shouldReceive('isButtonPressed')
            ->with('cancel')
            ->andReturn(true)
            ->once()
            ->shouldReceive('getInterimData')
            ->andReturn('interimData')
            ->shouldReceive('redirect')
            ->andReturn(
                m::mock()
                ->shouldReceive('toRouteAjax')
                ->andReturn('redirect')
                ->getMock()
            );

        $this->assertEquals('redirect', $this->sut->indexAction());
    }

    /**
     * Test process interim refusing
     *
     * @group interimController
     */
    public function testIndexActionWithProcessInterimRefusing()
    {
        $this->mockForm = $this->mockInterimForm();

        $this->sut
            ->shouldReceive('isButtonPressed')
            ->with('cancel')
            ->andReturn(false)
            ->shouldReceive('getInterimForm')
            ->andReturn($this->mockForm)
            ->shouldReceive('getRequest')
            ->andReturn(
                m::mock()
                ->shouldReceive('isPost')
                ->andReturn(true)
                ->shouldReceive('getPost')
                ->andReturn([])
                ->getMock()
            )
            ->shouldReceive('params')
            ->andReturn(
                m::mock()
                ->shouldReceive('fromPost')
                ->andReturn(['custom' => 'refuse'])
                ->getMock()
            )
            ->shouldReceive('isButtonPressed')
            ->with('confirm')
            ->andReturn(true)
            ->shouldReceive('confirm')
            ->with('message', true, 'refuse')
            ->andReturn(true)
            ->shouldReceive('getInterimData')
            ->andReturn('interimData')
            ->shouldReceive('addSuccessMessage')
            ->with('internal.interim.form.interim_refused')
            ->shouldReceive('getIdentifier')
            ->andReturn(1)
            ->shouldReceive('redirectToOverview')
            ->andReturn('redirect');

        $this->sm->setService(
            'translator',
            m::mock()
            ->shouldReceive('translate')
            ->with('internal.interim.form.refuse_confirm')
            ->andReturn('message')
            ->getMock()
        );

        $this->sm->setService(
            'Helper\Interim',
            m::mock()
            ->shouldReceive('refuseInterim')
            ->with(1)
            ->getMock()
        );

        $this->assertEquals('redirect', $this->sut->indexAction());
    }

    /**
     * Test process interim refusing with cancel
     *
     * @group interimController
     */
    public function testIndexActionWithProcessInterimRefusingWithCancel()
    {
        $this->mockForm = $this->mockInterimForm();

        $this->sut
            ->shouldReceive('isButtonPressed')
            ->with('cancel')
            ->andReturn(false)
            ->once()
            ->shouldReceive('getInterimForm')
            ->andReturn($this->mockForm)
            ->shouldReceive('getRequest')
            ->andReturn(
                m::mock()
                ->shouldReceive('isPost')
                ->andReturn(true)
                ->shouldReceive('getPost')
                ->andReturn([])
                ->getMock()
            )
            ->shouldReceive('params')
            ->andReturn(
                m::mock()
                ->shouldReceive('fromPost')
                ->andReturn(['custom' => 'refuse'])
                ->getMock()
            )
            ->shouldReceive('isButtonPressed')
            ->with('confirm')
            ->andReturn(true)
            ->shouldReceive('isButtonPressed')
            ->with('cancel')
            ->andReturn(true)
            ->once()
            ->shouldReceive('getInterimData')
            ->andReturn('interimData')
            ->shouldReceive('redirect')
            ->andReturn(
                m::mock()
                ->shouldReceive('toRouteAjax')
                ->andReturn('redirect')
                ->getMock()
            );

        $this->assertEquals('redirect', $this->sut->indexAction());
    }

    /**
     * Mock interim form
     * 
     * @param bool $isValid
     */
    protected function mockInterimForm($isValid = true)
    {
        return m::mock('Zend\Form\Form')
            ->shouldReceive('setData')
            ->with([])
            ->shouldReceive('get')
            ->with('data')
            ->andReturn(
                m::mock()
                ->shouldReceive('get')
                ->with('interimStatus')
                ->andReturn(
                    m::mock()
                    ->shouldReceive('getValue')
                    ->andReturn(ApplicationEntityService::INTERIM_STATUS_REQUESTED)
                    ->getMock()
                )
                ->getMock()
            )
            ->once()
            ->shouldReceive('get')
            ->with('requested')
            ->andReturn(
                m::mock()
                ->shouldReceive('get')
                ->with('interimRequested')
                ->andReturn(
                    m::mock()
                    ->shouldReceive('getValue')
                    ->andReturn('Y')
                    ->getMock()
                )
                ->getMock()
            )
            ->shouldReceive('isValid')
            ->andReturn($isValid)
            ->getMock();
    }

    /**
     * Test index action with display confirm modal for grant
     *
     * @group interimController
     */
    public function testIndexActionDisplayConfirmModalForRefuse()
    {
        $this->mockForm = $this->mockInterimForm()
            ->shouldReceive('getData')
            ->andReturn('formData')
            ->getMock();

        $this->sut
            ->shouldReceive('isButtonPressed')
            ->with('cancel')
            ->andReturn(false)
            ->shouldReceive('getInterimForm')
            ->andReturn($this->mockForm)
            ->shouldReceive('getRequest')
            ->andReturn(
                m::mock()
                ->shouldReceive('isPost')
                ->andReturn(true)
                ->shouldReceive('getPost')
                ->andReturn([])
                ->getMock()
            )
            ->shouldReceive('params')
            ->andReturn(
                m::mock()
                ->shouldReceive('fromPost')
                ->andReturn(['custom' => 'refuse'])
                ->getMock()
            )
            ->shouldReceive('isButtonPressed')
            ->with('confirm')
            ->andReturn(false)
            ->shouldReceive('isButtonPressed')
            ->with('save')
            ->once()
            ->andReturn(false)
            ->shouldReceive('isButtonPressed')
            ->with('refuse')
            ->once()
            ->andReturn(true)
            ->shouldReceive('confirm')
            ->with('message', true, 'refuse')
            ->andReturn(new ViewModel());

        $this->sm->setService(
            'translator',
            m::mock()
            ->shouldReceive('translate')
            ->with('internal.interim.form.refuse_confirm')
            ->andReturn('message')
            ->getMock()
        );

        $this->sm->setService(
            'Entity\Application',
            m::mock()
            ->shouldReceive('saveInterimData')
            ->with('formData', true)
            ->getMock()
        );

        $this->assertInstanceOf('Zend\View\Model\ViewModel', $this->sut->indexAction());
    }
}
