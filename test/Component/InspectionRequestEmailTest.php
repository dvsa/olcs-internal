<?php

/**
 * Inspection Request Email Test
 *
 * @author Dan Eggleston <dan@stolenegg.com>
 */
namespace OlcsComponentTest;

use PHPUnit_Framework_TestCase;
use Olcs\View\Model\Email\InspectionRequest as InspectionRequestEmailViewModel;
use Zend\View\Renderer\PhpRenderer;
use Zend\View\Resolver;

/**
 * Inspection Request Email Test
 *
 * @author Dan Eggleston <dan@stolenegg.com>
 */
class InspectionRequestEmailTest extends PHPUnit_Framework_TestCase
{
    /**
     * @group component
     *
     * Test the email template view renders content in expected format
     */
    public function testView()
    {
        // set up the renderer
        $renderer = new PhpRenderer();
        $resolver = new Resolver\TemplatePathStack(
            [
                'script_paths' => [
                    __DIR__ . '/../../module/Olcs/view',
                ],
            ]
        );
        $renderer->setResolver($resolver);

        $model = new InspectionRequestEmailViewModel();

        $model->setVariables($this->getViewData());

        $this->assertEquals($this->getExpectedOutput(), $renderer->render($model));
    }

    protected function getViewData()
    {
        $data = [
            'inspectionRequestId' => '189781',
            'currentUserName' => 'Hugh Janus',
            'currentUserEmail' => 'hugh@example.com',
            'inspectionRequestDateRequested' => '17/04/2015 14:13:56',
            'inspectionRequestNotes' => 'Lorem ipsum dolor',
            'inspectionRequestDueDate' => '18/04/2015 14:13:56',
            'ocAddress' => [
                'addressLine1' => 'DVSA',
                'town' => 'Leeds',
                'postcode' => 'LS9 6NF',
            ],
            'inspectionRequestType' => 'New OP',
            'licenceNumber' => 'OB1234567',
            'licenceType' => 'Standard National',
            'totAuthVehicles' => 5,
            'totAuthTrailers' => 6,
            'numberOfOperatingCentres' => 2,
            'expiryDate' => '31/12/2020',
            'operatorId' => 1,
            'operatorName' => 'Big Old Trucks Ltd.',
            'operatorEmail' => 'bigoldtrucks@example.com',
            'operatorAddress' => [
                'addressLine1' => 'Big Old House',
                'town' => 'Leeds',
                'postcode' => 'LS1 3AD',
            ],
            'contactPhoneNumbers' => [
                0 => [
                    'phoneNumber' => '0113 2345678',
                    'phoneContactType' => [
                        'description' => 'Business',
                    ],
                ],
                1 => [
                    'phoneNumber' => '07878 123456',
                    'phoneContactType' => [
                        'description' => 'Mobile',
                    ],
                ]
            ],
            'tradingNames' => [
                'Big Ol\' Wagons',
                'Keep On Trucking',
            ],
            'workshopIsExternal' => true,
            'safetyInspectionVehicles' => 7,
            'safetyInspectionTrailers' => 14,
            'inspectionProvider' => [
                'address' => [
                    'addressLine1' => 'Inspector Gadget House',
                    'town' => 'Doncaster',
                    'postcode' => 'DN1 1QZ',
                ],
            ],
            'people' => [
                0 => [
                    'forename' => 'Norma',
                    'familyName' => 'Snockers',
                ],
                1 => [
                    'forename' => 'Juan',
                    'familyName' => 'Kerr',
                ],
            ],
            'otherLicences' => [
                'OB1234568',
                'OB1234569',
            ],
            'applicationOperatingCentres' => [
                0 => [
                    'operatingCentre' => [
                        'address' => [
                            'addressLine1' => 'Centre One',
                            'town' => 'Leeds',
                        ],
                    ],
                    'noOfVehiclesRequired' => 2,
                    'noOfTrailersRequired' => 4,
                    'action' => 'Added',
                ],
                1 => [
                    'operatingCentre' => [
                        'address' => [
                            'addressLine1' => 'Centre Two',
                            'town' => 'Bradford',
                        ],
                    ],
                    'noOfVehiclesRequired' => 3,
                    'noOfTrailersRequired' => 2,
                    'action' => 'Added',
                ],
            ],
        ];

        return $data;
    }

    protected function getExpectedOutput()
    {
        $expected = <<<STR
Result_Type=
When replying to this sender, append to the subject line with:
'S' (if Inspection is Satisfactory)
'U' (if Inspection is Unsatisfactory)

INSPECTION DETAILS for request ID 189781...
Requested By:                   Hugh Janus        (E-Mail - hugh@example.com) 
Date Requested:                 17/04/2015 14:13:56 
Caseworker Notes...
Lorem ipsum dolor 

Date Required:                  18/04/2015 14:13:56 
Op Centre:                      DVSA 
                                Leeds 
                                LS9 6NF 
Request Type:                   New OP 
Licence No:                     OB1234567 
Licence Type:                   Standard National 
Authorised Vehicles:            5 
Authorised Trailers:            6 

#Centres on:                    2 
Licence
Expiry Date:                    31/12/2020 
Operator ID:                    1 
Operator Name                   Big Old Trucks Ltd. 
Operator Email Address          bigoldtrucks@example.com 
Address:                        Big Old House 
                                Leeds 
                                LS1 3AD 

Contact Phone No(s)...
0113 2345678 (Business)
07878 123456 (Mobile)

Trading Name(s)...
Big Ol' Wagons
Keep On Trucking

Maintenance...
Maintained:                     Contracted Out 
Inspection Periods...
Vehicles:                       7 
Trailers:                       14 

Workshops/Garage Address(es)...
Address:                        Inspector Gadget House 
                                Doncaster 
                                DN1 1QZ 

Partners/Directors...
Norma Snockers
Juan Kerr

Associated Licences...
OB1234568
OB1234569

Application Details...
Centre One, Leeds...
#Vehicles                       2 
#Trailers                       4 
Action                          Added
Centre Two, Bradford...
#Vehicles                       3 
#Trailers                       2 
Action                          Added

STR;
        return $expected;
    }
}
