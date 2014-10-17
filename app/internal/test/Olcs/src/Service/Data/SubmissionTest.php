<?php

namespace OlcsTest\Service\Data;

use Olcs\Service\Data\Submission;

/**
 * Class SubmissionTest
 * @package OlcsTest\Service\Data
 */
class SubmissionTest extends \PHPUnit_Framework_TestCase
{
    protected $sut;

    public function setUp()
    {
        $this->sut = new Submission();
    }

    public function testCreateService()
    {
        $mockRefDataService = $this->getMock('\Olcs\Service\Data\RefData');

        $mockTranslator = $this->getMock('stdClass', ['getLocale']);
        $mockTranslator->expects($this->once())->method('getLocale')->willReturn('en_GB');

        $mockRestClient = $this->getMock('\Common\Util\RestClient', [], [], '', 0);
        $mockRestClient->expects($this->once())->method('setLanguage')->with($this->equalTo('en_GB'));

        $mockApiResolver = $this->getMock('stdClass', ['getClient']);
        $mockApiResolver
            ->expects($this->once())
            ->method('getClient')
            ->with($this->equalTo('Submission'))
            ->willReturn($mockRestClient);

        $mockSl = $this->getMock('\Zend\ServiceManager\ServiceManager');
        $mockSl->expects($this->any())
            ->method('get')
            ->willReturnMap(
                [
                    ['translator', true, $mockTranslator],
                    ['ServiceApiResolver', true, $mockApiResolver],
                    ['Common\Service\Data\RefData', true, $mockRefDataService]
                ]
            );

        $service = $this->sut->createService($mockSl);

        $this->assertInstanceOf('\Olcs\Service\Data\Submission', $service);
        $this->assertSame($mockRestClient, $service->getRestClient());
        $this->assertSame($mockRefDataService, $service->getRefDataService());
    }

    public function testFetchSubmissionData()
    {
        $submission = ['id' => 24];

        $mockRestClient = $this->getMock('\Common\Util\RestClient', [], [], '', false);
        $mockRestClient->expects($this->once())
            ->method('get')
            ->with($this->equalTo('/24'), $this->isType('array'))
            ->willReturn($submission);

        $this->sut->setRestClient($mockRestClient);

        $this->assertEquals($submission, $this->sut->fetchSubmissionData(24));
        //test data is cached
        $this->assertEquals($submission, $this->sut->fetchSubmissionData(24));

    }

    /**
     *
     * @dataProvider providerSubmissions
     * @param $input
     * @param $expected
     */
    public function testExtractSelectedSubmissionSectionsData($input, $expected)
    {
        $mockRefDataService = $this->getMock('Common\Service\Data\RefData', ['fetchListOptions']);

        $mockSectionRefData = $this->getMockSectionRefData();
        $mockRefDataService->expects(
            $this->once()
        )->method(
            'fetchListOptions'
        )->with('submission_section')
        ->willReturn($mockSectionRefData);

        $this->sut->setRefDataService($mockRefDataService);

        $result = $this->sut->extractSelectedSubmissionSectionsData($input);

        $this->assertEquals($result, $expected);

    }

    public function testGetAllSectionsRefData()
    {
        $mockRefDataService = $this->getMock('Common\Service\Data\RefData', ['fetchListOptions']);

        $mockSectionRefData = $this->getMockSectionRefData();
        $mockRefDataService->expects(
            $this->once()
        )->method('fetchListOptions')->with('submission_section')
            ->willReturn($mockSectionRefData);

        $this->sut->setRefDataService($mockRefDataService);

        $result = $this->sut->getAllSectionsRefData();

        $this->assertEquals($result, $this->getMockSectionRefData());

        // check cached
        $result = $this->sut->getAllSectionsRefData();
        $this->assertEquals($result, $this->getMockSectionRefData());

    }

    public function testSetAllSectionsRefData()
    {
        $this->sut->setAllSectionsRefData($this->getMockSectionRefData());
        $result = $this->sut->getAllSectionsRefData();

        $this->assertEquals($this->getMockSectionRefData(), $this->sut->getAllSectionsRefData());
    }

