<?php namespace Rebill\SDK;

/**
 *  Model class
 *
 * @author Kijam
 */
abstract class RebillModel
{
    /**
     * Attribute List edited
     *
     * @var array<string, mixed>
     */
    protected $to_put_attributes = [];

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
     * @param array $values All values.
     *
     * @return object Return recursive model.
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
                    if (!\in_array($key, $this->to_put_attributes)) {
                        $this->to_put_attributes[] = $key;
                    }
                }
            }
        }
        return $this;
    }

    /**
     * Magic method of Model to set/get Property or Attribute.
     *
     * @param string $method Method.
     * @param array  $args   Arguments.
     *
     * @return bool|array
     */
    public function __call($method, $args)
    {
        $key = substr($method, 3);
        if (!\property_exists($this, $key)) {
            $key = \lcfirst($key);
        }
        $cmd = substr($method, 0, 3);
        if (!\property_exists($this, $key) 
            || \property_exists($this, 'attributes') && !isset($this->attributes[$key])
        ) {
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
                return isset($this->attributes[$key]) ? $this->attributes[$key] : null;
            }
        }
        return $this;
    }

    /**
     * Magic method of Model to set Attribute.
     *
     * @param string $name  Attribute name.
     * @param array  $value Value.
     *
     * @return void
     */
    public function __set($name, $value)
    {
        if (!\array_key_exists($name, $this->attributes)) {
            throw new \Exception("The attribute '$name' is invalid.");
        }
        $this->attributes[$name] = $value;
        if (!\in_array($name, $this->to_put_attributes)) {
            $this->to_put_attributes[] = $name;
        }
    }

    /**
     * Magic method of Model to get Attribute.
     *
     * @param string $name Attribute name.
     *
     * @return mixed
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
     * @param string $name Attribute name.
     *
     * @return bool
     */
    public function __isset($name)
    {
        return $name === 'id' || isset($this->attributes[$name]) && !empty($this->attributes[$name]);
    }


    /**
     * Magic method of Model to unset Attribute.
     *
     * @param string $name Attribute name.
     *
     * @return void
     */
    public function __unset($name)
    {
        if ($name === 'id') {
            $this->id = null;
        } elseif (isset($this->attributes[$name])) {
            $this->attributes[$name] = null;
            if (!\in_array($name, $this->to_put_attributes)) {
                $this->to_put_attributes[] = $name;
            }
        }
    }

    /**
     * Validate Model attributes.
     *
     * @return object Recursive Model
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
    public static function getAll($endpoint = false)
    {
        $class_name = get_called_class();
        $list = Rebill::getInstance()->callApiGet($endpoint ? $endpoint : static::$endpoint);
        $result = [];
        if ($list && isset($list['response']) && is_array($list['response'])) {
            foreach ($list['response'] as $element) {
                $obj = new $class_name;
                $obj->setAttributes($element);
                $obj->to_put_attributes = [];
                $result[] = $obj;
            }
        }
        return $result;
    }

    /**
     * Get element of this Model by ID
     *
     * @return mixed|bool
     */
    public static function getById($id, $endpoint = false)
    {
        Rebill::log('getById: '.$id.' - '.$endpoint);
        $data = Rebill::getInstance()->callApiGet($endpoint ? $endpoint : (static::$endpoint.'/'.(int)$id));
        Rebill::log('getById data: '.$id.' - '.\var_export($data, true));
        if ($data && isset($data['response'])) {
            if (isset($data['response']['id']) && $data['response']['id'] == $id) {
                $class_name = get_called_class();
                $obj = new $class_name;
                $obj->setAttributes($data['response']);
                $obj->to_put_attributes = [];
                return $obj;
            }
            if (isset($data['response'][0]) && isset($data['response'][0]['id']) && $data['response'][0]['id'] == $id) {
                $class_name = get_called_class();
                $obj = new $class_name;
                $obj->setAttributes($data['response'][0]);
                $obj->to_put_attributes = [];
                return $obj;
            }
        }
        return false;
    }


    /**
     * Get Model in Array format
     *
     * @return array
     */
    public function toArray()
    {
        return array_merge($this->attributes, ['id' => $this->id]);
    }

    /**
     * Create Model
     *
     * @return bool|object Recursive Model
     */
    public function create($endpoint = false)
    {
        $data = $this->validate()->attributes;
        Rebill::log('Create '.$endpoint.': '.\var_export($this->to_put_attributes, true).\var_export($data, true));
        foreach (\array_keys($data) as $key) {
            if (!\in_array($key, $this->to_put_attributes) || \in_array($key, $this->ignore, true)) {
                unset($data[$key]);
            }
        }
        Rebill::log('Create '.$endpoint.' filtered: '.\var_export($data, true));
        if (count($data) == 0) {
            return false;
        }
        $result = Rebill::getInstance()->callApiPost($endpoint ? $endpoint : static::$endpoint, $data);
        if ($result && $result['success']) {
            if (isset($result['response'])) {
                $this->setAttributes($result['response']);
            }
            $this->to_put_attributes = [];
            return $this;
        }
        return false;
    }

    /**
     * Update Model
     *
     * @return bool|object Recursive Model
     */
    public function update($endpoint = false)
    {
        $data = $this->validate()->attributes;
        Rebill::log('Update '.$endpoint.': '.\var_export($this->id, true).\var_export($this->to_put_attributes, true).\var_export($data, true));
        if (!$this->id) {
            throw new \Exception("The attribute 'id' is required.");
        }
        foreach (\array_keys($data) as $key) {
            if (!\in_array($key, $this->to_put_attributes) || \in_array($key, $this->ignore, true)) {
                unset($data[$key]);
            }
        }
        Rebill::log('Update '.$endpoint.' filtered: '.\var_export($this->id, true).\var_export($this->to_put_attributes, true).\var_export($data, true));
        if (count($data) == 0) {
            return $this;
        }
        $data['id'] = $this->id;
        $result = Rebill::getInstance()->callApiPut($endpoint ? $endpoint : static::$endpoint, $data);
        if ($result && $result['success']) {
            if (isset($result['response'])) {
                $this->setAttributes($result['response']);
            }
            $this->to_put_attributes = [];
            return $this;
        }
        return false;
    }

    /**
     * Delete Model
     *
     * @return bool
     */
    public function delete($endpoint = false)
    {
        if (!$this->id) {
            throw new \Exception("The attribute 'id' is required.");
        }
        $result = Rebill::getInstance()->callApiDelete($endpoint ? $endpoint : (static::$endpoint.'/'.$this->id));
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
