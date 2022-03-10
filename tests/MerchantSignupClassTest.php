<?php

namespace Rebill\SDK\Tests;

use PHPUnit\Framework\TestCase;

/**
*  Test Models/Customer class
*
*  @author Kijam
*/
class MerchantSignupClassTest extends TestCase
{
    public function __construct()
    {
        /*
        \Rebill\SDK\Rebill::getInstance()->isDebug = true;
        \Rebill\SDK\Rebill::getInstance()->setCallBackDebugLog(function ($data) {
            var_dump($data);
        });
        */
        parent::__construct();
    }
    /**
     * Just check if the Model has no syntax error
     * @test
     */
    public function isObject()
    {
        $obj = new \Rebill\SDK\Models\MerchantSignup;
        $this->assertTrue(is_object($obj));
        unset($obj);
    }
    /**
     * Validate all OK
     * @test
     */
    public function checkValidationAllOK()
    {
        $this->assertTrue(is_object((new \Rebill\SDK\Models\MerchantSignup)->setAttributes([
            'user' => (new \Rebill\SDK\Models\Shared\User)->setAttributes([
                'email' => 'dummy@rebill.to',
                'password' => 'dummy123',
            ]),
            'organization' => (new \Rebill\SDK\Models\Organization)->setAttributes([
                'name' => 'Unit Test',
                'alias' => 'unit-test',
                'address' => (new \Rebill\SDK\Models\Shared\OrganizationAddress)->setAttributes([
                    'street' => 'Riverside St.',
                    'number' => '102',
                    'floor' => '2',
                    'apt' => 'B',
                    'city' => 'Santa Cruz',
                    'state' => 'Santa Cruz',
                    'zipCode' => '9011',
                    'country' => 'ARG',
                    'description' => 'Home / Office'
                ])
            ])
        ])->validate()));
    }
    /**
     * Check required attribute
     * @test
     */
    public function checkRequired1()
    {
        $valid = true;
        try {
            (new \Rebill\SDK\Models\MerchantSignup)->setAttributes([
                'user' => (new \Rebill\SDK\Models\Shared\User)->setAttributes([
                    'password' => 'dummy123',
                ]),
                'organization' => (new \Rebill\SDK\Models\Organization)->setAttributes([
                    'name' => 'Unit Test',
                    'alias' => 'unit-test',
                    'address' => (new \Rebill\SDK\Models\Shared\OrganizationAddress)->setAttributes([
                      'street' => 'Riverside St.',
                      'number' => '102',
                      'floor' => '2',
                      'apt' => 'B',
                      'city' => 'Santa Cruz',
                      'state' => 'Santa Cruz',
                      'zipCode' => '9011',
                      'country' => 'ARG',
                      'description' => 'Home / Office'
                    ])
                ])
            ])->validate();
            $valid = false;
        } catch (\Exception $e) {
        }
        $this->assertTrue($valid);
        unset($obj);
    }
    /**
     * Check required attribute
     * @test
     */
    public function checkRequired2()
    {
        $valid = true;
        try {
            (new \Rebill\SDK\Models\MerchantSignup)->setAttributes([
                'organization' => (new \Rebill\SDK\Models\Organization)->setAttributes([
                    'name' => 'Unit Test',
                    'alias' => 'unit-test',
                    'address' => (new \Rebill\SDK\Models\Shared\OrganizationAddress)->setAttributes([
                      'street' => 'Riverside St.',
                      'number' => '102',
                      'floor' => '2',
                      'apt' => 'B',
                      'city' => 'Santa Cruz',
                      'state' => 'Santa Cruz',
                      'zipCode' => '9011',
                      'country' => 'ARG',
                      'description' => 'Home / Office'
                    ])
                ])
            ])->validate();
            $valid = false;
        } catch (\Exception $e) {
        }
        $this->assertTrue($valid);
        unset($obj);
    }
    /**
     * Check required attribute
     * @test
     */
    public function checkRequired3()
    {
        $valid = true;
        try {
            (new \Rebill\SDK\Models\MerchantSignup)->setAttributes([
                'user' => (new \Rebill\SDK\Models\Shared\User)->setAttributes([
                    'email' => 'dummy@rebill.to',
                    'password' => 'dummy123',
                ])
            ])->validate();
            $valid = false;
        } catch (\Exception $e) {
        }
        $this->assertTrue($valid);
        unset($obj);
    }
    /**
     * Check required attribute
     * @test
     */
    public function checkRequired4()
    {
        $valid = true;
        try {
            (new \Rebill\SDK\Models\MerchantSignup)->setAttributes([
                'user' => (new \Rebill\SDK\Models\Shared\User)->setAttributes([
                    'email' => 'dummy@rebill.to',
                    'password' => 'dummy123',
                ]),
                'organization' => (new \Rebill\SDK\Models\Organization)->setAttributes([
                    'name' => 'Unit Test',
                    'address' => (new \Rebill\SDK\Models\Shared\OrganizationAddress)->setAttributes([
                      'street' => 'Riverside St.',
                      'number' => '102',
                      'floor' => '2',
                      'apt' => 'B',
                      'city' => 'Santa Cruz',
                      'state' => 'Santa Cruz',
                      'zipCode' => '9011',
                      'country' => 'ARG',
                      'description' => 'Home / Office'
                    ])
                ])
            ])->validate();
            $valid = false;
        } catch (\Exception $e) {
        }
        $this->assertTrue($valid);
        unset($obj);
    }
    /**
     * Check required attribute
     * @test
     */
    public function checkRequired5()
    {
        $valid = true;
        try {
            (new \Rebill\SDK\Models\MerchantSignup)->setAttributes([
                'user' => (new \Rebill\SDK\Models\Shared\User)->setAttributes([
                    'email' => 'dummy@rebill.to',
                    'password' => 'dummy123',
                ]),
                'organization' => (new \Rebill\SDK\Models\Organization)->setAttributes([
                    'name' => 'Unit Test',
                    'alias' => 'unit-test',
                    'address' => (new \Rebill\SDK\Models\Shared\OrganizationAddress)->setAttributes([
                      'number' => '102',
                      'floor' => '2',
                      'apt' => 'B',
                      'city' => 'Santa Cruz',
                      'state' => 'Santa Cruz',
                      'zipCode' => '9011',
                      'country' => 'ARG',
                      'description' => 'Home / Office'
                    ])
                ])
            ])->validate();
            $valid = false;
        } catch (\Exception $e) {
        }
        $this->assertTrue($valid);
    }
    /**
     * Check required attribute
     * @test
     */
    public function checkRequired6()
    {
        $valid = true;
        try {
            (new \Rebill\SDK\Models\MerchantSignup)->setAttributes([
                'user' => (new \Rebill\SDK\Models\Shared\User)->setAttributes([
                    'email' => 'dummy@rebill.to',
                    'password' => 'dummy123',
                ]),
                'organization' => (new \Rebill\SDK\Models\Organization)->setAttributes([
                    'name' => 'Unit Test',
                    'alias' => 'unit-test'
                ])
            ])->validate();
            $valid = false;
        } catch (\Exception $e) {
        }
        $this->assertTrue($valid);
        unset($obj);
    }
    /**
     * Invalid Mail
     * @test
     */
    public function checkInvalidEmail()
    {
        $valid = true;
        try {
            (new \Rebill\SDK\Models\MerchantSignup)->setAttributes([
                'user' => (new \Rebill\SDK\Models\Shared\User)->setAttributes([
                    'email' => 'dummy',
                    'password' => 'dummy123',
                ]),
                'organization' => (new \Rebill\SDK\Models\Organization)->setAttributes([
                    'name' => 'Unit Test',
                    'alias' => 'unit-test',
                    'address' => (new \Rebill\SDK\Models\Shared\OrganizationAddress)->setAttributes([
                      'street' => 'Riverside St.',
                      'number' => '102',
                      'floor' => '2',
                      'apt' => 'B',
                      'city' => 'Santa Cruz',
                      'state' => 'Santa Cruz',
                      'zipCode' => '9011',
                      'country' => 'ARG',
                      'description' => 'Home / Office'
                    ])
                ])
            ])->validate();
            $valid = false;
        } catch (\Exception $e) {
        }
        $this->assertTrue($valid);
        unset($obj);
    }
    /**
     * Invalid String
     * @test
     */
    public function checkInvalidString()
    {
        $valid = true;
        try {
            (new \Rebill\SDK\Models\MerchantSignup)->setAttributes([
                'user' => (new \Rebill\SDK\Models\Shared\User)->setAttributes([
                    'email' => 'dummy@rebill.to',
                    'password' => 'dummy123',
                ]),
                'organization' => (new \Rebill\SDK\Models\Organization)->setAttributes([
                    'name' => new \stdClass,
                    'alias' => 'unit-test',
                    'address' => (new \Rebill\SDK\Models\Shared\OrganizationAddress)->setAttributes([
                        'street' => 'Riverside St.',
                        'number' => '102',
                        'floor' => '2',
                        'apt' => 'B',
                        'city' => 'Santa Cruz',
                        'state' => 'Santa Cruz',
                        'zipCode' => '9011',
                        'country' => 'ARG',
                        'description' => 'Home / Office'
                    ])
                ])
            ])->validate();
            $valid = false;
        } catch (\Exception $e) {
        }
        $this->assertTrue($valid);
        unset($obj);
    }
    /**
     * Invalid String
     * @test
     */
    public function checkInvalidString2()
    {
        $valid = true;
        try {
            (new \Rebill\SDK\Models\MerchantSignup)->setAttributes([
                    'user' => (new \Rebill\SDK\Models\Shared\User)->setAttributes([
                        'email' => 'dummy@rebill.to',
                        'password' => new \stdClass,
                    ]),
                    'organization' => (new \Rebill\SDK\Models\Organization)->setAttributes([
                        'name' => 'Unit Test',
                        'alias' => 'unit-test',
                        'address' => (new \Rebill\SDK\Models\Shared\OrganizationAddress)->setAttributes([
                          'street' => 'Riverside St.',
                          'number' => '39',
                          'floor' => '2',
                          'apt' => 'B',
                          'city' => 'Santa Cruz',
                          'state' => 'Santa Cruz',
                          'zipCode' => '9011',
                          'country' => 'ARG',
                          'description' => 'Home / Office'
                        ])
                    ])
                ])->validate();
            $valid = false;
        } catch (\Exception $e) {
        }
        $this->assertTrue($valid);
        unset($obj);
    }
    /**
     * Invalid String
     * @test
     */
    public function checkInvalidString3()
    {
        $valid = true;
        try {
            (new \Rebill\SDK\Models\MerchantSignup)->setAttributes([
                    'user' => (new \Rebill\SDK\Models\Shared\User)->setAttributes([
                        'email' => 'dummy@rebill.to',
                        'password' => 'dummy123',
                    ]),
                    'organization' => (new \Rebill\SDK\Models\Organization)->setAttributes([
                        'name' => 'Unit Test',
                        'alias' => 'unit-test',
                        'address' => (new \Rebill\SDK\Models\Shared\OrganizationAddress)->setAttributes([
                          'street' => 'Riverside St.',
                          'number' => new \stdClass,
                          'floor' => '2',
                          'apt' => 'B',
                          'city' => 'Santa Cruz',
                          'state' => 'Santa Cruz',
                          'zipCode' => '9011',
                          'country' => 'ARG',
                          'description' => 'Home / Office'
                        ])
                    ])
                ])->validate();
            $valid = false;
        } catch (\Exception $e) {
        }
        $this->assertTrue($valid);
        unset($obj);
    }
    /**
     * Validate toArray
     * @test
     */
    public function checkValidArray()
    {
        $valid = false;
        try {
            $data = (new \Rebill\SDK\Models\MerchantSignup)->setAttributes([
                'user' => (new \Rebill\SDK\Models\Shared\User)->setAttributes([
                    'email' => 'dummy@rebill.to',
                    'password' => 'dummy123',
                ]),
                'organization' => (new \Rebill\SDK\Models\Organization)->setAttributes([
                    'name' => 'Unit Test',
                    'alias' => 'unit-test',
                    'address' => (new \Rebill\SDK\Models\Shared\OrganizationAddress)->setAttributes([
                        'street' => 'Riverside St.',
                        'number' => '39',
                        'floor' => '2',
                        'apt' => 'B',
                        'city' => 'Santa Cruz',
                        'state' => 'Santa Cruz',
                        'zipCode' => '9011',
                        'country' => 'ARG',
                        'description' => 'Home / Office'
                    ])
                ])
            ])->validate();
            if ((string)$data == '{"user":{"email":"dummy@rebill.to","password":"dummy123"},"organization":{"name":"Unit Test","alias":"unit-test","address":{"street":"Riverside St.","number":"39","floor":"2","apt":"B","city":"Santa Cruz","state":"Santa Cruz","zipCode":"9011","country":"ARG","description":"Home \/ Office"}}}') {
                $valid = true;
            }
        } catch (\Exception $e) {
        }
        $this->assertTrue($valid);
        unset($obj);
    }
}
