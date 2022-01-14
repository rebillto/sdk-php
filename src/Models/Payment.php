<?php namespace Rebill\SDK\Models;

/**
*  Payment class
*
*  @author Kijam
*/
class Payment extends \Rebill\SDK\RebillModel
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
    protected $endpoint = '/payments';

    /**
     * Attribute List
     *
     * @var array<string, mixed>
     */
    protected $attributes = [
        'plan_id' => null,
        'related_payment_id' => null,
        'subscription_id' => null,
        'customer_id' => null,
        'invoice_id' => null,
        'gateway_id' => null,
        'gateway_payment_id' => null,
        'date_created' => null,
        'due_date' => null,
        'date_approved' => null,
        'date_last_updated' => null,
        'money_release_date' => null,
        'gateway_collector_id' => null,
        'operation_type' => null,
        'gateway_payer_id' => null,
        'binary_mode' => null,
        'gateway_order_id' => null,
        'description' => null,
        'currency_id' => null,
        'transaction_amount' => null,
        'transaction_amount_refunded' => null,
        'coupon_amount' => null,
        'campaign_id' => null,
        'coupon_code' => null,
        'financial_institution' => null,
        'net_received_amount' => null,
        'total_paid_amount' => null,
        'installment_amount' => null,
        'overpaid_amount' => null,
        'payment_method_reference_id' => null,
        'differential_pricing_id' => null,
        'application_fee' => null,
        'status' => null,
        'status_detail' => null,
        'capture' => null,
        'captured' => null,
        'payment_type' => null,
        'call_for_authorize_id' => null,
        'payment_method_id' => null,
        'issuer_id' => null,
        'payment_type_id' => null,
        'card_id' => null,
        'gateway_card_id' => null,
        'card_last_four_digits' => null,
        'card_first_six_digits' => null,
        'card_expiration_year' => null,
        'card_expiration_month' => null,
        'card_date_created' => null,
        'card_date_last_updated' => null,
        'cardholder_name' => null,
        'cardholder_identification_number' => null,
        'cardholder_identification_type' => null,
        'statement_descriptor' => null,
        'installments' => null,
        'notification_url' => null,
        'callback_url' => null,
        'debit_date' => null,
        'finance_charge' => null,
        'next_payment_date' => null,
        'marketplace' => null,
        'marketplace_fee' => null,
        'authorization_code' => null,
        'reason' => null,
        'vendor_id' => null,
        'company_id' => null,
        'user_id' => null,
        'external_reference' => null,
        'organization_id' => null,
        'ip_address' => null,
        'bank_account_id' => null,
        'webhook_sync' => null,
        'updatedAt' => null,
        'createdAt' => null,
        'processor' => null
    ];
    /**
     * Format List
     *
     * @var array<string, array<int, string>>
     */
    protected $format = [
        'organization_id' => ['is_numeric'],
        'vendor_id' => ['is_numeric'],
        'company_id' => ['is_numeric'],
        'user_id' => ['is_numeric'],
        'plan_id' => ['is_numeric'],
        'related_payment_id' => ['is_numeric'],
        'subscription_id' => ['is_numeric'],
        'customer_id' => ['is_numeric'],
        'invoice_id' => ['is_numeric'],
        'card_id' => ['is_numeric'],
        'gateway_id' => ['is_numeric'],
        'gateway_payment_id' => ['is_numeric'],
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
    public function refund()
    {
        if (!$this->id || !isset($this->gateway_payment_id)) {
            return false;
        }
        $result = \Rebill\SDK\Rebill::getInstance()->callApiPost($this->endpoint.'/refund', [
            'payment_id' => $this->gateway_payment_id
        ]);
        return $result && $result['success'];
    }
}