    public function testGetSubmissionTypeTitle()
    {
        $mockRefDataService = $this->getMock('Common\Service\Data\RefData', ['fetchListData']);

        $mockSubmissionTitles = $this->getMockSubmissionTitles();
        $mockRefDataService->expects($this->any())->method('fetchListData')->with('submission_type_title')
            ->willReturn($mockSubmissionTitles);

        $this->sut->setRefDataService($mockRefDataService);

        $testData = $this->getMockSubmissionTitles();
        foreach ($testData as $index => $testTitle) {
            $input = str_replace('_t_', '_o_', $testTitle['id']);

            $result = $this->sut->getSubmissionTypeTitle($input);

            $this->assertEquals($result, $testTitle['description']);
        }
        // test bad data
        $result = $this->sut->getSubmissionTypeTitle('');
        $this->assertEquals($result, '');
        $result = $this->sut->getSubmissionTypeTitle('type_doesnt_exist');
        $this->assertEquals($result, '');
        $result = $this->sut->getSubmissionTypeTitle(false);
        $this->assertEquals($result, '');

    }

    /**
     *
     * @dataProvider providerSubmissionSectionData
     * @param $input
     * @param $expected
     */
    public function testCreateSubmissionSection($input, $expected)
    {
        $mockRestClient = $this->getMock('\Common\Util\RestClient', [], [], '', false);
        $mockRestClient->expects($this->any())
            ->method('get')
            ->with(
                '',
                array('id' => $input['caseId'],
                    'bundle' => json_encode($input['sectionConfig']['bundle'])
                )
            )
            ->willReturn($expected['loadedCaseSectionData']);

        $mockApiResolver = $this->getMock('stdClass', ['getClient']);
        $mockApiResolver
            ->expects($this->once())
            ->method('getClient')
            ->with($this->equalTo($input['sectionConfig']['service']))
            ->willReturn($mockRestClient);

        $this->sut->setApiResolver($mockApiResolver);

        $result = $this->sut->createSubmissionSection($input['caseId'], $input['sectionId'], $input['sectionConfig']);

        $this->assertEquals($result, $expected['filteredSectionData']);
    }

    /**
     * Tests for submission sections where the data has already been extracted
     *
     * @dataProvider providerSubmissionSectionPrebuiltData
     * @param $input
     * @param $expected
     */
    public function testCreateSubmissionSectionUsingPrebuiltData($input, $expected)
    {
        $this->sut->setLoadedSectionDataForSection($input['sectionId'], $expected['loadedCaseSectionData']);

        $result = $this->sut->createSubmissionSection($input['caseId'], $input['sectionId'], $input['sectionConfig']);

        $this->assertEquals($result, $expected['filteredSectionData']);
    }

    public function testCreateSubmissionSectionEmptyConfig()
    {

        $input = [
            'caseId' => 24,
            'sectionId' => 'conviction-fpn-offence-history',
            'sectionConfig' => []
        ];

        $result = $this->sut->createSubmissionSection($input['caseId'], $input['sectionId'], $input['sectionConfig']);

        $this->assertEquals($result, []);
    }

    public function testSetId()
    {
        $this->sut->setId(1);
        $this->assertEquals(1, $this->sut->getId());
    }

    public function testGetId()
    {
        $this->assertNull($this->sut->getId());
    }

    public function testSetApiResolver()
    {
        $apiResolver = new \StdClass();
        $this->sut->setApiResolver($apiResolver);
        $this->assertEquals($apiResolver, $this->sut->getApiResolver());
    }

    public function testGetApiResolver()
    {
        $this->assertNull($this->sut->getApiResolver());
    }

    public function testGetLoadedSectionData()
    {
        $this->sut->setLoadedSectionData('foo');
        $this->assertEquals('foo', $this->sut->getLoadedSectionData());
    }

    public function testSetLoadedSectionData()
    {
        $this->assertEquals($this->sut, $this->sut->setLoadedSectionData('foo'));
    }

    public function testSetSubmissionConfig()
    {
        $config = ['foo'];
        $this->sut->setSubmissionConfig($config);
        $this->assertEquals($config, $this->sut->getSubmissionConfig());
    }

    public function testGetSubmissionConfig()
    {
        $this->assertNull($this->sut->getSubmissionConfig());
    }


    public function providerSubmissionTitles()
    {
        return [
            [
                    'submission_title_o_mlh',
                    'Introduction'

            ]
        ];
    }

