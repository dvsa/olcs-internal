<?php
namespace OlcsTest\Data\Mapper;

use Mockery as m;
use Mockery\Adapter\Phpunit\MockeryTestCase;
use Olcs\Data\Mapper\User as Sut;
use Zend\Form\Form;

/**
 * User Mapper Test
 */
class UserTest extends MockeryTestCase
{
    /**
    * @dataProvider mapFromResultDataProvider
    *
    * @param $inData
    * @param $expected
    */
    public function testMapFromResult($inData, $expected)
    {
        $this->assertEquals($expected, Sut::mapFromResult($inData));
    }

    public function mapFromResultDataProvider()
    {
        return [
            // add
            [
                [],
                []
            ],
            // edit - internal
            [
                [
                    'id' => 987,
                    'version' => 1,
                    'loginId' => 'testuser',
                    'createdOn' => '2012-06-01 17:11:12',
                    'accountDisabled' => 'Y',
                    'disabledDate' => '2015-06-07 17:11:12',
                    'userType' => 'internal',
                    'roles' => [
                        [
                            'id' => 99,
                            'role' => 'role',
                        ]
                    ],
                    'team' => [
                        'id' => 3
                    ],
                    'contactDetails' => [
                        'person' => [
                            'forename' => 'fn1',
                            'familyName' => 'ln1',
                            'birthDate' => '2012-03-01',
                        ],
                        'emailAddress' => 'test@test.me',
                        'address' => [
                            'id' => 200,
                            'version' => 1,
                            'addressLine1' => 'a1'
                        ],
                        'phoneContacts' => [
                            [
                                'id' => 301,
                                'version' => 1,
                                'phoneContactType' => ['id' => 'phone_t_tel'],
                                'phoneNumber' => 'pn1',
                            ],
                            [
                                'id' => 304,
                                'version' => 4,
                                'phoneContactType' => ['id' => 'phone_t_fax'],
                                'phoneNumber' => 'pn2',
                            ],
                        ],
                    ],
                    'translateToWelsh' => 'Y',
                ],
                [
                    'id' => 987,
                    'version' => 1,
                    'userLoginSecurity' => [
                        'loginId' => 'testuser',
                        'createdOn' => '01/06/2012 17:11:12',
                        'accountDisabled' => 'Y',
                        'disabledDate' => '07/06/2015 17:11:12',
                    ],
                    'userType' => [
                        'id' => 987,
                        'userType' => 'internal',
                        'role' => 'role',
                        'team' => 3,
                    ],
                    'userPersonal' => [
                        'forename' => 'fn1',
                        'familyName' => 'ln1',
                        'birthDate' => '2012-03-01',
                    ],
                    'userContactDetails' => [
                        'emailAddress' => 'test@test.me',
                        'emailConfirm' => 'test@test.me',
                        'phone_business' => 'pn1',
                        'phone_business_id' => 301,
                        'phone_business_version' => 1,
                        'phone_fax' => 'pn2',
                        'phone_fax_id' => 304,
                        'phone_fax_version' => 4,
                    ],
                    'address' => [
                        'id' => 200,
                        'version' => 1,
                        'addressLine1' => 'a1'
                    ],
                    'userSettings' => [
                        'translateToWelsh' => 'Y',
                    ],
                ]
            ],
            // edit - transport-manager
            [
                [
                    'id' => 987,
                    'version' => 1,
                    'loginId' => 'testuser',
                    'createdOn' => '2012-06-01 17:11:12',
                    'accountDisabled' => 'Y',
                    'userType' => 'transport-manager',
                    'roles' => [
                        [
                            'id' => 99,
                            'role' => 'role',
                        ]
                    ],
                    'transportManager' => [
                        'id' => 3,
                        'homeCd' => [
                            'person' => [
                                'forename' => 'test',
                                'familyName' => 'me'
                            ]
                        ]
                    ],
                    'contactDetails' => [
                        'person' => [
                            'forename' => 'fn1',
                            'familyName' => 'ln1',
                            'birthDate' => '2012-03-01',
                        ],
                        'emailAddress' => 'test@test.me',
                        'address' => [
                            'id' => 200,
                            'version' => 1,
                            'addressLine1' => 'a1'
                        ],
                    ],
                    'translateToWelsh' => 'N',
                ],
                [
                    'id' => 987,
                    'version' => 1,
                    'userLoginSecurity' => [
                        'createdOn' => '01/06/2012 17:11:12',
                        'loginId' => 'testuser',
                        'accountDisabled' => 'Y',
                    ],
                    'userType' => [
                        'id' => 987,
                        'userType' => 'transport-manager',
                        'role' => 'role',
                        'currentTransportManager' => 3,
                        'currentTransportManagerName' => 'test me',
                    ],
                    'userPersonal' => [
                        'forename' => 'fn1',
                        'familyName' => 'ln1',
                        'birthDate' => '2012-03-01',
                    ],
                    'userContactDetails' => [
                        'emailAddress' => 'test@test.me',
                        'emailConfirm' => 'test@test.me',
                    ],
                    'address' => [
                        'id' => 200,
                        'version' => 1,
                        'addressLine1' => 'a1'
                    ],
                    'userSettings' => [
                        'translateToWelsh' => 'N',
                    ],
                ]
            ],
            // edit - partner
            [
                [
                    'id' => 987,
                    'version' => 1,
                    'loginId' => 'testuser',
                    'createdOn' => '2012-06-01 17:11:12',
                    'accountDisabled' => 'Y',
                    'userType' => 'partner',
                    'roles' => [
                        [
                            'id' => 99,
                            'role' => 'role',
                        ]
                    ],
                    'partnerContactDetails' => [
                        'id' => 3
                    ],
                    'contactDetails' => [
                        'person' => [
                            'forename' => 'fn1',
                            'familyName' => 'ln1',
                            'birthDate' => '2012-03-01',
                        ],
                        'emailAddress' => 'test@test.me',
                        'address' => [
                            'id' => 200,
                            'version' => 1,
                            'addressLine1' => 'a1'
                        ],
                    ],
                    'translateToWelsh' => 'N',
                ],
                [
                    'id' => 987,
                    'version' => 1,
                    'userLoginSecurity' => [
                        'loginId' => 'testuser',
                        'createdOn' => '01/06/2012 17:11:12',
                        'accountDisabled' => 'Y',
                    ],
                    'userType' => [
                        'id' => 987,
                        'userType' => 'partner',
                        'role' => 'role',
                        'partnerContactDetails' => 3,
                    ],
                    'userPersonal' => [
                        'forename' => 'fn1',
                        'familyName' => 'ln1',
                        'birthDate' => '2012-03-01',
                    ],
                    'userContactDetails' => [
                        'emailAddress' => 'test@test.me',
                        'emailConfirm' => 'test@test.me',
                    ],
                    'address' => [
                        'id' => 200,
                        'version' => 1,
                        'addressLine1' => 'a1'
                    ],
                    'userSettings' => [
                        'translateToWelsh' => 'N',
                    ],
                ]
            ],
            // edit - local-authority
            [
                [
                    'id' => 987,
                    'version' => 1,
                    'loginId' => 'testuser',
                    'createdOn' => '2012-06-01 17:11:12',
                    'accountDisabled' => 'Y',
                    'userType' => 'local-authority',
                    'roles' => [
                        [
                            'id' => 99,
                            'role' => 'role',
                        ]
                    ],
                    'localAuthority' => [
                        'id' => 3
                    ],
                    'contactDetails' => [
                        'person' => [
                            'forename' => 'fn1',
                            'familyName' => 'ln1',
                            'birthDate' => '2012-03-01',
                        ],
                        'emailAddress' => 'test@test.me',
                        'address' => [
                            'id' => 200,
                            'version' => 1,
                            'addressLine1' => 'a1'
                        ],
                    ],
                    'translateToWelsh' => 'N',
                ],
                [
                    'id' => 987,
                    'version' => 1,
                    'userLoginSecurity' => [
                        'loginId' => 'testuser',
                        'createdOn' => '01/06/2012 17:11:12',
                        'accountDisabled' => 'Y',
                    ],
                    'userType' => [
                        'id' => 987,
                        'userType' => 'local-authority',
                        'role' => 'role',
                        'localAuthority' => 3,
                    ],
                    'userPersonal' => [
                        'forename' => 'fn1',
                        'familyName' => 'ln1',
                        'birthDate' => '2012-03-01',
                    ],
                    'userContactDetails' => [
                        'emailAddress' => 'test@test.me',
                        'emailConfirm' => 'test@test.me',
                    ],
                    'address' => [
                        'id' => 200,
                        'version' => 1,
                        'addressLine1' => 'a1'
                    ],
                    'userSettings' => [
                        'translateToWelsh' => 'N',
                    ],
                ]
            ],
        ];
    }

