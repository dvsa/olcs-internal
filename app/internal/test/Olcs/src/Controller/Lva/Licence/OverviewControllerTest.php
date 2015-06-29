<?php

/**
 * Internal Licencing Overview Controller Test
 *
 * @author Rob Caiger <rob@clocal.co.uk>
 * @author Dan Eggleston <dan@stolenegg.com>
 */
namespace OlcsTest\Controller\Lva\Licence;

use Mockery as m;
use OlcsTest\Controller\Lva\AbstractLvaControllerTestCase;
use Common\BusinessService\Response;
use Common\Service\Entity\LicenceEntityService as Licence;
use Dvsa\Olcs\Transfer\Query\Licence\Overview as OverviewQuery;
use Dvsa\Olcs\Transfer\Command\Licence\Overview as OverviewCommand;

/**
 * Internal Licencing Overview Controller Test
 *
 * @author Rob Caiger <rob@clocal.co.uk>
 * @author Dan Eggleston <dan@stolenegg.com>
 */
class OverviewControllerTest extends AbstractLvaControllerTestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->mockController('\Olcs\Controller\Lva\Licence\OverviewController');
    }

    public function testCreateVariationAction()
    {
        $licenceId = 3;
        $varId = 5;

        $mockApplicationService = m::mock();
        $this->sm->setService('Entity\Application', $mockApplicationService);

        $this->sut->shouldReceive('params')
            ->with('licence')
            ->andReturn($licenceId);

        $mockApplicationService->shouldReceive('createVariation')
            ->with($licenceId)
            ->andReturn($varId);

        $this->sut->shouldReceive('redirect->toRouteAjax')
            ->with('lva-variation', ['application' => $varId])
            ->andReturn('RESPONSE');

        $this->assertEquals('RESPONSE', $this->sut->createVariationAction());
    }

    /**
     * @dataProvider indexProvider
     * @param array $overviewData
     * @param boolean $shouldRemoveTcArea
     * @param boolean $shouldRemoveReviewDate
     */
    public function testIndexActionGet($overviewData, $shouldRemoveReviewDate)
    {
        $licenceId = 123;

        $this->sut->shouldReceive('params')
            ->with('licence')
            ->andReturn($licenceId);

        $form = $this->createMockForm('LicenceOverview');

        $this->expectQuery(OverviewQuery::class, ['id' => $licenceId], $overviewData);

        $viewData = ['foo' => 'bar'];

        $this->mockService('Helper\LicenceOverview', 'getViewData')
            ->with($overviewData)
            ->once()
            ->andReturn($viewData);

        $this->mockTcAreaSelect($form);

        $form->shouldReceive('setData')
            ->once()
            ->with(
                [
                    'details' => [
                        'continuationDate' => '2017-06-05',
                        'reviewDate'       => '2016-05-04',
                        'translateToWelsh' => 'Y',
                        'leadTcArea'       => 'B',
                    ],
                    'id' => $licenceId,
                    'version' => 1,
                ]
            )
            ->andReturnSelf();

        if ($shouldRemoveReviewDate) {
            $this->getMockFormHelper()
                ->shouldReceive('remove')
                ->once()
                ->with($form, 'details->reviewDate');
        }

        $this->mockRender();

        $view = $this->sut->indexAction();
        $this->assertEquals('pages/licence/overview', $view->getTemplate());

        foreach ($viewData as $key => $value) {
            $this->assertEquals($value, $view->getVariable($key), "'$key' not as expected");
        }
    }

    public function indexProvider()
    {
        $valueOptions = [
            'trafficAreas' => [
                'A' => 'Traffic area A',
                'B' => 'Traffic area B',
            ],
        ];

        return [
            'valid goods licence' => [
                [
                    'id'           => 123,
                    'version'      => 1,
                    'translateToWelsh' => 'Y',
                    'reviewDate'   => '2016-05-04',
                    'expiryDate'   => '2017-06-05',
                    'status'       => ['id' => Licence::LICENCE_STATUS_VALID],
                    'organisation' => [
                        'id' => 72,
                        'licences' => [
                            ['id' => 210],
                            ['id' => 208],
                            ['id' => 203],
                        ],
                        'leadTcArea' => ['id' => 'B', 'isWales' => true],
                    ],
                    'trafficArea' => ['id' => 'B', 'isWales' => true],
                    'valueOptions' => $valueOptions,
                ],
                false,
            ],
            'surrendered psv licence' => [
                [
                    'id'           => 123,
                    'version'      => 1,
                    'translateToWelsh' => 'Y',
                    'reviewDate'   => '2016-05-04',
                    'expiryDate'   => '2017-06-05',
                    'status'       => ['id' => Licence::LICENCE_STATUS_SURRENDERED],
                    'organisation' => [
                        'id' => 72,
                        'licences' => [
                            ['id' => 210],
                            ['id' => 208],
                            ['id' => 203],
                        ],
                        'leadTcArea' => ['id' => 'B', 'isWales' => true],
                    ],
                    'trafficArea' => ['id' => 'B', 'isWales' => true],
                    'valueOptions' => $valueOptions,
                ],
                true,
            ],
            'special restricted psv licence' => [
                [
                    'id'           => 123,
                    'version'      => 1,
                    'translateToWelsh' => 'Y',
                    'reviewDate'   => '2016-05-04',
                    'expiryDate'   => '2017-06-05',
                    'status'       => ['id' => Licence::LICENCE_STATUS_VALID],
                    'organisation' => [
                        'id' => 72,
                        'licences' => [
                            ['id' => 210],
                        ],
                        'leadTcArea' => ['id' => 'B', 'isWales' => true],
                    ],
                    'trafficArea' => ['id' => 'B', 'isWales' => true],
                    'valueOptions' => $valueOptions,
                ],
                false,
            ],
        ];
    }

    public function testIndexActionPostValidSaveSuccess()
    {
        $licenceId = 123;
        $organisationId = 234;

        $this->sut->shouldReceive('params')
            ->with('licence')
            ->andReturn($licenceId);

        $form = $this->createMockForm('LicenceOverview');

        $overviewData = [
            'id' => $licenceId,
            'status' => ['id' => Licence::LICENCE_STATUS_VALID],
            'organisation' => [
                'id' => $organisationId,
                'leadTcArea' => ['id' => 'B', 'isWales' => false],
                'licences' => [
                    ['id' => 69],
                    ['id' => 70],
                ],
            ],
            'trafficArea' => ['id' => 'B', 'isWales' => false],
            'valueOptions' => [
                'trafficAreas' => [
                    'A' => 'Traffic area A',
                    'B' => 'Traffic area B',
                ],
            ],
        ];

        $this->expectQuery(OverviewQuery::class, ['id' => $licenceId], $overviewData);

        $postData = [
            'id' => $licenceId,
            'version' => '1',
            'details' => [
                'continuationDate' => [
                    'day' => '04',
                    'month' => '03',
                    'year' => '2012'
                ],
                'reviewDate' => [
                    'day' => '11',
                    'month' => '12',
                    'year' => '2021'
                ],
                'leadTcArea' => 'B',
            ],
        ];

        $formData = [
            'id' => $licenceId,
            'version' => '1',
            'details' => [
                'continuationDate' => '2012-03-04',
                'reviewDate' =>  '2021-12-11',
                'leadTcArea' => 'B',
            ],
        ];

        $expectedCmdData = [
            'id' => $licenceId,
            'version' => '1',
            'leadTcArea' => 'B',
            'expiryDate' => '2012-03-04',
            'reviewDate' => '2021-12-11',
            'translateToWelsh' => null,
        ];

        $this->setPost($postData);

        $form->shouldReceive('setData')
            ->once()
            ->with($postData)
            ->andReturnSelf()
            ->shouldReceive('getData')
            ->andReturn($formData);

        $form->shouldReceive('isValid')
            ->once()
            ->andReturn(true);

        $this->getMockFormHelper()
            ->shouldReceive('remove')
            ->with($form, 'details->reviewDate')
            ->shouldReceive('remove')
            ->with($form, 'details->translateToWelsh');

        $this->mockTcAreaSelect($form);

        $this->expectCommand(
            OverviewCommand::class,
            $expectedCmdData,
            [
                'id' => [
                    'licence' => $licenceId,
                ],
                'messages' => [
                    'licence updated',
                ]
            ]
        );

        $this->sut->shouldReceive('addSuccessMessage')->once();
        $this->sut->shouldReceive('reload')->andReturn('REDIRECT');

        $this->assertEquals('REDIRECT', $this->sut->indexAction());
    }

    public function testIndexActionPostValidSaveFails()
    {
        $licenceId = 123;
        $organisationId = 234;

        $this->sut->shouldReceive('params')
            ->with('licence')
            ->andReturn($licenceId);

        $form = $this->createMockForm('LicenceOverview');

        $overviewData = [
            'id' => $licenceId,
            'status' => ['id' => Licence::LICENCE_STATUS_VALID],
            'organisation' => [
                'id' => $organisationId,
                'leadTcArea' => ['id' => 'B', 'isWales' => false],
                'licences' => [
                    ['id' => 69],
                    ['id' => 70],
                ],
            ],
            'trafficArea' => ['id' => 'B', 'isWales' => false],
            'valueOptions' => [
                'trafficAreas' => [
                    'A' => 'Traffic area A',
                    'B' => 'Traffic area B',
                ],
            ],
        ];

        $this->expectQuery(OverviewQuery::class, ['id' => $licenceId], $overviewData);

        $postData = [
            'id' => $licenceId,
            'version' => '1',
            'details' => [
                'continuationDate' => [
                    'day' => '04',
                    'month' => '03',
                    'year' => '2012'
                ],
                'reviewDate' => [
                    'day' => '11',
                    'month' => '12',
                    'year' => '2021'
                ],
                'leadTcArea' => 'B',
            ],
        ];

        $formData = [
            'id' => $licenceId,
            'version' => '1',
            'details' => [
                'continuationDate' => '2012-03-04',
                'reviewDate' =>  '2021-12-11',
                'leadTcArea' => 'B',
            ],
        ];

        $expectedCmdData = [
            'id' => $licenceId,
            'version' => '1',
            'leadTcArea' => 'B',
            'expiryDate' => '2012-03-04',
            'reviewDate' => '2021-12-11',
            'translateToWelsh' => null,
        ];

        $this->setPost($postData);

        $form->shouldReceive('setData')
            ->once()
            ->with($postData)
            ->andReturnSelf()
            ->shouldReceive('getData')
            ->andReturn($formData);

        $form->shouldReceive('isValid')
            ->once()
            ->andReturn(true);

        $this->getMockFormHelper()
            ->shouldReceive('remove')
            ->with($form, 'details->reviewDate')
            ->shouldReceive('remove')
            ->with($form, 'details->translateToWelsh');

        $this->mockTcAreaSelect($form);

        $this->expectCommand(
            OverviewCommand::class,
            $expectedCmdData,
            [
                'messages'  => [
                    'failed',
                ]
            ],
            false
        );

        $this->sut->shouldReceive('addErrorMessage')->once();

        $this->mockService('Helper\LicenceOverview', 'getViewData')
            ->with($overviewData)
            ->once()
            ->andReturn([]);

        $this->mockTcAreaSelect($form);

        $this->mockRender();

        $view = $this->sut->indexAction();
        $this->assertEquals('pages/licence/overview', $view->getTemplate());
    }

    public function testIndexActionPostInvalid()
    {
        $licenceId = 123;

        $this->sut->shouldReceive('params')
            ->with('licence')
            ->andReturn($licenceId);

        $form = $this->createMockForm('LicenceOverview');

        $overviewData = [
            'id'           => 123,
            'version'      => 1,
            'reviewDate'   => '2016-05-04',
            'expiryDate'   => '2017-06-05',
            'status'       => ['id' => Licence::LICENCE_STATUS_VALID],
            'organisation' => [
                'id' => 72,
                'licences' => [
                    ['id' => 210],
                    ['id' => 208],
                    ['id' => 203],
                ],
                'leadTcArea' => ['id' => 'B', 'isWales' => true],
            ],
            'trafficArea' => ['id' => 'B', 'isWales' => true],
            'valueOptions' => [
                'trafficAreas' => [
                    'A' => 'Traffic area A',
                    'B' => 'Traffic area B',
                ],
            ],
        ];

        $this->expectQuery(OverviewQuery::class, ['id' => $licenceId], $overviewData);

        $postData = [
            'id' => $licenceId,
            'version' => '1',
            'details' => [],
        ];

        $this->setPost($postData);

        $form->shouldReceive('setData')
            ->once()
            ->with($postData)
            ->andReturnSelf();

        $form->shouldReceive('isValid')
            ->once()
            ->andReturn(false);

        $this->mockService('Helper\LicenceOverview', 'getViewData')
            ->with($overviewData)
            ->once()
            ->andReturn([]);

        $this->mockTcAreaSelect($form);

        $this->mockRender();

        $view = $this->sut->indexAction();
        $this->assertEquals('pages/licence/overview', $view->getTemplate());
    }

    protected function mockTcAreaSelect($form)
    {
        $tcAreaOptions = [
            'A' => 'Traffic area A',
            'B' => 'Traffic area B',
        ];

        $this->mockEntity('TrafficArea', 'getValueOptions')
            ->andReturn($tcAreaOptions);

        $form->shouldReceive('get')->with('details')->andReturn(
            m::mock()
                ->shouldReceive('get')
                    ->with('leadTcArea')
                    ->andReturn(
                        m::mock()
                            ->shouldReceive('setValueOptions')
                            ->with($tcAreaOptions)
                            ->getMock()
                    )
                ->getMock()
        );
    }

    public function testPrintAction()
    {
        $this->markTestSkipped();
        $this->sut->shouldReceive('params')
            ->with('licence')
            ->andReturn(123);

        $this->mockService('Processing\Licence', 'generateDocument')
            ->with(123);

        $this->sut->shouldReceive('addSuccessMessage')->once();

        $this->sut->shouldReceive('redirect->toRoute')
            ->with('lva-licence/overview', [], [], true)
            ->andReturn('RESPONSE');

        $this->assertEquals(
            'RESPONSE',
            $this->sut->printAction()
        );
    }
}
