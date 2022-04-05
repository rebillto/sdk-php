<?php namespace Rebill\SDK\Models;

/**
*  Subscription
*
*  @author Kijam
*/
class Webhook extends \Rebill\SDK\RebillModel
{
    /**
     * Endpoint of Model
     *
     * @var int
     */
    protected static $endpoint = '/notifications/webhooks';

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
        'event',
        'url',
        'childId',
        'id'
    ];
    /**
     * List attributes required in PUT or POST request
     *
     * @var array<int, string>
     */
    protected $required = [
        'event',
        'url',
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
     * List of all Webhook
     *
     * @return array<\Rebill\SDK\Models\Webhook>|bool
     */
    public static function all()
    {
        $return = [];
        $data = \Rebill\SDK\Rebill::getInstance()->callApiGet(static::$endpoint);
        \Rebill\SDK\Rebill::log('get data: - '.\var_export($data, true));
        if ($data) {
            foreach($data as $d) {
                $obj = new self;
                $obj->setAttributes($d);
                $obj->to_put_attributes = [];
                $return[] = $obj;
            }
        }
        return $return;
    }
}
