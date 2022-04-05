<?php namespace Rebill\SDK\Models;

/**
*  Frequecy
*
*  @author Kijam
*/
class PriceSetting extends \Rebill\SDK\RebillModel
{
    /**
     * Endpoint of Model
     *
     * @var int
     */
    protected static $endpoint = '/item/price/setting/{price_id}';

    /**
     * Attribute List
     *
     * @var array<string, mixed>
     */
    protected $attributes = [
        'documentRequired',
        'phoneRequired',
        'billingAddressRequired',
        'showImage',
        'coupons',
        'redirectUrl'
    ];

    /**
     * Format List
     *
     * @var array<string, array<int, string>>
     */
    protected $format = [
        'documentRequired' => ['is_bool'],
        'phoneRequired' => ['is_bool'],
        'billingAddressRequired' => ['is_bool'],
        'showImage' => ['is_bool'],
        'coupons' => ['is_array'],
        'redirectUrl' => ['is_string'],
    ];

    /**
     * Modify Price Setting by Price ID
     *
     * @param string $price_id Price ID.
     *
     * @return bool|\Rebill\SDK\Models\PriceSetting PriceSetting Model
     */
    public function edit($price_id)
    {
        return parent::create(str_replace('{price_id}', $price_id, self::$endpoint));
    }
}
