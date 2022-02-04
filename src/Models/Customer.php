<?php namespace Rebill\SDK\Models;

/**
*  Customer class
*
*  @author Kijam
*/
class Customer extends \Rebill\SDK\RebillModel
{
    /**
     * ID of Model
     *
     * @var int
     */
    protected $id;

    /**
     * Endpoint of Model
     *
     * @var int
     */
    protected static $endpoint = '/customers';

    /**
     * Class of Model
     *
     * @var string
     */
    protected $class = self::class;
    /**
     * Attribute List
     *
     * @var array<string, mixed>
     */
    protected $attributes = [
        'email' => null,
        'firstName' => null,
        'lastName' => null,
        'phone_areacode' => null,
        'phone_number' => null,
        'birthday' => null,
        'vat_type' => null,
        'vatID_number' => null,
        'personalID_type' => null,
        'personalID_number' => null,
        'address_street' => null,
        'address_number' => null,
        'address_floor' => null,
        'address_apt' => null,
        'address_city' => null,
        'address_province' => null,
        'address_zipcode' => null,
        'description' => null,
        'region_id' => null,
        'vendor_id' => null   ,
        'user_id' => null   
    ];
    /**
     * Format List
     *
     * @var array<string, array<int, string>>
     */
    protected $format =  [
        'email' => ['validateEmail'],
        'firstName' => ['is_string'],
        'lastName' => ['is_string'],
        'phone_areacode' => ['is_numeric'],
        'phone_number' => ['is_numeric'],
        'vat_type' => ['is_string'],
        'vatID_number' => ['is_numeric'],
        'personalID_type' => ['is_string'],
        'personalID_number' => ['is_numeric'],
        'address_street' => ['is_string'],
        'address_number' => ['is_numeric'],
        'address_floor' => ['is_string'],
        'address_city' => ['is_string'],
        'address_floor' => ['is_string'],
        'address_apt' => ['is_string'],
        'address_province' => ['is_string'],
        'address_zipcode' => ['is_string'],
        'description' => ['is_string'],
        'region_id' => ['is_numeric'],
        'vendor_id' => ['is_numeric'],
        'user_id' => ['is_numeric'],
    ];
    /**
     * List attributes ignored in PUT or POST request
     *
     * @var array<int, string>
     */
    protected $ignore = [
        'region_id',
        'vendor_id',
        'user_id',
        'updatedAt',
        'createdAt',
    ];
    /**
     * List attributes required in PUT or POST request
     *
     * @var array<int, string>
     */
    protected $required =  [
        'email',
        'firstName',
        'lastName',
        'vat_type',
        'vatID_number',
        'personalID_type',
        'personalID_number',
        'address_street',
        'address_number',
        'address_city',
        'address_province',
        'address_zipcode'
    ];
}