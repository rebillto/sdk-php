<?php namespace Rebill\SDK\Models;

/**
*  Card class
*
*  @author Kijam
*/
class Card extends \Rebill\SDK\RebillModel
{
    /**
     * ID of Model
     *
     * @var int
     */
    public $id;

    /**
     * Endpoint of Model
     *
     * @var int
     */
    protected static $endpoint = '/cards';

    /**
     * Attribute List
     *
     * @var array<string, mixed>
     */
    protected $attributes = [
        'customer_id' => null,
        'gateway_id' => null,
        'gateway_card_id' => null,
        'gateway_card_token' => null,
        'expiration_month' => null,
        'expiration_year' => null,
        'first_six_digits' => null,
        'last_four_digits' => null,
        'payment_method_id' => null,
        'payment_method_name' => null,
        'payment_method_type_id' => null,
        'thumbnail' => null,
        'secure_thumbnail' => null,
        'security_code_length' => null,
        'security_code_location' => null,
        'issuer_id' => null,
        'issuer_name' => null,
        'cardholder_name' => null,
        'cardholder_identification_number' => null,
        'cardholder_identification_type' => null,
        'live_mode' => null,
        'gateway_user_id' => null,
        'organization_id' => null,
        'updatedAt' => null,
        'createdAt' => null,
        'deletedAt' => null,
        'status' => null
    ];
    /**
     * Format List
     *
     * @var array<string, array<int, string>>
     */
    protected $format = [
    ];

    /**
     * List attributes ignored in PUT or POST request
     *
     * @var array<int, string>
     */
    protected $ignore = [ ];
    /**
     * List attributes required in PUT or POST request
     *
     * @var array<int, string>
     */
    protected $required = [ ];

    
    /**
     * Refund Payment
     *
     * @return bool|object Recursive Model
     */
    public function requestRenew()
    {
        if (!$this->id) {
            return false;
        }
        $result = Rebill::getInstance()->callApiPost($this->endpoint.'/requestRenewCard', [
            'id'                  => $this->id,
            'request_destination' => 'customer',
            'vendor_id'           => null,
        ]);
        return $result && $result['success'];
    }

    /**
     * Get element of this Model by ID
     *
     * @return mixed|bool
     */
    public static function getById($id, $endpoint = false)
    {
        return parent::getById($id, $endpoint?$endpoint:static::$endpoint.'/id/'.(int)$id);
    }
}
