<?php namespace Rebill\SDK\Models;

/**
*  Card class
*
*  @author Kijam
*/
class Card extends \Rebill\SDK\RebillModel
{
    /**
     * Endpoint of Model
     *
     * @var int
     */
    protected static $endpoint = '/customers/{customer_id}/card';

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
        'cardNumber',
        'cardHolder',
        'securityCode',
        'expiration',
        'deviceId',
        'id',
    ];

    /**
     * List attributes ignored in PUT or POST request
     *
     * @var array<int, string>
     */
    protected $ignore = [
        'id',
        'expirationMonth',
        'expirationYear',
    ];

    /**
     * Format List
     *
     * @var array<string, array<int, string>>
     */
    protected $format = [
        'cardNumber' => ['is_string'],
        'deviceId' => ['is_string'],
        'securityCode' => ['is_string'],
        'id' => ['is_string'],
    ];

    /**
     * Response Model
     *
     * @var string
     */
    protected $responseClass = \Rebill\SDK\Models\Response\CardResponse::class;

    /**
     * Check format of Attributes
     *
     * @return object Recursive Model
     */
    protected function format()
    {
        if (isset($this->cardHolder) && !is_object($this->cardHolder)) {
            $this->cardHolder = (new \Rebill\SDK\Models\Shared\CardHolder)->setAttributes($this->cardHolder);
        }
        if (isset($this->expiration) && !is_object($this->expiration)) {
            $this->expiration = (new \Rebill\SDK\Models\Shared\Expiration)->setAttributes($this->expiration);
        }
        return $this;
    }
    /**
     * Create Model
     *
     * @param string $customer_id Customer ID
     *
     * @return bool|\Rebill\SDK\Models\Response\NewGatewayResponse NewGatewayResponse Model
     */
    public function add($customer_id)
    {
        return parent::create(str_replace('{customer_id}', $customer_id, self::$endpoint));
    }
}