    public function providerSubmissionSectionData()
    {
        return [
            [   // conviction section
                [
                    'caseId' => 24,
                    'sectionId' => 'conviction-fpn-offence-history',
                    'sectionConfig' => [
                        'service' => 'Cases',
                        'bundle' => ['some_bundle'],
                    ]
                ],
                [
                    'loadedCaseSectionData' => [
                        'convictions' => [
                            0 => [
                                'offenceDate' => '2012-03-10T00:00:00+0000',
                                'convictionDate' => '2012-06-15T00:00:00+0100',
                                'operatorName' => 'John Smith Haulage Ltd.',
                                'categoryText' => null,
                                'court' => 'FPN',
                                'penalty' => '3 points on licence',
                                'msi' => 'N',
                                'isDeclared' => 'N',
                                'isDealtWith' => 'N',
                                'defendantType' => [
                                    'id' => 'def_t_op',
                                    'description' => 'Operator'
                                ],
                                'personFirstname' => '',
                                'personLastname' => '',
                            ],
                            1 => [
                                'offenceDate' => '2012-03-10T00:00:00+0000',
                                'convictionDate' => '2012-06-15T00:00:00+0100',
                                'operatorName' => false,
                                'personFirstname' => 'John',
                                'personLastname' => 'Smith',
                                'categoryText' => null,
                                'court' => 'FPN',
                                'penalty' => '3 points on licence',
                                'msi' => 'N',
                                'isDeclared' => 'N',
                                'isDealtWith' => 'N',
                                'defendantType' => [
                                    'id' => 'def_t_owner',
                                    'description' => 'Owner'
                                ],
                                'personFirstname' => 'Bob',
                                'personLastname' => 'Smith',
                            ]
                        ]
                    ],
                    'filteredSectionData' => [
                        0 => [
                            'offenceDate' => '2012-03-10T00:00:00+0000',
                            'convictionDate' => '2012-06-15T00:00:00+0100',
                            'name' => 'John Smith Haulage Ltd.',
                            'categoryText' => null,
                            'court' => 'FPN',
                            'penalty' => '3 points on licence',
                            'msi' => 'N',
                            'isDeclared' => 'N',
                            'isDealtWith' => 'N',
                            'defendantType' => [
                                'id' => 'def_t_op',
                                'description' => 'Operator'
                            ],
                        ],
                        1 => [
                            'offenceDate' => '2012-03-10T00:00:00+0000',
                            'convictionDate' => '2012-06-15T00:00:00+0100',
                            'name' => 'Bob Smith',
                            'categoryText' => null,
                            'court' => 'FPN',
                            'penalty' => '3 points on licence',
                            'msi' => 'N',
                            'isDeclared' => 'N',
                            'isDealtWith' => 'N',
                            'defendantType' => [
                                'id' => 'def_t_owner',
                                'description' => 'Owner'
                            ],
                        ]
                    ]
                ],
            ],
            [   // case-outline
                [ // input
                    'caseId' => 24,
                    'sectionId' => 'case-outline',
                    'sectionConfig' => [
                        'service' => 'Cases',
                        'bundle' => ['some_bundle'],
                    ]
                ],
                [ // expected
                    'loadedCaseSectionData' => [
                        'description' => 'test description'
                    ],
                    'filteredSectionData' => [
                        'outline' => 'test description',
                    ]
                ]
            ],
            [   // case-summary
                [ // input
                    'caseId' => 24,
                    'sectionId' => 'case-summary',
                    'sectionConfig' => [
                        'service' => 'Cases',
                        'bundle' => ['some_bundle'],
                    ]
                ],
                [ // expected
                    'loadedCaseSectionData' => $this->getCaseSummaryMockData(),
                    'filteredSectionData' => [
                        'id' => 24,
                        'organisationName' => 'John Smith Haulage Ltd.',
                        'isMlh' => 'Y',
                        'organisationType' => 'Registered Company',
                        'businessType' => null,
                        'caseType' => 'case_t_lic',
                        'ecmsNo' => 'E123456',
                        'licNo' => 'OB1234567',
                        'licenceStartDate' => '2010-01-12T00:00:00+0000',
                        'licenceType' => 'Standard National',
                        'goodsOrPsv' => 'Goods Vehicle',
                        'serviceStandardDate' => null,
                        'licenceStatus' => 'New',
                        'totAuthorisedVehicles' => 12,
                        'totAuthorisedTrailers' => 4,
                        'vehiclesInPossession' => 4,
                        'trailersInPossession' => 4,

                    ]
                ]
            ]
        ];
    }


