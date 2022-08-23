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
        'customer',
        'prices',
        'cartId'
    ];

    /**
     * Format List
     *
     * @var array<string, array<int, string>>
     */
    protected $format = [
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
    ];

    /**
     * List attributes required in PUT or POST request
     *
     * @var array<int, string>
     */
    protected $required = [
        'customer'
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
        return $field instanceof \Rebill\SDK\Models\Shared\CustomerCheckout && $field->validate();
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
                    } else {
                        $value->validate();
                    }
                }
            } else {
                \Rebill\SDK\Rebill::log('Checkout: the prices not is an array: '.var_export($field, true));
                throw new \Exception('Checkout: the prices not is array');
            }
        }
        return true;
    }

    /**
     * Check format of Attributes
     *
     * @return object Recursive Model
     */
    protected function format()
    {
        if (isset($this->card) && !is_object($this->card)) {
            $this->card = (new \Rebill\SDK\Models\Card)->setAttributes($this->card);
        }
        if (isset($this->customer) && !is_object($this->customer)) {
            $this->customer = (new \Rebill\SDK\Models\Shared\CustomerCheckout)->setAttributes($this->customer);
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
        $result = \Rebill\SDK\Rebill::getInstance()->callApiPost($endpoint ? $endpoint : static::$endpoint, $data, false);
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
    
    /**
     * List of installment availables
     *
     * @param int $cardBin First six card number.
     * @param array<\Rebill\SDK\Models\Shared\CheckoutPrice> $prices List of prices with quantity.
     *
     * @ int
     * @return array List of installment available for this card number.
     */
    public static function installments($cardBin, $prices)
    {
        if (!is_numeric($cardBin) || strlen($cardBin) != 6) {
            throw new \Exception('Checkout Installment: cardBin is invalid');
        }
        foreach ($prices as &$price) {
            if (!($price instanceof \Rebill\SDK\Models\Shared\CheckoutPrice)) {
                $price = (new \Rebill\SDK\Models\Shared\CheckoutPrice)->setAttributes($price);
            }
        }
        if (self::validatePrices($prices)) {
            $data = [
                'priceId' => $prices[0]->id,
                'quantity' => $prices[0]->quantity,
                'installmentsRequiredData' => [
                    'cardBin' => $cardBin,
                    'cardNumber' => $cardBin.'0000000000'
                ]
            ];
            return \Rebill\SDK\Rebill::getInstance()->callApiPost(self::$endpoint.'/processInstallments', $data);
        }
        return [];
    }
}
