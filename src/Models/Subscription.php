<?php namespace Rebill\SDK\Models;

/**
*  Subscription
*
*  @author Kijam
*/
class Subscription extends \Rebill\SDK\RebillModel
{
    /**
     * Endpoint of Model
     *
     * @var int
     */
    protected static $endpoint = '/subscriptions';

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
        'price',
        'quantity',
        'nextChargeDate',
        'remainingIterations',
        'couponsApplied',
        'user',
        'gateway',
        'value',
        'invoices',
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
    ];

    /**
     * Check format of Attributes
     *
     * @return object Recursive Model
     */
    protected function format()
    {
        if ($this->price && !is_object($this->price)) {
            $this->price = (new \Rebill\SDK\Models\Price)->setAttributes($this->price);
        }
        return $this;
    }
    /**
     * Get Subscriptions by Cart ID
     *
     * @param string $cart_id Cart ID.
     * 
     * @return array<\Rebill\SDK\Models\Subscription> Payment List.
     */
    public static function getByCartId($cart_id)
    {
        $data = \Rebill\SDK\Rebill::getInstance()->callApiGet(static::$endpoint.'/cart/'.$cart_id);
        $result = [];
        if ($data) {
            foreach ($data as $payment) {
                $result[] = (new self)->setAttributes($payment);
            }
        }
        return $result;
    }

    /**
     * Get Subscription by ID
     * 
     * @param string $id Model ID.
     * @param string $endpoint Endpoint.
     *
     * @return \Rebill\SDK\Models\Subscription|bool
     */
    public static function get($id = false, $endpoint = false)
    {
        \Rebill\SDK\Rebill::log('get: '.$endpoint);
        $class_name = get_called_class();
        $obj = new $class_name;
        $data = \Rebill\SDK\Rebill::getInstance()->callApiGet(($endpoint ? $endpoint : static::$endpoint).($id ? '/'.$id : ''));
        \Rebill\SDK\Rebill::log('get data: - '.\var_export($data, true));
        if ($data) {
            $result = $data['subscription'];
            $result['value'] = $data['value'];
            $result['gateway'] = $data['gateway'];
            $obj->setAttributes($result);
            $obj->to_put_attributes = [];
            return $obj;
        }
        unset($obj);
        return false;
    }
}
