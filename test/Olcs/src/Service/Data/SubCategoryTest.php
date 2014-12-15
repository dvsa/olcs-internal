<?php

namespace OlcsTest\Service\Data;

use Olcs\Service\Data\SubCategory;
use Mockery as m;
use Mockery\Adapter\Phpunit\MockeryTestCase;

/**
 * Class DocumentSubCategoryTest
 * @package OlcsTest\Service\Data
 */
class SubCategoryTest extends MockeryTestCase
{
    public function testFetchListData()
    {
        $results = [
            'Results' => [
                ['result1'],
                ['result2']
            ]
        ];

        $mockRestClient = m::mock('\Common\Util\RestClient');
        $mockRestClient->shouldReceive('get')->once()->with('', m::type('array'))->andReturn($results);
        $sut = new SubCategory();
        $sut->setRestClient($mockRestClient);

        $this->assertEquals($results['Results'], $sut->fetchListData([]));
        $sut->fetchListData([]);
    }

    public function testFetchListDataWithCategory()
    {
        $results = [
            'Results' => [
                ['result1'],
                ['result2']
            ]
        ];

        $mockRestClient = m::mock('\Common\Util\RestClient');
        $mockRestClient->shouldReceive('get')
            ->once()
            ->with('', ['category' => 'testing', 'sort' => 'subCategoryName'])
            ->andReturn($results);

        $sut = new SubCategory();
        $sut->setRestClient($mockRestClient);
        $sut->setCategory('testing');

        $this->assertEquals($results['Results'], $sut->fetchListData([]));
        $sut->fetchListData([]);
    }

    public function testFormatData()
    {
        $sut = new SubCategory();

        $this->assertEquals(
            [
                1 => 'foo',
                2 => 'bar'
            ],
            $sut->formatData(
                [
                    [
                        'id' => 1,
                        'subCategoryName' => 'foo'
                    ], [
                        'id' => 2,
                        'subCategoryName' => 'bar'
                    ]
                ]
            )
        );
    }
}