    public function providerSubmissionSectionPrebuiltData()
    {
        return [
            [
                [
                    'caseId' => 24,
                    'sectionId' => 'persons',
                    'sectionConfig' => [
                        'bundle' => 'case-summary',
                    ]
                ],
                [
                    'loadedCaseSectionData' => $this->getCaseSummaryMockData(),
                    'filteredSectionData' => [
                        0 => [
                            'title' => '',
                            'forename' => 'Tom',
                            'familyName' => 'Jones',
                            'birthDate' => '1972-02-15T00:00:00+0100',
                        ],
                        1 => [
                            'title' => '',
                            'forename' => 'Keith',
                            'familyName' => 'Winnard',
                            'birthDate' => '1975-03-15T00:00:00+0100',
                        ]
                    ]
                ]
            ]
        ];
    }

    public function providerSubmissions()
    {
        return [
            [
                [
                    'dataSnapshot' =>
                        '[{"sectionId":"introduction","data":[]}]',
                    'submissionSectionComments' =>
                        []
                ],
                [ 0 => [
                    'sectionId' => 'introduction',
                    'description' => 'Introduction',
                    'data' => [],
                    'comments' => []
                    ]
                ]
            ]
        ];
    }

    private function getMockSubmissionTitles()
    {
        return
            array (
                0 =>
                    array (
                        'description' => 'MLH Submission',
                        'refDataCategoryId' => 'submission_type_title',
                        'olbsKey' => null,
                        'displayOrder' => 1,
                        'id' => 'submission_type_t_mlh',
                        'parent' => null,
                    ),
                1 =>
                    array (
                        'description' => 'Licencing (G) Submission',
                        'refDataCategoryId' => 'submission_type_title',
                        'olbsKey' => null,
                        'displayOrder' => 2,
                        'id' => 'submission_type_t_clo_g',
                        'parent' => null,
                    ),
                2 =>
                    array (
                        'description' => 'Licencing (PSV) Submission',
                        'refDataCategoryId' => 'submission_type_title',
                        'olbsKey' => null,
                        'displayOrder' => 3,
                        'id' => 'submission_type_t_clo_psv',
                        'parent' => null,
                    ),
                3 =>
                    array (
                        'description' => 'Licencing Fees Submission',
                        'refDataCategoryId' => 'submission_type_title',
                        'olbsKey' => null,
                        'displayOrder' => 4,
                        'id' => 'submission_type_t_clo_fep',
                        'parent' => null,
                    ),
                4 =>
                    array (
                        'description' => 'Compliance submission',
                        'refDataCategoryId' => 'submission_type_title',
                        'olbsKey' => null,
                        'displayOrder' => 5,
                        'id' => 'submission_type_t_otc',
                        'parent' => null,
                    ),
                5 =>
                    array (
                        'description' => 'ENV Submission',
                        'refDataCategoryId' => 'submission_type_title',
                        'olbsKey' => null,
                        'displayOrder' => 6,
                        'id' => 'submission_type_t_env',
                        'parent' => null,
                    ),
                6 =>
                    array (
                        'description' => 'IRFO Submission',
                        'refDataCategoryId' => 'submission_type_title',
                        'olbsKey' => null,
                        'displayOrder' => 7,
                        'id' => 'submission_type_t_irfo',
                        'parent' => null,
                    ),
                7 =>
                    array (
                        'description' => 'Bus Registration Submission',
                        'refDataCategoryId' => 'submission_type_title',
                        'olbsKey' => null,
                        'displayOrder' => 8,
                        'id' => 'submission_type_t_bus_reg',
                        'parent' => null,
                    ),
                8 =>
                    array (
                        'description' => 'TM Only Submission',
                        'refDataCategoryId' => 'submission_type_title',
                        'olbsKey' => null,
                        'displayOrder' => 9,
                        'id' => 'submission_type_t_tm',
                        'parent' => null,
                    ),
            );
    }

