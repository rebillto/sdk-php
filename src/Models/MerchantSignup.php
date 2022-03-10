<?php namespace Rebill\SDK\Models;

/**
*  MerchantSignup class
*
*  @author Kijam
*/
class MerchantSignup extends \Rebill\SDK\RebillModel
{
    protected $is_guest = true;
    /**
     * Endpoint of Model
     *
     * @var int
     */
    protected static $endpoint = '/auth/merchant/signup';

    /**
     * Class of Model
     *
     * @var string
     */
    protected $class = self::class;

    /**
     * Response Model
     *
     * @var string
     */
    protected $responseClass = \Rebill\SDK\Models\Response\MerchantSignupResponse::class;
    
    /**
     * Attribute List
     *
     * @var array<string, mixed>
     */
    protected $attributes = [
        'organization',
        'user'
    ];

    /**
     * Format List
     *
     * @var array<string, array<int, string>>
     */
    protected $format = [
        'user' => ['validateUser'],
        'organization' => ['valiateOrganization'],
    ];

    /**
     * List attributes ignored in PUT or POST request
     *
     * @var array<int, string>
     */
    protected $ignore = [
    ];

    /**
     * List attributes required in PUT or POST request
     *
     * @var array<int, string>
     */
    protected $required = [
        'user',
        'organization'
    ];

    /**
     * Validate OrganizationAddress Field
     *
     * @var array<int, string>
     */
    public static function valiateOrganization($field)
    {
        return $field instanceof \Rebill\SDK\Models\Organization && $field->validate();
    }

    /**
     * Validate OrganizationAddress Field
     *
     * @var array<int, string>
     */
    public static function validateUser($field)
    {
        return $field instanceof \Rebill\SDK\Models\Shared\User && $field->validate();
    }
}
