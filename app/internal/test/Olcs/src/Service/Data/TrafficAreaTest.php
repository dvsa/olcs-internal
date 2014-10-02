<?php

namespace OlcsTest\Service\Data;

use Olcs\Service\Data\TrafficArea;
use Mockery as m;

/**
 * Class TrafficArea Test
 * @package CommonTest\Service
 */
class TrafficAreaTest extends \PHPUnit_Framework_TestCase
{
    public function testGetServiceName()
    {
        $sut = new TrafficArea();
        $this->assertEquals('TrafficArea', $sut->getServiceName());
    }

    public function testFormatData()
    {
        $source = $this->getSingleSource();
        $expected = $this->getSingleExpected();

        $sut = new TrafficArea();

        $this->assertEquals($expected, $sut->formatData($source));
    }

    /**
     * @dataProvider provideFetchListOptions
     * @param $input
     * @param $expected
     */
    public function testFetchListOptions($input, $expected)
    {
        $sut = new TrafficArea();
        $sut->setData('TrafficArea', $input);

        $this->assertEquals($expected, $sut->fetchListOptions(''));
    }

    public function provideFetchListOptions()
    {
        return [
            [$this->getSingleSource(), $this->getSingleExpected()],
            [false, []]
        ];
    }

    /**
     * @dataProvider provideFetchListData
     * @param $data
     * @param $expected
     */
    public function testFetchListData($data, $expected)
    {
        $mockRestClient = m::mock('Common\Util\RestClient');
        $mockRestClient->shouldReceive('get')->once()->with('', ['limit' => 1000])->andReturn($data);

        $sut = new TrafficArea();
        $sut->setRestClient($mockRestClient);

        $this->assertEquals($expected, $sut->fetchListData());
        $sut->fetchListData(); //ensure data is cached
    }

    public function provideFetchListData()
    {
        return [
            [false, false],
            [['Results' => $this->getSingleSource()], $this->getSingleSource()],
            [['some' => 'data'],  false]
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
            ['id' => 'val-1', 'name' => 'Value 1'],
            ['id' => 'val-2', 'name' => 'Value 2'],
            ['id' => 'val-3', 'name' => 'Value 3'],
        ];
        return $source;
    }
}
