<?php namespace Rebill\SDK\Models\Shared;

/**
*  PaidBag
*
*  @author Kijam
*/
class PaidBag extends SharedEntity
{
    public $content;
    public $payment;
    public $schedules;
    public function format() {
        if ($this->payment && !is_object($this->payment)) {
            $this->payment = (new \Rebill\SDK\Models\Payment)->setAttributes($this->payment);
        }
        if ($this->content) {
            foreach ($this->content as $k => $v) {
                if (!is_object($v)) {
                    $this->content[$k] = (new \Rebill\SDK\Models\Price)->setAttributes($v);
                }
            }
        }
        return $this;
    }
}
