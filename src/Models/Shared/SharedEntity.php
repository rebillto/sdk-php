<?php namespace Rebill\SDK\Models\Shared;

/**
*  SharedEntity
*
*  @author Kijam
*/

abstract class SharedEntity
{
    public function toArray($data = null)
    {
        if ($data === null) {
            $data = $this;
        }
        if (is_array($data) || is_object($data)) {
            $result = [];
            foreach ($data as $key => $value) {
                $result[$key] = $value && (is_array($value) || is_object($value)) ? $this->toArray($value) : $value;
            }
            return $result;
        }
        return $data;
    }
    public function setAttributes($values)
    {
        foreach ($values as $key => $value) {
            if (\is_string($key)) {
                if (\property_exists($this, $key)) {
                    $this->{$key} = $value;
                }
            }
        }
        return $this->format();
    }
    public function format()
    {
        //...
        return $this;
    }
}