    /**
    * @dataProvider mapFromFormDataProvider
    *
    * @param $inData
    * @param $expected
    */
    public function testMapFromForm($inData, $expected)
    {
        $this->assertEquals($expected, Sut::mapFromForm($inData));
    }

    public function mapFromFormDataProvider()
    {
        return [
            // edit - internal
            [
                [
                    'id' => 987,
                    'version' => 1,
                    'userLoginSecurity' => [
                        'loginId' => 'testuser',
                        'accountDisabled' => 'Y',
                        'resetPassword' => 'Y',
                    ],
                    'userType' => [
                        'userType' => 'internal',
                        'role' => 'role',
                        'team' => 3,
                    ],
                    'userPersonal' => [
                        'forename' => 'fn1',
                        'familyName' => 'ln1',
                        'birthDate' => '2012-03-01',
                    ],
                    'userContactDetails' => [
                        'emailAddress' => 'test@test.me',
                        'emailConfirm' => 'test@test.me',
                        'phone_business' => 'pn1',
                        'phone_business_id' => 301,
                        'phone_business_version' => 1,
                        'phone_fax' => 'pn2',
                        'phone_fax_id' => 304,
                        'phone_fax_version' => 4,
                    ],
                    'address' => [
                        'addressLine1' => 'a1'
                    ],
                    'userSettings' => [
                        'translateToWelsh' => 'Y',
                    ],
                ],
                [
                    'id' => 987,
                    'version' => 1,
                    'loginId' => 'testuser',
                    'accountDisabled' => 'Y',
                    'resetPassword' => 'Y',
                    'userType' => 'internal',
                    'roles' => ['role'],
                    'team' => 3,
                    'contactDetails' => [
                        'person' => [
                            'forename' => 'fn1',
                            'familyName' => 'ln1',
                            'birthDate' => '2012-03-01',
                        ],
                        'emailAddress' => 'test@test.me',
                        'address' => [
                            'addressLine1' => 'a1'
                        ],
                        'phoneContacts' => [
                            [
                                'id' => 301,
                                'version' => 1,
                                'phoneContactType' => 'phone_t_tel',
                                'phoneNumber' => 'pn1',
                            ],
                            [
                                'id' => 304,
                                'version' => 4,
                                'phoneContactType' => 'phone_t_fax',
                                'phoneNumber' => 'pn2',
                            ],
                        ],
                    ],
                    'translateToWelsh' => 'Y',
                ],
            ],
            // edit - transport-manager
            [
                [
                    'id' => 987,
                    'version' => 1,
                    'userLoginSecurity' => [
                        'loginId' => 'testuser',
                        'accountDisabled' => 'Y',
                        'resetPassword' => 'N',
                    ],
                    'userType' => [
                        'userType' => 'transport-manager',
                        'role' => 'role',
                        'applicationTransportManagers' => ['application' => 97],
                        'transportManager' => 3,
                    ],
                    'userPersonal' => [
                        'forename' => 'fn1',
                        'familyName' => 'ln1',
                        'birthDate' => '2012-03-01',
                    ],
                    'userContactDetails' => [
                        'emailAddress' => 'test@test.me',
                        'emailConfirm' => 'test@test.me',
                        'phone_business' => 'pn1',
                        'phone_business_id' => 301,
                        'phone_business_version' => 1,
                        'phone_fax' => 'pn2',
                        'phone_fax_id' => 304,
                        'phone_fax_version' => 4,
                    ],
                    'address' => [
                        'addressLine1' => 'a1'
                    ],
                    'userSettings' => [
                        'translateToWelsh' => 'N',
                    ],
                ],
                [
                    'id' => 987,
                    'version' => 1,
                    'loginId' => 'testuser',
                    'accountDisabled' => 'Y',
                    'resetPassword' => 'N',
                    'userType' => 'transport-manager',
                    'roles' => ['role'],
                    'application' => 97,
                    'transportManager' => 3,
                    'contactDetails' => [
                        'person' => [
                            'forename' => 'fn1',
                            'familyName' => 'ln1',
                            'birthDate' => '2012-03-01',
                        ],
                        'emailAddress' => 'test@test.me',
                        'address' => [
                            'addressLine1' => 'a1'
                        ],
                        'phoneContacts' => [
                            [
                                'id' => 301,
                                'version' => 1,
                                'phoneContactType' => 'phone_t_tel',
                                'phoneNumber' => 'pn1',
                            ],
                            [
                                'id' => 304,
                                'version' => 4,
                                'phoneContactType' => 'phone_t_fax',
                                'phoneNumber' => 'pn2',
                            ],
                        ],
                    ],
                    'translateToWelsh' => 'N',
                ],
            ],
            // edit - partner
            [
                [
                    'id' => 987,
                    'version' => 1,
                    'userLoginSecurity' => [
                        'loginId' => 'testuser',
                        'accountDisabled' => 'Y',
                        'resetPassword' => 'N',
                    ],
                    'userType' => [
                        'userType' => 'partner',
                        'role' => 'role',
                        'partnerContactDetails' => 3,
                    ],
                    'userPersonal' => [
                        'forename' => 'fn1',
                        'familyName' => 'ln1',
                        'birthDate' => '2012-03-01',
                    ],
                    'userContactDetails' => [
                        'emailAddress' => 'test@test.me',
                        'emailConfirm' => 'test@test.me',
                        'phone_business' => 'pn1',
                        'phone_business_id' => 301,
                        'phone_business_version' => 1,
                        'phone_fax' => 'pn2',
                        'phone_fax_id' => 304,
                        'phone_fax_version' => 4,
                    ],
                    'address' => [
                        'addressLine1' => 'a1'
                    ],
                    'userSettings' => [
                        'translateToWelsh' => 'N',
                    ],
                ],
                [
                    'id' => 987,
                    'version' => 1,
                    'loginId' => 'testuser',
                    'accountDisabled' => 'Y',
                    'resetPassword' => 'N',
                    'userType' => 'partner',
                    'roles' => ['role'],
                    'partnerContactDetails' => 3,
                    'contactDetails' => [
                        'person' => [
                            'forename' => 'fn1',
                            'familyName' => 'ln1',
                            'birthDate' => '2012-03-01',
                        ],
                        'emailAddress' => 'test@test.me',
                        'address' => [
                            'addressLine1' => 'a1'
                        ],
                        'phoneContacts' => [
                            [
                                'id' => 301,
                                'version' => 1,
                                'phoneContactType' => 'phone_t_tel',
                                'phoneNumber' => 'pn1',
                            ],
                            [
                                'id' => 304,
                                'version' => 4,
                                'phoneContactType' => 'phone_t_fax',
                                'phoneNumber' => 'pn2',
                            ],
                        ],
                    ],
                    'translateToWelsh' => 'N',
                ],
            ],
            // edit - local-authority
            [
                [
                    'id' => 987,
                    'version' => 1,
                    'userLoginSecurity' => [
                        'loginId' => 'testuser',
                        'accountDisabled' => 'Y',
                        'resetPassword' => 'N',
                    ],
                    'userType' => [
                        'userType' => 'local-authority',
                        'role' => 'role',
                        'localAuthority' => 3,
                    ],
                    'userPersonal' => [
                        'forename' => 'fn1',
                        'familyName' => 'ln1',
                        'birthDate' => '2012-03-01',
                    ],
                    'userContactDetails' => [
                        'emailAddress' => 'test@test.me',
                        'emailConfirm' => 'test@test.me',
                        'phone_business' => 'pn1',
                        'phone_business_id' => 301,
                        'phone_business_version' => 1,
                        'phone_fax' => 'pn2',
                        'phone_fax_id' => 304,
                        'phone_fax_version' => 4,
                    ],
                    'address' => [
                        'addressLine1' => 'a1'
                    ],
                    'userSettings' => [
                        'translateToWelsh' => 'N',
                    ],
                ],
                [
                    'id' => 987,
                    'version' => 1,
                    'loginId' => 'testuser',
                    'accountDisabled' => 'Y',
                    'resetPassword' => 'N',
                    'userType' => 'local-authority',
                    'roles' => ['role'],
                    'localAuthority' => 3,
                    'contactDetails' => [
                        'person' => [
                            'forename' => 'fn1',
                            'familyName' => 'ln1',
                            'birthDate' => '2012-03-01',
                        ],
                        'emailAddress' => 'test@test.me',
                        'address' => [
                            'addressLine1' => 'a1'
                        ],
                        'phoneContacts' => [
                            [
                                'id' => 301,
                                'version' => 1,
                                'phoneContactType' => 'phone_t_tel',
                                'phoneNumber' => 'pn1',
                            ],
                            [
                                'id' => 304,
                                'version' => 4,
                                'phoneContactType' => 'phone_t_fax',
                                'phoneNumber' => 'pn2',
                            ],
                        ],
                    ],
                    'translateToWelsh' => 'N',
                ],
            ],
            // edit - operator
            [
                [
                    'id' => 987,
                    'version' => 1,
                    'userLoginSecurity' => [
                        'loginId' => 'testuser',
                        'accountDisabled' => 'Y',
                        'resetPassword' => 'N',
                    ],
                    'userType' => [
                        'userType' => 'operator',
                        'role' => 'role',
                        'licenceNumber' => 'licNo',
                    ],
                    'userPersonal' => [
                        'forename' => 'fn1',
                        'familyName' => 'ln1',
                        'birthDate' => '2012-03-01',
                    ],
                    'userContactDetails' => [
                        'emailAddress' => 'test@test.me',
                        'emailConfirm' => 'test@test.me',
                        'phone_business' => 'pn1',
                        'phone_business_id' => 301,
                        'phone_business_version' => 1,
                        'phone_fax' => 'pn2',
                        'phone_fax_id' => 304,
                        'phone_fax_version' => 4,
                    ],
                    'address' => [
                        'addressLine1' => 'a1'
                    ],
                    'userSettings' => [
                        'translateToWelsh' => 'N',
                    ],
                ],
                [
                    'id' => 987,
                    'version' => 1,
                    'loginId' => 'testuser',
                    'accountDisabled' => 'Y',
                    'resetPassword' => 'N',
                    'userType' => 'operator',
                    'roles' => ['role'],
                    'licenceNumber' => 'licNo',
                    'contactDetails' => [
                        'person' => [
                            'forename' => 'fn1',
                            'familyName' => 'ln1',
                            'birthDate' => '2012-03-01',
                        ],
                        'emailAddress' => 'test@test.me',
                        'address' => [
                            'addressLine1' => 'a1'
                        ],
                        'phoneContacts' => [
                            [
                                'id' => 301,
                                'version' => 1,
                                'phoneContactType' => 'phone_t_tel',
                                'phoneNumber' => 'pn1',
                            ],
                            [
                                'id' => 304,
                                'version' => 4,
                                'phoneContactType' => 'phone_t_fax',
                                'phoneNumber' => 'pn2',
                            ],
                        ],
                    ],
                    'translateToWelsh' => 'N',
                ],
            ],
        ];
    }


    public function testMapFromErrors()
    {
        $errors = [
            'messages' => [
                'loginId' => ['err'],
                'general' => 'error'
            ]
        ];
        $expected = [
            'messages' => [
                'general' => 'error'
            ]
        ];
        $mockForm = m::mock(Form::class)
            ->shouldReceive('setMessages')
            ->with(['userLoginSecurity' => ['loginId' => ['err']]])
            ->once()
            ->getMock();

        $this->assertEquals($expected, Sut::mapFromErrors($mockForm, $errors));
    }
}
