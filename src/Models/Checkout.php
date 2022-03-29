<?php namespace Rebill\SDK\Models;

/**
*  Customer class
*
*  @author Kijam
*/
class Checkout extends \Rebill\SDK\RebillModel
{
    /**
     * Endpoint of Model
     *
     * @var int
     */
    protected static $endpoint = '/checkout';

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
        'card',
        'customer',
        'prices',
		'cartId',
        'organizationId',
    ];

    /**
     * Format List
     *
     * @var array<string, array<int, string>>
     */
    protected $format = [
        'card' => ['validateCard'],
        'customer' => ['validateCustomer'],
        'prices' => ['validatePrices'],
        'cartId' => ['is_string'],
    ];

    /**
     * List attributes ignored in PUT or POST request
     *
     * @var array<int, string>
     */
    protected $ignore = [
        'organizationId'
    ];

    /**
     * List attributes required in PUT or POST request
     *
     * @var array<int, string>
     */
    protected $required = [
        'card',
        'customer',
        'organizationId'
    ];

    /**
     * Response Model
     *
     * @var string
     */
    protected $responseClass = \Rebill\SDK\Models\Response\CheckoutResponse::class;

    /**
     * Validate Address Field
     *
     * @var array<int, string>
     */
    public static function validateCard($field)
    {
        return $field instanceof \Rebill\SDK\Models\Card && $field->validate();
    }

    /**
     * Validate Address Field
     *
     * @var array<int, string>
     */
    public static function validateCustomer($field)
    {
        return $field instanceof \Rebill\SDK\Models\Shared\Profile && $field->validate();
    }

    /**
     * Validate Address Field
     *
     * @var array<int, string>
     */
    public static function validatePrices($field)
    {
        if ($field) {
            if (is_array($field)) {
                $key_expected = 0;
                foreach ($field as $key => &$value) {
                    if ($key !== $key_expected) {
                        \Rebill\SDK\Rebill::log('Checkout: the key prices['.$key.'] is invalid, key expected '.$key_expected.': '.var_export($field, true));
                        throw new \Exception('Checkout: the key prices['.$key.'] is invalid, key expected '.$key_expected);
                    }
                    ++$key_expected;
                    if (!($value instanceof \Rebill\SDK\Models\Shared\CheckoutPrice)) {
                        \Rebill\SDK\Rebill::log('Checkout: the prices['.$key.'] not is CheckoutPrice instance: '.var_export($field, true));
                        throw new \Exception('Checkout: the prices['.$key.'] not is CheckoutPrice instance');
                    }
                }
            } else {
                \Rebill\SDK\Rebill::log('Checkout: the prices not is an array: '.var_export($field, true));
                throw new \Exception('Checkout: the prices not is array');
            }
        }
        return true;
    }
    public function format()
    {
        if (isset($this->card) && !is_object($this->card)) {
            $this->card = (new \Rebill\SDK\Models\Card)->setAttributes($this->card);
        }
        if (isset($this->customer) && !is_object($this->customer)) {
            $this->customer = (new \Rebill\SDK\Models\Shared\Profile)->setAttributes($this->customer);
        }
        if (isset($this->prices)) {
            foreach ($this->prices as $k => $price) {
                if (!is_object($price)) {
                    $this->prices[$k] = (new \Rebill\SDK\Models\Shared\CheckoutPrice)->setAttributes($price);
                }
            }
        }
        return $this;
    }
    
    /**
     * Create Model
     *
     * @param string $endpoint Endpoint.
     * 
     * @return bool|object Recursive Model
     */
    public function create($endpoint = false)
    {
        $data = $this->validate()->toArray();
        \Rebill\SDK\Rebill::log('Create '.$endpoint.': '.\var_export($this->to_put_attributes, true).\var_export($data, true));
        foreach (\array_keys($data) as $key) {
            if (!\in_array($key, $this->to_put_attributes) || \in_array($key, $this->ignore, true)) {
                unset($data[$key]);
            }
        }
        \Rebill\SDK\Rebill::log('Create '.$endpoint.' filtered: '.\var_export($data, true));
        if (count($data) == 0) {
            return false;
        }
        $result = \Rebill\SDK\Rebill::getInstance()->callApiPost($endpoint ? $endpoint : static::$endpoint, $data, false, [
            'organization_id: '. $this->organizationId
        ]);
        if ($result) {
            $this->to_put_attributes = [];
            if (\property_exists($this, 'responseClass')) {
                $class_name = $this->responseClass;
                $response = new $class_name;
                $response->setAttributes($result);
                return $response;
            } else {
                $this->setAttributes($result);
            }
            return $this;
        }
        return false;
    }
}
