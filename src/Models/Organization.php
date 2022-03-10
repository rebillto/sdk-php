<?php namespace Rebill\SDK\Models;

/**
*  Organization class
*
*  @author Kijam
*/
class Organization extends \Rebill\SDK\RebillModel
{
    /**
     * Endpoint of Model
     *
     * @var int
     */
    protected static $endpoint = '/organization';

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
        'alias',
        'logoUrl',
        'name',
        'roles',
        'gateways',
        'address',
        'parent',
        'customCheckoutUrl'
    ];

    /**
     * Format List
     *
     * @var array<string, array<int, string>>
     */
    protected $format = [
        'address' => ['validateOrganizationAddress'],
        'alias' => ['is_string'],
        'name' => ['is_string'],
    ];

    /**
     * List attributes ignored in PUT or POST request
     *
     * @var array<int, string>
     */
    protected $ignore = [
        'customCheckoutUrl',
        'roles',
        'gateways',
        'parent',
        'logoUrl',
    ];

    /**
     * List attributes required in PUT or POST request
     *
     * @var array<int, string>
     */
    protected $required = [
        'name',
        'address',
        'alias'
    ];
    /**
     * Validate OrganizationAddress Field
     *
     * @var array<int, string>
     */
    public static function validateOrganizationAddress($field)
    {
        return $field instanceof \Rebill\SDK\Models\Shared\OrganizationAddress && $field->validate();
    }
}
