<?php namespace Rebill\SDK;

/**
*  Model class
*
*  @author Kijam
*/
abstract class RebillModel
{
    /**
     * Set all Property values, example:
     *      Rebill::getInstance()->setProp([ 'user' => 'rebill', 'pass' => '123' ]);
     *
     * @param   array $values All values.
     *
     * @return  object Return recursive model.
     */
    public function setProp(array $values)
    {
        foreach ($values as $key => $value) {
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
    public function setAttributes(array $values)
    {
        foreach ($values as $key => $value) {
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
                $this->__set($key, $args[0]);
            }
        }
        if ($cmd == 'get') {
            if (\property_exists($this, $key)) {
                return $this->{$key};
            } else {
                return isset($this->attributes[$key])?$this->attributes[$key]:null;
            }
        }
        return $this;
    }

    /**
     * Magic method of Model to set Attribute.
     *
     * @param   string $name Attribute name.
     * @param   array  $value Value.
     *
     * @return  void
     */
    public function __set($name, $value)
    {
        if (!\array_key_exists($name, $this->attributes)) {
            throw new \Exception("The attribute '$name' is invalid.");
        }
        $this->attributes[$name] = $value;
    }

    /**
     * Magic method of Model to get Attribute.
     *
     * @param   string $name Attribute name.
     *
     * @return  mixed
     */
    public function __get($name)
    {
        if (!\array_key_exists($name, $this->attributes)) {
            throw new \Exception("The attribute '$name' is invalid.");
        }
        return $this->attributes[$name];
    }

    /**
     * Magic method of Model to isset Attribute.
     *
     * @param   string $name Attribute name.
     *
     * @return  bool
     */
    public function __isset($name)
    {
        return $name === 'id' || isset($this->attributes[$name]);
    }


    /**
     * Magic method of Model to unset Attribute.
     *
     * @param   string $name Attribute name.
     *
     * @return  void
     */
    public function __unset($name)
    {
        if ($name === 'id') {
            $this->id = null;
        } elseif (isset($this->attributes[$name])) {
            $this->attributes[$name] = null;
        }
    }

    /**
     * Validate Model attributes.
     *
     * @return  object Recursive Model
     */
    public function validate()
    {
        if (\property_exists($this, 'required') && \count($this->required)) {
            foreach ($this->required as $key) {
                if (!isset($this->attributes[$key]) || empty($this->attributes[$key])) {
                    throw new \Exception("The attribute '$key' is required.");
                }
            }
        }
        if (\property_exists($this, 'format') && \count($this->format)) {
            foreach ($this->format as $key => $validators) {
                foreach ($validators as $validator) {
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
     * Get all elements of this Model
     *
     * @return array<mixed>
     */
    public function getAll()
    {
        $list = Rebill::getInstance()->callApiGet($this->endpoint);
        $result = [];
        if ($list && isset($list['response']) && is_array($list['response'])) {
            $class_name = get_class($this);
            foreach ($list['response'] as $element) {
                $obj = new $class_name;
                $obj->setAttributes($element);
                $result[] = $obj;
            }
        }
        return $result;
    }

    /**
     * Create Model
     *
     * @return bool|object Recursive Model
     */
    public function create()
    {
        $data = $this->validate()->attributes;
        foreach (\array_keys($data) as $key) {
            if (\in_array($key, $this->ignore, true)) {
                unset($data[$key]);
            }
        }
        $result = Rebill::getInstance()->callApiPost($this->endpoint, $data);
        if ($result && $result['success']) {
            if (isset($result['response'])) {
                $this->setAttributes($result['response']);
            }
            return $this;
        }
        return false;
    }

    /**
     * Update Model
     *
     * @return bool|object Recursive Model
     */
    public function update()
    {
        $data = $this->validate()->attributes;
        if (!$this->id) {
            throw new \Exception("The attribute 'id' is required.");
        }
        foreach (\array_keys($data) as $key) {
            if (\in_array($key, $this->ignore, true)) {
                unset($data[$key]);
            }
        }
        $data['id'] = $this->id;
        $result = Rebill::getInstance()->callApiPut($this->endpoint, $data);
        if ($result && $result['success']) {
            if (isset($result['response'])) {
                $this->setAttributes($result['response']);
            }
            return $this;
        }
        return false;
    }

    /**
     * Delete Model
     *
     * @return bool
     */
    public function delete()
    {
        if (!$this->id) {
            throw new \Exception("The attribute 'id' is required.");
        }
        $result = Rebill::getInstance()->callApiDelete($this->endpoint.'/'.$this->id);
        return $result && $result['success'];
    }
    
    /**
     * Validate email format.
     *
     * @return bool
     */
    public static function validateEmail($value)
    {
        return filter_var($value, FILTER_VALIDATE_EMAIL);
    }

    /**
     * Validate url format.
     *
     * @return bool
     */
    public static function validateUrl($value)
    {
        return filter_var($value, FILTER_VALIDATE_URL);
    }

    /**
     * Validate Frequency Type format.
     *
     * @return bool
     */
    public static function validateFrequencyType($value)
    {
        switch ($value) {
            case 'minutes':
            case 'days':
            case 'months':
            case 'years':
                return true;
        }
        return false;
    }
}
