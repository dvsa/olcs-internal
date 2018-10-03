<?php

namespace AdminTest\Data\Mapper;

use Mockery as m;
use Mockery\Adapter\Phpunit\MockeryTestCase;
use Admin\Data\Mapper\ScoringResultExport as Sut;
use Zend\Form\Form;

class ScoringResultExportTest extends MockeryTestCase
{
    public function testMapFromResult()
    {
        $firstPermitId = '1';
        $firstIrhpApplicationId = '101';
        $firstLicenceNo = 'OB1111';
        $firstOrganisationName = 'Testing Inc.';
        $firstAppScore = '1.1';
        $firstIntensity = '0.8';
        $firstRandomFactor = '0.2';
        $firstRandomizedScore = '2.9';

        $secondTrafficAreaName = 'Rule Britannia';
        $secondSectorName = 'Some Sector Test';


        $input = [
            'results' => [
                0 => [
                    'id' => $firstPermitId,
                    'applicationScore' => $firstAppScore,
                    'intensityOfUse' => $firstIntensity,
                    'randomFactor' => $firstRandomFactor,
                    'randomizedScore' => $firstRandomizedScore,
                    'successful' => true,
                    'irhpPermitApplication' => [
                        'id' => $firstIrhpApplicationId,
                        'ecmtPermitApplication' => [
                            'sectors' => [
                                'name' => 'None/More than one of these sectors'
                            ],
                            'internationalJourneys' => [
                                'id' => 'inter_journey_less_60'
                            ],
                            'hasRestrictedCountries' => false //don't need to specify countries because this is false
                        ],
                        'licence' => [
                            'licNo' => $firstLicenceNo,
                            'organisation' => [
                                'name' => $firstOrganisationName
                            ],
                            'trafficArea' => [
                                'id' => 'X',
                                'name' => ''
                            ]
                        ]
                    ]
                ],
                1 => [
                    'id' => $firstPermitId,
                    'applicationScore' => $firstAppScore,
                    'intensityOfUse' => $firstIntensity,
                    'randomFactor' => $firstRandomFactor,
                    'randomizedScore' => $firstRandomizedScore,
                    'successful' => true,
                    'irhpPermitApplication' => [
                        'id' => $firstIrhpApplicationId,
                        'ecmtPermitApplication' => [
                            'sectors' => [
                                'name' => $secondSectorName
                            ],
                            'internationalJourneys' => [
                                'id' => 'inter_journey_less_60'
                            ],
                            'hasRestrictedCountries' => false //don't need to specify countries because this is false
                        ],
                        'licence' => [
                            'licNo' => $firstLicenceNo,
                            'organisation' => [
                                'name' => $firstOrganisationName
                            ],
                            'trafficArea' => [
                                'id' => 'M',
                                'name' => $secondTrafficAreaName
                            ]
                        ]
                    ]
                ]
            ]
        ];

        $output = Sut::mapFromResult($input);
        $output = $output['results'];

        $this->assertEquals($output[0]['permitRef'], $firstLicenceNo.'/'.$firstIrhpApplicationId.'/'.$firstPermitId);
        $this->assertEquals($output[0]['organisation'], $firstOrganisationName);
        $this->assertEquals($output[0]['applicationScore'], $firstAppScore);
        $this->assertEquals($output[0]['intensityOfUse'], $firstIntensity);
        $this->assertEquals($output[0]['randomFactor'], $firstRandomFactor);
        $this->assertEquals($output[0]['randomizedScore'], $firstRandomizedScore);
        $this->assertEquals($output[0]['internationalJourneys'], 0.3); //derived from ID of inter_journey_less_60
        $this->assertEquals($output[0]['sector'], 'N/A'); //sector name for 'NONE' should result in N/A
        $this->assertEquals($output[0]['devolvedAdministration'], 'N/A'); //Id of X should result in N/A
        $this->assertEquals($output[0]['result'], 'Successful'); //True should result in 'Successful'
        $this->assertEquals($output[0]['restrictedCountriesRequested'], 'N/A');//hasRestrictedCountries = false result N/A

        $this->assertEquals($output[1]['devolvedAdministration'], $secondTrafficAreaName);
        $this->assertEquals($output[1]['sector'], $secondSectorName);
    }
}
