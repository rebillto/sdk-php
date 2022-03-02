<?php namespace Rebill\SDK\Models;

/**
*  Setting class
*
*  @author Kijam
*/
class Setting extends \Rebill\SDK\RebillModel
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
    protected static $endpoint = '/organizations/settings';

    /**
     * Attribute List
     *
     * @var array<string, mixed>
     */
    protected $attributes = [
        'uuid' => null,
        'gateway_id' => null,
        'gateway_mode' => null,
        'gateway_client_id' => null,
        'gateway_client_secret' => null,
        'gateway_public_key' => null,
        'gateway_access_token' => null,
        'gateway_app_id' => null,
        'gateway_country_id' => null,
        'gateway_refresh_token' => null,
        'statement_descriptor' => null,
        'mailer_provider' => null,
        'mailer_client_id' => null,
        'mailer_client_secret' => null,
        'mailer_host' => null,
        'mailer_from' => null,
        'mailer_user' => null,
        'require_phone_number' => null,
        'company_id' => null,
        'organization_id' => null,
        'required_phone' => null,
        'logo_url' => null,
        'colors_scheme' => null,
        'platform_frontend_url' => null,
        'checkout_url' => null,
        'onboarding_redirect' => null,
        'user_id_mercadopago' => null,
        'application_id_mercadopago' => null,
        'show_signup_mercadopago' => null,
        'updatedAt' => null,
        'createdAt' => null
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
     * Get all elements of this Model
     *
     * @return array<mixed>
     */
    public static function getAll($endpoint = false)
    {
        $class_name = get_called_class();
        $data = \Rebill\SDK\Rebill::getInstance()->callApiGet($endpoint ? $endpoint : static::$endpoint);
        $result = [];
        if ($data && isset($data['response'])) {
            $obj = new $class_name;
            $obj->setAttributes($data['response']);
            $obj->to_put_attributes = [];
            return $obj;
        }
        return false;
    }


}
