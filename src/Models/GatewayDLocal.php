<?php namespace Rebill\SDK\Models;

/**
*  GatewayDLocal class
*
*  @author Kijam
*/
class GatewayDLocal extends \Rebill\SDK\RebillModel
{
    /**
     * Endpoint of Model
     *
     * @var int
     */
    protected static $endpoint = '/organization/gateway/dlocal/{iso_country}';

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
        'xLogin',
        'xTransKey',
        'secretKey',
        'description'
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
        'xLogin',
        'xTransKey',
        'secretKey',
        'description'
    ];

    /**
     * Format List
     *
     * @var array<string, array<int, string>>
     */
    protected $format = [
        'xLogin' => ['is_string'],
        'xTransKey' => ['is_string'],
        'secretKey' => ['is_string'],
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
