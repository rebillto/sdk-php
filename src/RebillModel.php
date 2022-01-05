<?php namespace Rebill\SDK;

/**
*  Model class
*
*  @author Kijam
*/
abstract class RebillModel {
    /**
     * Set all Property values, example:
     *      Rebill::getInstance()->setProp([ 'user' => 'rebill', 'pass' => '123' ]);
     *
     * @param   array $values All values.
     *
     * @return  object Return recursive model.
     */
    public function setProp(array $values) {
        foreach($values as $key => $value) {
            if (\is_string($key)) {
                if (\property_exists($this, $key)) {
                    $this->{$key} = $value;
                }
            }
        }
        return $this;
    }
    
    /**
     * Set all Attributes values, example:
     *      (new Rebill\SDK\Models\Customer)->setAttributes([ 'user' => 'rebill', 'pass' => '123' ]);
     *
     * @param   array $values All values.
     *
     * @return  object Return recursive model.
     */
    public function setAttributes(array $values) {
        foreach($values as $key => $value) {
            if (\is_string($key)) {
                if ($key == 'id') {
                    $this->id = $value;
                    continue;
                }
                if (\key_exists($key, $this->attributes)) {
                    $this->attributes[$key] = $value;
                }
            }
        }
        return $this;
    }

    /**
     * Magic method of Model to set/get Property or Attribute.
     *
     * @param   string $method Method.
     * @param   array  $args Arguments.
     *
     * @return  bool|array
     */
	public function __call($method, $args)
	{
        $cmd = substr($method, 0, 3);
        $key = substr($method, 3);
        if (!\property_exists($this, $key)) {
            $key = \lcfirst($key);
        }
        if (!\property_exists($this, $key) ||
            \property_exists($this, 'attributes') && !isset($this->attributes[$key])) {
            if ($cmd == 'get') {
                return null;
            }
            return $this;
        }
        if ($cmd == 'set' && count($args) == 1) {
            if (\property_exists($this, $key)) {
                $this->{$key} = $args[0];
            } else {
                $this->attributes[$key] = $args[0];
            }
        }
        if ($cmd == 'get') {
            if (\property_exists($this, $key)) {
                return $this->{$key};
            } else {
                return $this->attributes[$key];
            }
        }
        return $this;
    }

    /**
     * Validate Model attributes.
     *
     * @return  object Recursive Model
     */
    public function validate() {
        if (\property_exists($this, 'required') && \count($this->required)) {
            foreach($this->required as $key) {
                if (!isset($this->attributes[$key]) || empty($this->attributes[$key])) {
                    throw new \Exception("The attribute '$key' is required.");
                }
            }
        }
        if (\property_exists($this, 'format') && \count($this->format)) {
            foreach($this->format as $key => $validators) {
                foreach($validators as $validator) {
                    if (isset($this->attributes[$key]) && !empty($this->attributes[$key])) {
                        if (\method_exists($this, $validator)) {
                            if (!self::$validator($this->attributes[$key])) {
                                throw new \Exception("Validation of $validator for '$key' return false");
                            }
                        } elseif (\function_exists($validator)) {
                            if (!$validator($this->attributes[$key])) {
                                throw new \Exception("Validation of $validator for '$key' return false");
                            }
                        }
                    }
                }
            }
        }
        return $this;
    }

    /**
     * Validate email format.
     *
     * @return bool
     */
    static public function validateEmail($value) {
        return filter_var($value, FILTER_VALIDATE_EMAIL);
    }
}