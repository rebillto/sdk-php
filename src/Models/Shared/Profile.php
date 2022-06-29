<?php namespace Rebill\SDK\Models\Shared;

/**
*  Profile
*
*  @author Kijam
*/
class Profile extends SharedEntity
{
    /**
     * ID
     *
     * @var string
     */
    public $id;

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
     * List of Cards
     *
     * @var array<\Rebill\SDK\Models\Card>
     */
    public $cards;

    /**
     * E-Mail
     *
     * @var string
     */
    public $email;

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
        if ($this->cards && count($this->cards)) {
            $list = [];
            foreach ($this->cards as $card) {
                if (!is_object($card)) {
                    $list[] = (new \Rebill\SDK\Models\Card)->setAttributes($card);
                } else {
                    $list[] = $card;
                }
            }
            $this->cards = $list;
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
            \Rebill\SDK\Rebill::log('Profile: firstName is required');
            throw new \Exception('Profile: firstName is required');
        }
        if (empty($this->lastName)) {
            \Rebill\SDK\Rebill::log('Profile: lastName is required');
            throw new \Exception('Profile: lastName is required');
        }
        if (empty($this->email)) {
            \Rebill\SDK\Rebill::log('Profile: email is required');
            throw new \Exception('Profile: email is required');
        }
        return $this;
    }
}
