<?php namespace Rebill\SDK\Models;

/**
*  GatewayDLocal class
*
*  @author Kijam
*/
class GatewayIdentificationTypes
{
    /**
     * Endpoint of Model
     *
     * @var int
     */
    protected static $endpoint = '/data/identification/{gateway_type}/{iso_country}';

    /**
     * Get All List
     *
     * @param string $iso_country ISO2 of Country.
     *
     * @return array NewGatewayResponse Model
     */
    public static function get($gateway_type, $iso_country)
    {
        $list = \Rebill\SDK\Rebill::getInstance()->callApiGet(str_replace('{gateway_type}', $gateway_type, str_replace('{iso_country}', $iso_country, self::$endpoint)));
        if (!$list || !is_array($list) || count($list) == 0) {
            return array( array('Name' => 'Other', 'value' => 'other') );
        }
        return $list;
    }
}
