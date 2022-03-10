<?php namespace Rebill\SDK\Models;

/**
*  Frequecy
*
*  @author Kijam
*/
class Item extends \Rebill\SDK\RebillModel
{
    /**
     * Endpoint of Model
     *
     * @var int
     */
    protected static $endpoint = '/item';

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
        'name',
        'description',
        'prices',
        'metadata',
        'id'
    ];
    /**
     * List attributes required in PUT or POST request
     *
     * @var array<int, string>
     */
    protected $required = [
        'name',
        'description',
        'prices',
    ];

    /**
     * List attributes ignored in PUT or POST request
     *
     * @var array<int, string>
     */
    protected $ignore = [
        'id'
    ];

    /**
     * Format List
     *
     * @var array<string, array<int, string>>
     */
    protected $format = [
        'prices' => ['is_array', 'validatePriceList'],
        'metadata' => ['validateMetadata'],
        'name' => ['is_string'],
        'description' => ['is_string'],
        'description' => ['is_string'],
        'currency' => ['is_string'],
        'amount' => ['is_numeric'],
        'decimalPlaces' => ['is_numeric'],
    ];
    public static function validateMetadata($field) {
        if (!is_array($field)) {
            \Rebill\SDK\Rebill::log('Item: the metadata not is associative array: '.var_export($field, true));
            throw new \Exception('Item: the metadata not is associative array');
        }
        foreach($field as $key => $value) {
            if (!is_string($key) || is_numeric($key)) {
                \Rebill\SDK\Rebill::log('Item: the metadata not is associative array: '.var_export($field, true));
                throw new \Exception('Item: the metadata not is associative array');
            }
            if (!is_string($value) && !is_numeric($value)) {
                \Rebill\SDK\Rebill::log('Item: the metadata['.$key.'] not is string or numeric value: '.var_export($value, true));
                throw new \Exception('Item: the metadata['.$key.'] not is string or numeric value');
            }
        }
        return true;
    }
    public static function validatePriceList($field) {
        $key_expected = 0;
        foreach ($field as $key => &$value) {
            if ($key !== $key_expected) {
                \Rebill\SDK\Rebill::log('Item: the key prices['.$key.'] is invalid, key expected '.$key_expected.': '.var_export($field, true));
                throw new \Exception('Item: the key prices['.$key.'] is invalid, key expected '.$key_expected);
            }
            ++$key_expected;
            if (!($value instanceof \Rebill\SDK\Models\Price)) {
                \Rebill\SDK\Rebill::log('Item: the prices['.$key.'] not is Price instance: '.var_export($field, true));
                throw new \Exception('Item: the prices['.$key.'] not is Price instance');
            }
            $value->validate();
        }
        return true;
    }
    public function update($endpoint = false) {
        if (!isset($this->id)) {
            \Rebill\SDK\Rebill::log('Item: the ID is required for update');
            throw new \Exception('Item: the ID is required for update');
        }
        return parent::update(($endpoint ? $endpoint : static::$endpoint).'/'.$this->id);
    }
    public function delete($endpoint = false) {
        if (!isset($this->id)) {
            \Rebill\SDK\Rebill::log('Item: the ID is required for delete');
            throw new \Exception('Item: the ID is required for delete');
        }
        return parent::delete(($endpoint ? $endpoint : static::$endpoint).'/'.$this->id);
    }

    /**
     * Get list element of this Model
     *
     * @return mixed|bool
     */
    public static function all($endpoint = false)
    {
        \Rebill\SDK\Rebill::log('get all: '.$endpoint);
        $data = \Rebill\SDK\Rebill::getInstance()->callApiGet(($endpoint ? $endpoint : static::$endpoint).'/all');
        \Rebill\SDK\Rebill::log('get data: - '.\var_export($data, true));
        if ($data && isset($data['response'])) {
            if (isset($data['response']) && isset($data['response'][0])) {
                $list = [];
                foreach($data['response'] as $item) {
                    $obj = new self;
                    $obj->setAttributes($item);
                    $obj->to_put_attributes = [];
                    $list[] = $obj;
                }
                return $list;
            }
        }
        return false;
    }
}