    private function getMockSectionRefData()
    {
        return array (
            'introduction' => 'Introduction',
            'case-summary' => 'Case summary',
            'case-outline' => 'Case outline',
            'most-serious-infringement' => 'Most serious infringement',
            'persons' => 'Persons',
            'operating-centres' => 'Operating centres',
            'operating-centre-history' => 'Operating centre history',
            'conditions-and-undertakings' => 'Conditions and undertakings',
            'intelligent-unit-check' => 'Intelligence unit check',
            'interim' => 'Interim',
            'advertisement' => 'Advertisement',
            'linked-licences-app-numbers' => 'Linked licences & application numbers',
            'all-auths' => 'All auths',
            'lead-tc-area' => 'Lead TC area',
            'current-submissions' => 'Current submissions',
            'auth-requested-applied-for' => 'Authorisation requested / applied for',
            'transport-managers' => 'Transport managers',
            'continuous-effective-control' => 'Continuous and effective control',
            'fitness-and-repute' => 'Fitness & repute',
            'previous-history' => 'Previous history',
            'bus-reg-app-details' => 'Bus registration application details',
            'transport-authority-comments' => 'Transport authority comments',
            'total-bus-registrations' => 'Total bus registrations',
            'local-licence-history' => 'Local licence history',
            'linked-mlh-history' => 'Linked MLH history',
            'registration-details' => 'Registration details',
            'maintenance-tachographs-hours' => 'Maintenance / Tachographs / Drivers hours',
            'prohibition-history' => 'Prohibition history',
            'conviction-fpn-offence-history' => 'Conviction / FPN / Offence history',
            'annual-test-history' => 'Annual test history',
            'penalties' => 'Penalties',
            'other-issues' => 'Other issues / misc',
            'te-reports' => 'TE reports',
            'site-plans' => 'Site plans',
            'planning-permission' => 'Planning permission',
            'applicants-comments' => 'Applicants comments',
            'visibility-access-egress-size' => 'Visibility / access egress size',
            'case-complaints' => 'Case complaints',
            'environmental-complaints' => 'Environmental complaints',
            'representations' => 'Representations',
            'objections' => 'Objections',
            'financial-information' => 'Financial information',
            'maps' => 'Maps',
            'waive-fee-late-fee' => 'Waive fee / Late fee',
            'surrender' => 'Surrender',
            'annex' => 'Annex',
        );
    }

    private function getCaseSummaryMockData()
    {
        return [
            'ecmsNo' => 'E123456',
            'description' => 'Case for convictions against company directors',
            'id' => 24,
            'caseType' =>
                [
                    'id' => 'case_t_lic',
                ],
            'licence' => [
                'licNo' => 'OB1234567',
                'trailersInPossession' => null,
                'totAuthTrailers' => 4,
                'totAuthVehicles' => 12,
                'inForceDate' => '2010-01-12T00:00:00+0000',
                'status' => [
                    'description' => 'New',
                    'id' => 'lsts_new',
                ],
                'organisation' => [
                    'isMlh' => 'Y',
                    'name' => 'John Smith Haulage Ltd.',
                    'sicCode' => null,
                    'type' =>
                        [
                            'description' => 'Registered Company',
                            'id' => 'org_t_rc',
                        ],
                    'organisationPersons' => [
                        0 => [
                            'person' => [
                                'title' => '',
                                'forename' => 'Tom',
                                'familyName' => 'Jones',
                                'birthDate' => '1972-02-15T00:00:00+0100',
                            ],
                        ],
                        1 => [
                            'person' => [
                                'title' => '',
                                'forename' => 'Keith',
                                'familyName' => 'Winnard',
                                'birthDate' => '1975-03-15T00:00:00+0100',
                            ]
                        ]
                    ]
                ],
                'licenceVehicles' => [
                    0 => [
                        'id' => 1,
                        'deletedDate' => null,
                        'specifiedDate' => '2014-02-20T00:00:00+0000',
                    ],
                    1 => [
                        'id' => 2,
                        'deletedDate' => null,
                        'specifiedDate' => '2014-02-20T00:00:00+0000',
                    ],
                    2 => [
                        'id' => 3,
                        'deletedDate' => null,
                        'specifiedDate' => '2014-02-20T00:00:00+0000',
                    ],
                    3 => [
                        'id' => 4,
                        'deletedDate' => null,
                        'specifiedDate' => '2014-02-20T00:00:00+0000',
                    ],
                ],
                'licenceType' => [
                    'description' => 'Standard National',
                    'id' => 'ltyp_sn',
                ],
                'goodsOrPsv' => [
                    'description' => 'Goods Vehicle',
                ],
            ],
        ];
    }
}
