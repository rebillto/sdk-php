<?php namespace Rebill\SDK\Models\Response;

/**
*  ItemResponse class
*
*  @author Kijam
*/
class ItemResponse extends \Rebill\SDK\RebillModel
{
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
        'item',
        'prices'
    ];

    /**
     * Check format of Attributes
     *
     * @return object Recursive Model
     */
    protected function format()
    {
        if (isset($this->item) && !is_object($this->item)) {
            $this->item = (new \Rebill\SDK\Models\Item)->setAttributes($this->item);
        }
        if (isset($this->prices) && is_array($this->prices)) {
            foreach ($this->prices as $k => $price) {
                if ($price && !is_object($price)) {
                    $this->prices[$k] = (new \Rebill\SDK\Models\Price)->setAttributes($price);
                }
            }
        }
        return $this;
    }

}