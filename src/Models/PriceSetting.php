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
     * Create Model
     *
     * @param string $item_id Item ID.
     * 
     * @return bool|\Rebill\SDK\Models\Response\PriceResponse NewGatewayResponse Model
     */
    public function edit($price_id)
    {
        return parent::create(str_replace('{price_id}', $price_id, self::$endpoint));
    }

}