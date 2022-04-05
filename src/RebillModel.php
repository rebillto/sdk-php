<?php namespace Rebill\SDK;

/**
 *  Model class
 *
 * @author Kijam
 */
abstract class RebillModel extends \ArrayObject
{
    function __constructor($attributes = null)
    {
        if (is_array($attributes)) {
            $this->setAttributes($attributes);
        }
    }
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
    public function setProp($values)
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
     *      (new Rebill\SDK\Models\CurrentModel)->setAttributes([ 'user' => 'rebill', 'pass' => '123' ]);
     *
     * @param array $values All values.
     *
     * @return object Return recursive model.
     */
    public function setAttributes($values)
    {
        if (is_array($values)) {
            foreach ($values as $key => $value) {
                if (\is_string($key)) {
                    $this->__set($key, $value);
                } else {
                    throw new \Exception(get_called_class().": The attribute '".var_export($key, true)."' not is string.");
                }
            }
        } else {
            throw new \Exception(get_called_class().": The value '".var_export($values, true)."' not is array.");
        }
        return $this->format();
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
            || \property_exists($this, 'attributes') && !$this->offsetExists($key)
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
                return $this->offsetExists($key) ? $this->offsetGet($key) : null;
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
        if (!in_array($name, $this->attributes, true)) {
            //throw new \Exception("The attribute '$name' is invalid.");
            return;
        }
        $this->offsetSet($name, $value);
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
    public function &__get($name)
    {
        if (!in_array($name, $this->attributes, true)) {
            throw new \Exception("The attribute '$name' is invalid.");
        }
        if ($this->offsetExists($name)) {
            return $this[$name];
        }
        return null;
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
        return $this->offsetExists($name);
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
        if ($this->offsetExists($name)) {
            $this->offsetUnset($name);
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
                if (!$this->offsetExists($key) || !$this->offsetGet($key)) {
                    \Rebill\SDK\Rebill::log("The attribute '$key' is required ".var_export($this, true));
                    throw new \Exception("The attribute '$key' is required.");
                }
            }
        }
        if (\property_exists($this, 'format') && \count($this->format)) {
            foreach ($this->format as $key => $validators) {
                if ($this->offsetExists($key) && ($data = $this->offsetGet($key))) {
                    foreach ($validators as $validator) {
                        if (\method_exists($this, $validator)) {
                            if (!static::$validator($data)) {
                                \Rebill\SDK\Rebill::log("Validation method $validator for '$key' return false");
                                throw new \Exception("Validation method $validator for '$key' return false");
                            }
                        } elseif (\function_exists($validator)) {
                            if (!$validator($data)) {
                                \Rebill\SDK\Rebill::log("Validation funtion $validator for '$key' return false");
                                throw new \Exception("Validation funtion $validator for '$key' return false");
                            }
                        } else {
                            \Rebill\SDK\Rebill::log("Validation funtion $validator for '$key' not founds");
                            throw new \Exception("Validation funtion $validator for '$key' not found");
                        }
                    }
                }
            }
        }
        return $this;
    }

    /**
     * Get element of this Model by ID
     *
     * @param string $id Model ID.
     * @param string $endpoint Endpoint.
     *
     * @return mixed|bool
     */
    public static function get($id = false, $endpoint = false)
    {
        Rebill::log('get: '.$endpoint);
        $class_name = get_called_class();
        $obj = new $class_name;
        if (\property_exists($obj, 'is_guest') && $obj->is_guest) {
            $error_dummy = null;
            $data = Rebill::getInstance()->callApiGet(
                ($endpoint ? $endpoint : static::$endpoint).($id ? '/'.$id : ''),
                false,
                [],
                $error_dummy,
                true
            );
        } else {
            $data = Rebill::getInstance()->callApiGet(($endpoint ? $endpoint : static::$endpoint).($id ? '/'.$id : ''));
        }
        Rebill::log('get data: - '.\var_export($data, true));
        if ($data) {
            $obj->setAttributes($data);
            $obj->to_put_attributes = [];
            return $obj;
        }
        unset($obj);
        return false;
    }

    /**
     * Get Model in Array format
     *
     * @return array
     */
    public function toArray()
    {
        $values = [];
        foreach ($this as $name => $var) {
            if (in_array($name, $this->attributes, true)) {
                $values[$name] = is_object($var) ? $var->toArray() : $var;
            }
        }
        return $values;
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
     * Get Model in Json format
     *
     * @return string
     */
    public function __toString()
    {
        return json_encode($this->toArray());
    }

    /**
     * Create Model
     *
     * @param string $endpoint Endpoint.
     *
     * @return bool|object Recursive Model
     */
    public function create($endpoint = false)
    {
        $data = $this->validate()->toArray();
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
        
        if (\property_exists($this, 'is_guest') && $this->is_guest) {
            $error_dummy = null;
            $result = Rebill::getInstance()->callApiPost(
                $endpoint ? $endpoint : static::$endpoint,
                $data,
                false,
                [],
                $error_dummy,
                true
            );
        } else {
            $result = Rebill::getInstance()->callApiPost($endpoint ? $endpoint : static::$endpoint, $data);
        }
        if ($result) {
            $this->to_put_attributes = [];
            if (\property_exists($this, 'responseClass')) {
                $class_name = $this->responseClass;
                $response = new $class_name;
                $response->setAttributes($result);
                return $response;
            } else {
                $this->setAttributes($result);
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
    public function update($endpoint = false)
    {
        $data = $this->validate()->toArray();
        Rebill::log('Update '.$endpoint.': '.\var_export($this->to_put_attributes, true).\var_export($data, true));
        foreach (\array_keys($data) as $key) {
            if (!\in_array($key, $this->to_put_attributes) || \in_array($key, $this->ignore, true)) {
                unset($data[$key]);
            }
        }
        Rebill::log('Update '.$endpoint.' filtered: '.\var_export($this->to_put_attributes, true).\var_export($data, true));
        if (count($data) == 0) {
            return $this;
        }
        if (\property_exists($this, 'is_guest') && $this->is_guest) {
            $error_dummy = null;
            $result = Rebill::getInstance()->callApiPut(
                $endpoint ? $endpoint : static::$endpoint,
                $data,
                false,
                [],
                $error_dummy,
                true
            );
        } else {
            $result = Rebill::getInstance()->callApiPut($endpoint ? $endpoint : static::$endpoint, $data);
        }
        if ($result) {
            $this->to_put_attributes = [];
            if (\property_exists($this, 'responseClass')) {
                $class_name = $this->responseClass;
                $response = new $class_name;
                $response->setAttributes($result);
                return $response;
            } else {
                $this->setAttributes($result);
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
    public function delete($endpoint = false)
    {
        $id = '';
        if (isset($this->id) && $this->id) {
            $id = '/'.$this->id;
        }
        if (\property_exists($this, 'is_guest') && $this->is_guest) {
            $error_dummy = null;
            $result = Rebill::getInstance()->callApiDelete($endpoint ? $endpoint : (static::$endpoint.$id), false, [], $error_dummy, true);
        } else {
            $result = Rebill::getInstance()->callApiDelete($endpoint ? $endpoint : (static::$endpoint.$id));
        }
        return $result;
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
