<?php

/**
 * Inspector Request Update Test
 *
 * @author Dan Eggleston <dan@stolenegg.com>
 */
namespace OlcsTest\BusinessService\Service\Lva;

use Common\BusinessService\Response;
use Mockery\Adapter\Phpunit\MockeryTestCase;
use Mockery as m;
use OlcsTest\Bootstrap;
use Olcs\BusinessService\Service\InspectionRequestUpdate;
use Common\Service\Entity\InspectionRequestEntityService;
use Common\Service\Data\CategoryDataService;
use Common\Exception\ResourceNotFoundException;

/**
 * Inspector Request Update Test
 *
 * @author Dan Eggleston <dan@stolenegg.com>
 */
class InspectionRequestUpdateTest extends MockeryTestCase
{
    protected $sut;

    protected $sm;

    public function setUp()
    {
        $this->sm = Bootstrap::getServiceManager();

        $this->sut = new InspectionRequestUpdate();

        $this->sut->setServiceLocator($this->sm);
    }

    /**
     * Test process method
     *
     * @dataProvider successProvider
     */
    public function testProcessSuccess($id, $status, $expectedResultType, $expectedTaskDescription)
    {
        // mocks
        $mockEntityService = m::mock();
        $this->sm->setService('Entity\InspectionRequest', $mockEntityService);
        $taskProcessingMock = m::mock();
        $this->sm->setService('Processing\Task', $taskProcessingMock);
        $bsm = m::mock('\Common\BusinessService\BusinessServiceManager')->makePartial();
        $taskBusinessServiceMock = m::mock('\Common\BusinessService\BusinessServiceInterface');
        $bsm->setService('Task', $taskBusinessServiceMock);
        $this->sut->setBusinessServiceManager($bsm);

        // expectations
        $mockEntityService
            ->shouldReceive('forceUpdate')
            ->once()
            ->with($id, ['resultType' => $expectedResultType]);

        $taskProcessingMock
            ->shouldReceive('getAssignment')
            ->once()
            ->with(
                [
                    'category' => CategoryDataService::CATEGORY_LICENSING,
                    'subCategory' => CategoryDataService::TASK_SUB_CATEGORY_INSPECTION_REQUEST_SEMINAR,
                ]
            )
            ->andReturn(
                [
                    'assignedToTeam' => 9,
                    'assignedToUser' => 10,
                ]
            );

        $expectedTaskData = [
            'category'       => CategoryDataService::CATEGORY_LICENSING,
            'subCategory'    => CategoryDataService::TASK_SUB_CATEGORY_INSPECTION_REQUEST_SEMINAR,
            'description'    => $expectedTaskDescription,
            'isClosed'       => 'N',
            'urgent'         => 'N',
            'assignedToTeam' => 9,
            'assignedToUser' => 10,
        ];
        $taskBusinessServiceMock
            ->shouldReceive('process')
            ->once()
            ->with($expectedTaskData)
            ->andReturn(new Response(Response::TYPE_SUCCESS));

        $params = [
            'id' => $id,
            'status' => $status,
        ];
        $response = $this->sut->process($params);

        $this->assertInstanceOf('Common\BusinessService\Response', $response);
        $this->assertTrue($response->isOk());
    }

    public function successProvider()
    {
        return [
            'satisfactory' => [
                123,
                'S',
                'insp_res_t_new_sat', // InspectionRequestEntityService::RESULT_TYPE_SATISFACTORY
                'Satisfactory inspection request: ID 123',
            ],
            'unsatisfactory' => [
                123,
                'U',
                'insp_res_t_new_unsat', // InspectionRequestEntityService::RESULT_TYPE_UNSATISFACTORY
                'Unsatisfactory inspection request: ID 123',
            ],
        ];
    }

    /**
     * Test process method when inspection request not found
     */
    public function testProcessNotFound()
    {
        $id = 123;
        $status = 'S';

        // mocks
        $mockEntityService = m::mock();
        $this->sm->setService('Entity\InspectionRequest', $mockEntityService);

        // expectations
        $mockEntityService
            ->shouldReceive('forceUpdate')
            ->once()
            ->with($id, ['resultType' => 'insp_res_t_new_sat'])
            ->andThrow(new ResourceNotFoundException());

        $params = [
            'id' => $id,
            'status' => $status,
        ];
        $response = $this->sut->process($params);

        $this->assertInstanceOf('Common\BusinessService\Response', $response);
        $this->assertFalse($response->isOk());
    }

    public function testProcessInvalidStatusCode()
    {
        $id = 123;
        $status = 'foo';

        $params = [
            'id' => $id,
            'status' => $status,
        ];
        $response = $this->sut->process($params);

        $this->assertInstanceOf('Common\BusinessService\Response', $response);
        $this->assertFalse($response->isOk());
    }
}
