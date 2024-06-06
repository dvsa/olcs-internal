<?php

namespace OlcsTest\Service\Data;

use Common\Exception\DataServiceException;
use Olcs\Service\Data\IrfoGvPermitType;
use Mockery as m;
use Dvsa\Olcs\Transfer\Query\Irfo\IrfoGvPermitTypeList as Qry;
use CommonTest\Common\Service\Data\AbstractDataServiceTestCase;

/**
 * Class IrfoGvPermitType Test
 * @package CommonTest\Service
 */
class IrfoGvPermitTypeTest extends AbstractDataServiceTestCase
{
    /** @var IrfoGvPermitType */
    private $sut;

    protected function setUp(): void
    {
        parent::setUp();

        $this->sut = new IrfoGvPermitType($this->abstractDataServiceServices);
    }

    public function testFormatData()
    {
        $source = $this->getSingleSource();
        $expected = $this->getSingleExpected();

        $this->assertEquals($expected, $this->sut->formatData($source));
    }

    /**
     * @dataProvider provideFetchListOptions
     * @param $input
     * @param $expected
     */
    public function testFetchListOptions($input, $expected)
    {
        $this->sut->setData('IrfoGvPermitType', $input);

        $this->assertEquals($expected, $this->sut->fetchListOptions(''));
    }

    public function provideFetchListOptions()
    {
        return [
            [$this->getSingleSource(), $this->getSingleExpected()],
            [false, []]
        ];
    }

    public function testFetchListData()
    {
        $results = ['results' => [['id' => 1],['id' => 2], ['id' => 12]]];
        $expected = ['results' => [['id' => 1],['id' => 2]]];

        $this->transferAnnotationBuilder->shouldReceive('createQuery')
            ->with(m::type(Qry::class))
            ->once()
            ->andReturn($this->query);

        $mockResponse = m::mock()
            ->shouldReceive('isOk')
            ->andReturn(true)
            ->once()
            ->shouldReceive('getResult')
            ->andReturn($results)
            ->twice()
            ->getMock();

        $this->mockHandleQuery($mockResponse);

        $this->assertEquals($expected['results'], $this->sut->fetchListData());  //ensure data is cached
        $this->assertEquals($expected['results'], $this->sut->fetchListData());
    }

    public function testFetchLicenceDataWithException()
    {
        $this->expectException(DataServiceException::class);

        $this->transferAnnotationBuilder->shouldReceive('createQuery')
            ->with(m::type(Qry::class))
            ->once()
            ->andReturn($this->query);

        $mockResponse = m::mock()
            ->shouldReceive('isOk')
            ->andReturn(false)
            ->once()
            ->getMock();

        $this->mockHandleQuery($mockResponse);

        $this->sut->fetchListData();
    }

    /**
     * @return array
     */
    protected function getSingleExpected()
    {
        $expected = [
            'val-1' => 'Value 1',
            'val-2' => 'Value 2',
            'val-3' => 'Value 3',
        ];
        return $expected;
    }

    /**
     * @return array
     */
    protected function getSingleSource()
    {
        $source = [
            ['id' => 'val-1', 'description' => 'Value 1'],
            ['id' => 'val-2', 'description' => 'Value 2'],
            ['id' => 'val-3', 'description' => 'Value 3'],
        ];
        return $source;
    }

    public function testFilterArrayById()
    {
        $sourceData = [
            ['id' => 1, 'description' => 'Valid Entry'],
            ['id' => 5, 'description' => 'Should be filtered out'],
            ['id' => 10, 'description' => 'Valid Entry'],
            ['id' => 12, 'someField' => 'Should be filtered out'],
            ['id' => 19, 'description' => 'Should be filtered out'],
            ['id' => 22, 'otherField' => 'Valid Entry'],
        ];

        $expected = [
            ['id' => 1, 'description' => 'Valid Entry'],
            ['id' => 10, 'description' => 'Valid Entry'],
            ['id' => 22, 'otherField' => 'Valid Entry']
        ];

        $filteredData = $this->sut->filterArrayById($sourceData);

        $this->assertEquals($expected, array_values($filteredData), "Filtered array does not match expected output");
    }
}
