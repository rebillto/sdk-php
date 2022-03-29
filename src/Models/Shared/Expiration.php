<?php namespace Rebill\SDK\Models\Shared;

/**
*  Expiration
*
*  @author Kijam
*/
class Expiration extends SharedEntity
{
    public $month;
    public $year;

    public function validate()
    {
        if (empty($this->month) || !\is_numeric($this->month)) {
            \Rebill\SDK\Rebill::log('Expiration: month not is numeric: '.var_export($v, true));
            throw new \Exception('The attribute month not is numeric.');
        }
        if ($this->month < 1 || $this->month > 12) {
            \Rebill\SDK\Rebill::log('Expiration: month is invalid: '.var_export($v, true));
            throw new \Exception('The attribute month is invalid.');
        }
        if (empty($this->year) || !\is_numeric($this->year)) {
            \Rebill\SDK\Rebill::log('Expiration: year not is numeric: '.var_export($v, true));
            throw new \Exception('The attribute year not is numericg.');
        }
        return $this;
    }
}
