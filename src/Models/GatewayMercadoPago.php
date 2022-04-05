<?php namespace Rebill\SDK\Models;

/**
*  GatewayMercadoPago class
*
*  @author Kijam
*/
class GatewayMercadoPago extends \Rebill\SDK\RebillModel
{
    /**
     * Endpoint of Model
     *
     * @var int
     */
    protected static $endpoint = '/organization/gateway/mercadopago/{iso_country}';

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
        'code',
        'appId'
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
        'code',
        'appId'
    ];

    /**
     * Format List
     *
     * @var array<string, array<int, string>>
     */
    protected $format = [
        'code' => ['is_string'],
        'appId' => ['is_string']
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
