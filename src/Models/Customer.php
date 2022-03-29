<?php namespace Rebill\SDK\Models;

/**
*  Customer class
*
*  @author Kijam
*/
class Customer extends \Rebill\SDK\RebillModel
{
    /**
     * Endpoint of Model
     *
     * @var int
     */
    protected static $endpoint = '/customers';

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
        'user',
        'profile',
        'cards',
        'id',
    ];

    /**
     * Format List
     *
     * @var array<string, array<int, string>>
     */
    protected $format = [
        'user' => ['validateUser'],
        'profile' => ['validateProfile'],
        'cards' => ['validateCards'],
        'id' => ['is_string'],
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
     * List attributes required in PUT or POST request
     *
     * @var array<int, string>
     */
    protected $required = [
        'user'
    ];

    /**
     * Response Model
     *
     * @var string
     */
    protected $responseClass = \Rebill\SDK\Models\Response\CustomerResponse::class;

    /**
     * Validate Address Field
     *
     * @var array<int, string>
     */
    public static function validateUser($field)
    {
        return $field instanceof \Rebill\SDK\Models\Shared\User && $field->validate();
    }

    /**
     * Validate Address Field
     *
     * @var array<int, string>
     */
    public static function validateProfile($field)
    {
        return $field instanceof \Rebill\SDK\Models\Shared\Profile && $field->validate();
    }

    /**
     * Validate Address Field
     *
     * @var array<int, string>
     */
    public static function validateCards($field)
    {
        if ($field) {
            if (is_array($field)) {
                $key_expected = 0;
                foreach ($field as $key => &$value) {
                    if ($key !== $key_expected) {
                        \Rebill\SDK\Rebill::log('Customer: the key cards['.$key.'] is invalid, key expected '.$key_expected.': '.var_export($field, true));
                        throw new \Exception('Customer: the key cards['.$key.'] is invalid, key expected '.$key_expected);
                    }
                    ++$key_expected;
                    if (!($value instanceof \Rebill\SDK\Models\Card)) {
                        \Rebill\SDK\Rebill::log('Customer: the cards['.$key.'] not is Card instance: '.var_export($field, true));
                        throw new \Exception('Customer: the cards['.$key.'] not is Card instance');
                    }
                    if (!$value->validate()) {
                        throw new \Exception('Customer: the cards['.$key.'] not is a Card valid');
                    }
                }
            } else {
                \Rebill\SDK\Rebill::log('Customer: the cards not is an array: '.var_export($field, true));
                throw new \Exception('Customer: the cards not is an array');
            }
        }
        return true;
    }
    public function format()
    {
        if (isset($this->profile) && !is_object($this->profile)) {
            $this->profile = (new \Rebill\SDK\Models\Shared\Profile)->setAttributes($this->profile);
        }
        if (isset($this->user) && !is_object($this->user)) {
            $this->user = (new \Rebill\SDK\Models\Shared\User)->setAttributes($this->user);
        }
        if (isset($this->cards) && is_array($this->cards)) {
            $list = [];
            foreach ($this->cards as $card) {
                if (!is_object($card)) {
                    $list[] = (new \Rebill\SDK\Models\Card)->setAttributes($card);
                } else {
                    $list[] = $card;
                }
            }
            $this->cards = $list;
        }
        return $this;
    }
}
