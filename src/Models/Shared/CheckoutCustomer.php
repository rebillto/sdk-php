<?php namespace Rebill\SDK\Models\Shared;

/**
*  Profile
*
*  @author Kijam
*/
class CheckoutCustomer extends SharedEntity
{
    /**
     * First Name
     *
     * @var string
     */
    public $firstName;

    /**
     * Last Name
     *
     * @var string
     */
    public $lastName;

    /**
     * Phone
     *
     * @var string
     */
    public $phone;

    /**
     * Birthday
     *
     * @var string
     */
    public $birthday;

    /**
     * Tax ID
     *
     * @var \Rebill\SDK\Models\Shared\TaxId
     */
    public $taxId;

    /**
     * Personal Tax ID
     *
     * @var \Rebill\SDK\Models\Shared\TaxId
     */
    public $personalId;

    /**
     * Address
     *
     * @var \Rebill\SDK\Models\Shared\Address
     */
    public $address;

    /**
     * E-Mail
     *
     * @var string
     */
    public $email;

    /**
     * Card
     *
     * @var string
     */
    public $card;

    /**
     * Check format of Attributes
     *
     * @return object Recursive Model
     */
    protected function format()
    {
        if ($this->address && !is_object($this->address)) {
            $this->address = (new \Rebill\SDK\Models\Shared\Address)->setAttributes($this->address);
        }
        if ($this->phone && !is_object($this->phone)) {
            $this->phone = (new \Rebill\SDK\Models\Shared\Phone)->setAttributes($this->phone);
        }
        if ($this->taxId && !is_object($this->taxId)) {
            $this->taxId = (new \Rebill\SDK\Models\Shared\TaxId)->setAttributes($this->taxId);
        }
        if ($this->personalId && !is_object($this->personalId)) {
            $this->personalId = (new \Rebill\SDK\Models\Shared\TaxId)->setAttributes($this->personalId);
        }
        if ($this->card && !is_object($this->card)) {
            $this->card = (new \Rebill\SDK\Models\Card)->setAttributes($this->card);
        }
        return $this;
    }

    /**
     * Validate Model attributes.
     *
     * @return object Recursive Model
     */
    public function validate()
    {
        if (empty($this->firstName)) {
            \Rebill\SDK\Rebill::log('CheckoutCustomer: firstName is required');
            throw new \Exception('CheckoutCustomer: firstName is required');
        }
        if (empty($this->lastName)) {
            \Rebill\SDK\Rebill::log('CheckoutCustomer: lastName is required');
            throw new \Exception('CheckoutCustomer: lastName is required');
        }
        if (empty($this->email)) {
            \Rebill\SDK\Rebill::log('CheckoutCustomer: email is required');
            throw new \Exception('CheckoutCustomer: email is required');
        }
        if (empty($this->card)) {
            \Rebill\SDK\Rebill::log('CheckoutCustomer: card is required');
            throw new \Exception('CheckoutCustomer: card is required');
        }
        return $this;
    }
}
