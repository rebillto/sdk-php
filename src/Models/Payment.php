<?php namespace Rebill\SDK\Models;

/**
*  PaymentBag
*
*  @author Kijam
*/
class Payment extends \Rebill\SDK\RebillModel
{
    /**
     * Endpoint of Model
     *
     * @var int
     */
    protected static $endpoint = '/payments';

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
        'gatewayCard',
        'currency',
        'amount',
        'metadata',
        'status',
        'cartId',
        'payer',
        'id'
    ];
    /**
     * List attributes required in PUT or POST request
     *
     * @var array<int, string>
     */
    protected $required = [
    ];

    /**
     * List attributes ignored in PUT or POST request
     *
     * @var array<int, string>
     */
    protected $ignore = [
        'id'
    ];
    public function format() {
        if ($this->payer && !is_object($this->payer)) {
            $this->payer = (new \Rebill\SDK\Models\Shared\Profile)->setAttributes($this->payer);
        }
        return $this;
    }
    public static function getByCartId($cart_id) {
        $data = \Rebill\SDK\Rebill::getInstance()->callApiGet(static::$endpoint.'/cart/'.$cart_id);
        $result = [];
        if ($data) {
            foreach ($data as $payment) {
                $result[] = (new self)->setAttributes($payment);
            }
        }
        return $result;
    }
}
