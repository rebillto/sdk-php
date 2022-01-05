<?php

namespace Rebill\SDK\Tests;

use PHPUnit\Framework\TestCase;

/**
*  Test Models/Customer class
*
*  @author Kijam
*/
class CustomerClassTest extends TestCase
{
    /**
     * Just check if the Model has no syntax error
     * @test
     */
    public function isObject()
    {
        $obj = new \Rebill\SDK\Models\Customer;
        $this->assertTrue(is_object($obj));
        unset($obj);
    }
    /**
     * Validate empty check
     * @test
     */
    public function checkValidationEmpty()
    {
        $obj = new \Rebill\SDK\Models\Customer;
        try {
            $obj->validate();
            $this->assertTrue(false);
        } catch (\Exception $e) {
            $this->assertTrue(true);
        }
        unset($obj);
    }
    /**
     * Validate all OK
     * @test
     */
    public function checkValidationAllOK()
    {
        $obj = new \Rebill\SDK\Models\Customer;
        $this->assertTrue(is_object($obj->setAttributes([
                'email' => 'dummy@dummy.com',
                'firstName' => 'dummy',
                'lastName' => 'dummy',
                'vat_type' => 'DNI',
                'vatID_number' => '1111111',
                'personalID_type' => 'DNI',
                'personalID_number' => '1111111',
                'address_street' => 'dummy',
                'address_number' => '111',
                'address_city' => 'dummy',
                'address_province' => 'dummy',
                'address_zipcode' => '1111'
            ])->validate()));
        unset($obj);
    }
    /**
     * Invalid Mail
     * @test
     */
    public function checkInvalidEmail()
    {
        $obj = new \Rebill\SDK\Models\Customer;
        $valid = true;
        try {
            $obj->setAttributes([
                'email' => 'dummy',
                'firstName' => 'dummy',
                'lastName' => 'dummy',
                'vat_type' => 'DNI',
                'vatID_number' => '1111111',
                'personalID_type' => 'DNI',
                'personalID_number' => '1111111',
                'address_street' => 'dummy',
                'address_number' => '111',
                'address_city' => 'dummy',
                'address_province' => 'dummy',
                'address_zipcode' => '1111'
            ])->validate();
            $valid = false;
        } catch (\Exception $e) { }
        $this->assertTrue($valid);
        unset($obj);
    }
    /**
     * Invalid String
     * @test
     */
    public function checkInvalidString()
    {
        $obj = new \Rebill\SDK\Models\Customer;
        $valid = true;
        try {
            $obj->setAttributes([
                'email' => 'dummy@dummy.com',
                'firstName' => new \stdClass,
                'lastName' => 'dummy',
                'vat_type' => 'DNI',
                'vatID_number' => '1111111',
                'personalID_type' => 'DNI',
                'personalID_number' => '1111111',
                'address_street' => 'dummy',
                'address_number' => '111',
                'address_city' => 'dummy',
                'address_province' => 'dummy',
                'address_zipcode' => '1111'
            ])->validate();
            $valid = false;
        } catch (\Exception $e) { }
        $this->assertTrue($valid);
        unset($obj);
    }
    /**
     * Invalid Numeric
     * @test
     */
    public function checkInvalidNumeric()
    {
        $obj = new \Rebill\SDK\Models\Customer;
        $valid = true;
        try {
            $obj->setAttributes([
                'email' => 'dummy@dummy.com',
                'firstName' => 'dummy',
                'lastName' => 'dummy',
                'vat_type' => 'DNI',
                'vatID_number' => 'dummy',
                'personalID_type' => 'DNI',
                'personalID_number' => 'dummy',
                'address_street' => 'dummy',
                'address_number' => 'dummy',
                'address_city' => 'dummy',
                'address_province' => 'dummy',
                'address_zipcode' => 'dummy'
            ])->validate();
            $valid = false;
        } catch (\Exception $e) { }
        $this->assertTrue($valid);
        unset($obj);
    }
}