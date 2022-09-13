<?php namespace Rebill\SDK\Models;

/**
*  Payment
*
*  @author Kijam
*/
class Payment extends \Rebill\SDK\RebillModel
{
    /**
     * Endpoint of Model
     *
     * @var int
     */
    protected static $endpoint = '/payments';

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
        'currency',
        'amount',
        'metadata',
        'status',
        'errorMessage',
        'cartId',
        'payer',
        'prices',
        'subscriptions',
        'id'
    ];
    /**
     * List attributes required in PUT or POST request
     *
     * @var array<int, string>
     */
    protected $required = [
    ];

    /**
     * List attributes ignored in PUT or POST request
     *
     * @var array<int, string>
     */
    protected $ignore = [
        'id',
        'prices',
        'subscriptions'
    ];

    /**
     * Check format of Attributes
     *
     * @return object Recursive Model
     */
    protected function format()
    {
        if ($this->payer && !is_object($this->payer)) {
            $this->payer = (new \Rebill\SDK\Models\Shared\Profile)->setAttributes($this->payer);
        }
        return $this;
    }

    /**
     * Get Payment list by Receipts ID
     *
     * @return array<\Rebill\SDK\Models\Payment> Payment List.
     */
    public static function getByReceiptsId($cart_id)
    {
        $data = \Rebill\SDK\Rebill::getInstance()->callApiGet('/receipts/'.$cart_id.'/payments');
        $result = [];
        if ($data) {
            foreach ($data as $payment) {
                $result[] = (new self)->setAttributes($payment);
            }
        }
        return $result;
    }

    /**
     * Get Prices ID by Payment
     *
     * @return Payment Recursive Model with prices attribute filled
     */
    public function getPrices()
    {
        if (empty($this->id)) {
            return $this;
        }
        if (isset($this->prices)) {
            return $this;
        }
        $data = \Rebill\SDK\Rebill::getInstance()->callApiGet('/payments/'.$this->id.'/prices');
        if ($data && isset($data['priceIds'])) {
            $this->prices = $data['priceIds'];
        }
        return $this;
    }
    /**
     * Get Subscriptions ID by Payment
     *
     * @return Payment Recursive Model with subscriptions attribute filled
     */
    public function getSubscriptions()
    {
        if (empty($this->id)) {
            return $this;
        }
        if (isset($this->subscriptions)) {
            return $this;
        }
        $data = \Rebill\SDK\Rebill::getInstance()->callApiGet('/payments/'.$this->id.'/billingSchedules');
        if ($data && isset($data['billingScheduleIds'])) {
            $this->subscriptions = $data['billingScheduleIds'];
        }
        return $this;
    }
    /**
     * Refund current payment
     *
     * @return bool Result of refund
     */
    public function refund()
    {
        return !!\Rebill\SDK\Rebill::getInstance()->callApiPost('/refund', ['paymentId' => $this->id]);
    }
}
