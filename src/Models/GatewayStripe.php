<?php namespace Rebill\SDK\Models;

/**
*  GatewayStripe class
*
*  @author Kijam
*/
class GatewayStripe extends \Rebill\SDK\RebillModel
{
    /**
     * Endpoint of Model
     *
     * @var int
     */
    protected static $endpoint = '/organization/gateway/stripe/{iso_country}';

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
    protected $responseClass = \Rebill\SDK\Models\Response\GatewayResponse::class;
    
    /**
     * Attribute List
     *
     * @var array<string, mixed>
     */
    protected $attributes = [
        'privateKey',
        'publicKey'
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
        'privateKey',
        'publicKey'
    ];

    /**
     * Format List
     *
     * @var array<string, array<int, string>>
     */
    protected $format = [
        'privateKey' => ['is_string'],
        'publicKey' => ['is_string']
    ];

    /**
     * Create Model
     *
     * @param string $iso_country ISO2 of Country.
     *
     * @return bool|\Rebill\SDK\Models\Response\NewGatewayResponse NewGatewayResponse Model
     */
    public function add($iso_country)
    {
        return parent::create(str_replace('{iso_country}', $iso_country, self::$endpoint));
    }
}
