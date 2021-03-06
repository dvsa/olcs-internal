<?php

namespace OlcsTest\Service\Data;

use Common\Exception\DataServiceException;
use CommonTest\Service\Data\AbstractDataServiceTestCase;
use Mockery as m;
use Olcs\Service\Data\ApplicationStatus;
use Dvsa\Olcs\Transfer\Query as TransferQry;

/**
 * @covers \Olcs\Service\Data\ApplicationStatus
 */
class ApplicationStatusTest extends AbstractDataServiceTestCase
{
    const ORG_ID = 9999;

    /** @var  ApplicationStatus */
    private $sut;
    /** @var  \Laminas\Http\Response | m\MockInterface */
    private $mockResp;
    /** @var  \Dvsa\Olcs\Transfer\Util\Annotation\AnnotationBuilder | m\MockInterface */
    protected $mockTransferAnnotationBuilder;

    public function setUp(): void
    {
        $this->sut = new ApplicationStatus;

        $this->mockResp = m::mock(\Laminas\Http\Response::class);
        $this->mockTransferAnnotationBuilder = m::mock(\Dvsa\Olcs\Transfer\Util\Annotation\AnnotationBuilder::class);

        parent::setUp();
    }

    public function testSetters()
    {
        $this->sut->setOrgId('unit_Org');

        static::assertEquals('unit_Org', $this->sut->getOrgId());
    }

    public function testFetchListData()
    {
        $results = ['results' => ['unit_Results']];

        $this->mockTransferAnnotationBuilder
            ->shouldReceive('createQuery')
            ->once()
            ->andReturnUsing(
                function (TransferQry\DataService\ApplicationStatus $qry) {
                    static::assertEquals(self::ORG_ID, $qry->getOrganisation());

                    return 'query';
                }
            );

        $this->mockResp
            ->shouldReceive('isOk')->once()->andReturn(true)
            ->shouldReceive('getResult')->once()->andReturn($results);

        $this->mockHandleQuery($this->sut, $this->mockTransferAnnotationBuilder, $this->mockResp);

        $this->sut->setOrgId(self::ORG_ID);

        static::assertEquals($results['results'], $this->sut->fetchListData());
        static::assertEquals($results['results'], $this->sut->fetchListData()); //ensure data is cached
    }

    public function testFetchListDataWithException()
    {
        $this->expectException(DataServiceException::class);

        $this->mockTransferAnnotationBuilder->shouldReceive('createQuery')->once()->andReturn('query');
        $this->mockResp->shouldReceive('isOk')->once()->andReturn(false);

        $this->mockHandleQuery($this->sut, $this->mockTransferAnnotationBuilder, $this->mockResp);

        $this->sut->fetchListData([]);
    }
}
