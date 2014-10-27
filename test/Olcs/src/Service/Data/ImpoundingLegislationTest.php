<?php

namespace OlcsTest\Service\Data;

use Olcs\Service\Data\ImpoundingLegislation;
use Mockery as m;

/**
 * Class ImpoundingLegislationTest
 * @author Ian Lindsay <ian@hemera-business-services.co.uk>
 * @package CommonTest\Service
 */
class ImpoundingLegislationTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider provideFetchListOptions
     *
     * @param $niFlag
     * @param $goodsOrPsv
     * @param $expectedList
     */
    public function testFetchListOptions($niFlag, $goodsOrPsv, $expectedList)
    {
        $mockLicenceService = $this->getMock('\Olcs\Service\Data\Licence');
        $mockLicenceService->expects($this->once())
            ->method('fetchLicenceData')
            ->willReturn(['niFlag'=> $niFlag, 'goodsOrPsv' => ['id'=> $goodsOrPsv], 'trafficArea' => ['id'=> 'B']]);

        $sut = new ImpoundingLegislation();
        $sut->setLicenceService($mockLicenceService);

        $mockRestClient = m::mock('Common\Util\RestClient');
        $mockRestClient->shouldReceive('get')->once()->with('/' . $expectedList)->andReturn($this->getSingleSource());
        $sut->setRestClient($mockRestClient);

        $this->assertEquals($this->getSingleExpected(), $sut->fetchListOptions([]));
    }

    /**
     * Tests fetchListOptions when no data is returned
     */
    public function testFetchListOptionsNoData()
    {
        $mockLicenceService = $this->getMock('\Olcs\Service\Data\Licence');
        $mockLicenceService->expects($this->once())
            ->method('fetchLicenceData')
            ->willReturn(['niFlag'=> true, 'goodsOrPsv' => ['id'=> 'lcat_gv'], 'trafficArea' => ['id'=> 'B']]);

        $sut = new ImpoundingLegislation();
        $sut->setLicenceService($mockLicenceService);

        $mockRestClient = m::mock('Common\Util\RestClient');
        $mockRestClient->shouldReceive('get')->once()->with('/impound_legislation_goods_ni')->andReturn('');
        $sut->setRestClient($mockRestClient);

        $this->assertEquals([], $sut->fetchListOptions([]));
    }

    /**
     * Data provider for testFetchListOptions
     *
     * @return array
     */
    public function provideFetchListOptions()
    {
        return [
            [true, 'lcat_psv', 'impound_legislation_psv_gb'],
            [true, 'lcat_gv', 'impound_legislation_goods_ni'],
            [false, 'lcat_gv', 'impound_legislation_goods_gb']
        ];
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
            0 => ['id' => 'val-1', 'description' => 'Value 1'],
            1 => ['id' => 'val-2', 'description' => 'Value 2'],
            2 => ['id' => 'val-3', 'description' => 'Value 3']
        ];

        return $source;
    }
}