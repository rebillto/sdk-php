<?php namespace Rebill\SDK\Models;

/**
*  Frequecy
*
*  @author Kijam
*/
class Price extends \Rebill\SDK\RebillModel
{
    /**
     * Endpoint of Model
     *
     * @var int
     */
    protected static $endpoint = '/item/{item_id}/prices';

    /**
     * Response Model
     *
     * @var string
     */
    protected $responseClass = \Rebill\SDK\Models\Response\GenericIdResponse::class;

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
        'amount',
        'type',
        'tiers',
        'frequency',
        'freeTrial',
        'repetitions',
        'description',
        'currency',
        'gatewayId',
		'item',
		'state',
		'gateway',
		'id'
    ];

    /**
     * List attributes required in PUT or POST request
     *
     * @var array<int, string>
     */
    protected $required = [
        //'amount',
        'type',
        'frequency',
        //'repetitions',
        'description',
        'currency',
        'gatewayId'
    ];

    /**
     * Format List
     *
     * @var array<string, array<int, string>>
     */
    protected $format = [
        'gatewayId' => ['is_string'],
        'description' => ['is_string'],
        'type' => ['is_string'],
        'currency' => ['is_string'],
        'amount' => ['is_numeric'],
        'repetitions' => ['is_numeric'],
    ];

    /**
     * List attributes ignored in PUT or POST request
     *
     * @var array<int, string>
     */
    protected $ignore = [
		'id'
    ];

    public function validate()
    {
        parent::validate();
        if (!in_array($this->type, ['fixed', 'tiered'])) {
            \Rebill\SDK\Rebill::log('Price: type is invalid '.var_export($this->type, true));
            throw new \Exception('Price: The attribute type is invalid.');
        }
        if (strlen($this->currency) != 3) {
            \Rebill\SDK\Rebill::log('Price: currency is invalid '.var_export($this->currency, true));
            throw new \Exception('Price: The attribute currency is invalid.');
        }
        if ($this->type == 'fixed' && isset($this->tiers)) {
            \Rebill\SDK\Rebill::log('Price: in fixed price tiers not is required');
            throw new \Exception('Price: in fixed price tiers not is required.');
        } elseif ($this->type == 'tiered' && !isset($this->tiers)) {
            \Rebill\SDK\Rebill::log('Price: in tiered price tiers is required');
            throw new \Exception('Price: in tiered price tiers is required.');
        } elseif ($this->type == 'tiered' && isset($this->tiers)) {
            if (!is_array($this->tiers)) {
                \Rebill\SDK\Rebill::log('Price: tiers is invalid '.var_export($this->tiers, true));
                throw new \Exception('Price: The attribute tiers is invalid.');
            }
            $key_expected = 0;
            foreach ($this->tiers as $key => $value) {
                if ($key !== $key_expected) {
                    \Rebill\SDK\Rebill::log('Price: the key tiers['.$key.'] is invalid, key expected '.$key_expected.': '.var_export($this->tiers, true));
                    throw new \Exception('Price: the key tiers['.$key.'] is invalid, key expected '.$key_expected);
                }
                ++$key_expected;
                if (!($value instanceof \Rebill\SDK\Models\Shared\Tiers)) {
                    \Rebill\SDK\Rebill::log('Price: the tiers['.$key.'] not is Tiers instance: '.var_export($this->tiers, true));
                    throw new \Exception('Price: the tiers['.$key.'] not is Tiers instance');
                }
                $value->validate();
            }
        }
        if (isset($this->frequency)) {
            if (!($this->frequency instanceof \Rebill\SDK\Models\Shared\Frequency)) {
                \Rebill\SDK\Rebill::log('Price: the frequency not is Frequency instance: '.var_export($this->frequency, true));
                throw new \Exception('Price: the frequency not is Frequency instance');
            }
            $this->frequency->validate();
        }
        if (isset($this->freeTrial)) {
            if (!($this->freeTrial instanceof \Rebill\SDK\Models\Shared\Frequency)) {
                \Rebill\SDK\Rebill::log('Price: the freeTrial not is Frequency instance: '.var_export($this->frequency, true));
                throw new \Exception('Price: the freeTrial not is Frequency instance');
            }
            $this->freeTrial->validate();
        }
        return $this;
    }

    public function format()
    {
        if (isset($this->frequency) && !is_object($this->frequency)) {
            $this->frequency = (new \Rebill\SDK\Models\Shared\Frequency)->setAttributes($this->frequency);
        }
        if (isset($this->freeTrial) && !is_object($this->freeTrial)) {
            $this->freeTrial = (new \Rebill\SDK\Models\Shared\Frequency)->setAttributes($this->freeTrial);
        }
        return $this;
    }

    /**
     * Create Model
     *
     * @param string $item_id Item ID.
     * 
     * @return bool|\Rebill\SDK\Models\Response\PriceResponse NewGatewayResponse Model
     */
    public function add($item_id)
    {
        return parent::create(str_replace('{item_id}', $item_id, self::$endpoint));
    }
}
