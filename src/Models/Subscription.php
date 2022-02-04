<?php namespace Rebill\SDK\Models;

/**
*  Payment class
*
*  @author Kijam
*/
class Subscription extends \Rebill\SDK\RebillModel
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
    protected static $endpoint = '/subscriptions';

    /**
     * Attribute List
     *
     * @var array<string, mixed>
     */
    protected $attributes = [
        'plan_id' => null,
        'gateway_id' => null,
        'customer_id' => null,
        'card_id' => null,
        'last_payment_id' => null,
        'currency_id' => null,
        'user_id' => null,
        'vendor_id' => null,
        'company_id' => null,
        'organization_id' => null,
        'region_id' => null,
        'subscription_state_id' => null,
        'frequency' => null,
        'frequency_type' => null,
        'repetitions' => null,
        'debit_date' => null,
        'current_retries' => null,
        'status' => null,
        'status_detail' => null,
        'description' => null,
        'start_date' => null,
        'end_date' => null,
        'debit_date_forced' => null,
        'next_process_date' => null,
        'current_period_end' => null,
        'current_period_start' => null,
        'retry_limit_date' => null,
        'retry_next_date' => null,
        'terms_to_charge' => null,
        'pause_date' => null,
        'resume_date' => null,
        'title' => null,
        'callback_url' => null,
        'external_reference' => null,
        'currentRepetition' => null,
        'free_trial_frequency' => null,
        'free_trial_frequency_type' => null,
        'transaction_amount' => null,
        'webhook_sync' => null,
        'items' => null,
        'updatedAt' => null,
        'createdAt' => null,
        'deletedAt' => null,
        'next_payments' => null
    ];
    /**
     * Format List
     *
     * @var array<string, array<int, string>>
     */
    protected $format = [
        'plan_id' => ['is_numeric'],
        'organization_id' => ['is_numeric'],
        'vendor_id' => ['is_numeric'],
        'company_id' => ['is_numeric'],
        'user_id' => ['is_numeric'],
        'customer_id' => ['is_numeric'],
        'card_id' => ['is_numeric'],
        'gateway_id' => ['is_numeric'],
        'frequency' => ['is_numeric'],
        'repetitions' => ['is_numeric'],
        'debit_date' => ['is_numeric'],
        'frequency' => ['is_numeric'],
        'frequency_type' => ['validateFrequencyType'],
    ];

    /**
     * List attributes ignored in PUT or POST request
     *
     * @var array<int, string>
     */
    protected $ignore = [
        'last_payment_id' => null,
        'subscription_state_id' => null,
        'current_retries' => null,
        //'start_date' => null,
        //'end_date' => null,
        'debit_date_forced' => null,
        'next_process_date' => null,
        'current_period_end' => null,
        'current_period_start' => null,
        'retry_limit_date' => null,
        'retry_next_date' => null,
        'terms_to_charge' => null,
        'pause_date' => null,
        'resume_date' => null,
        'callback_url' => null,
        'currentRepetition' => null,
        'webhook_sync' => null,
        'items' => null,
        'updatedAt' => null,
        'createdAt' => null,
        'deletedAt' => null,
        'next_payments' => null
    ];
    /**
     * List attributes required in PUT or POST request
     *
     * @var array<int, string>
     */
    protected $required = [ ];

    /**
     * Get element of this Model by ID
     *
     * @return mixed|bool
     */
    static function getById($id, $endpoint = false)
    {
        return parent::getById($id, $endpoint?$endpoint:static::$endpoint.'/id/'.(int)$id);
    }

    /**
     * Refund Payment
     *
     * @return bool|object Recursive Model
     */
    public function cancel()
    {
        if (!$this->id) {
            return false;
        }
        $this->status = 'cancelled';

        return $this->update();
    }
}
