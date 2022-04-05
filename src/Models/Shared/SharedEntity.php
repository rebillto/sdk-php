<?php namespace Rebill\SDK\Models\Shared;

/**
*  SharedEntity
*
*  @author Kijam
*/

abstract class SharedEntity
{
    /**
     * Get Model in Array format
     *
     * @return array
     */
    public function toArray($data = null)
    {
        if ($data === null) {
            $data = $this;
        }
        if (is_array($data) || is_object($data)) {
            $result = [];
            foreach ($data as $key => $value) {
                $result[$key] = $value && (is_array($value) || is_object($value)) ? $this->toArray($value) : $value;
                if ($result[$key] === null) {
                    unset($result[$key]);
                }
            }
            return $result;
        }
        return $data;
    }

    /**
     * Set all Attributes values, example:
     *      (new Rebill\SDK\Models\Shared\CurrentModel)->setAttributes([ 'user' => 'rebill', 'pass' => '123' ]);
     *
     * @param array $values All values.
     *
     * @return object Return recursive model.
     */
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

    /**
     * Check format of Attributes
     *
     * @return object Recursive Model
     */
    protected function format()
    {
        //...
        return $this;
    }

    /**
     * Validate Attributes
     *
     * @return object Recursive Model
     */
    public function validate()
    {
        //...
        return $this;
    }
}
