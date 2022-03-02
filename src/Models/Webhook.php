<?php namespace Rebill\SDK\Models;

/**
*  Webhook class
*
*  @author Kijam
*/
class Webhook extends \Rebill\SDK\RebillModel
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
    protected static $endpoint = '/webhooks';

    /**
     * Attribute List
     *
     * @var array<string, mixed>
     */
    protected $attributes = [
        'action' => null,
        'sync_url' => null,
        'request_headers' => null,
        'organization_id' => null,
        'webhook_sync' => null,
        'frequency' => null,
        'frequency_type' => null,
        'last_run' => null,
        'enabled' => null,
        'createdAt' => null,
        'updatedAt' => null,
        'deletedAt' => null
    ];
    /**
     * Format List
     *
     * @var array<string, array<int, string>>
     */
    protected $format = [
        'sync_url' => ['validateUrl'],
        'action' => ['is_string'],
        'frequency' => ['is_numeric'],
        'frequency_type' => ['is_string', 'validateFrequencyType'],
    ];

    /**
     * List attributes ignored in PUT or POST request
     *
     * @var array<int, string>
     */
    protected $ignore = [
        'webhook_sync',
        'last_run',
        'enabled',
        'updatedAt',
        'createdAt',
        'deletedAt',
    ];
    /**
     * List attributes required in PUT or POST request
     *
     * @var array<int, string>
     */
    protected $required = [
        'sync_url',
        'action',
        'frequency',
        'frequency_type'
    ];
    public static function getAll($endpoint = false) {
        return parent::getAll($endpoint);
    }
}
