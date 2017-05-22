<?php
namespace litepubl\core\data;

trait PropsTrait
{
    public function __get($name)
    {
        if (method_exists($this, $get = 'get' . $name)) {
            return $this->$get();
        } elseif (array_key_exists($name, $this->data)) {
            return $this->data[$name];
        } else {
            return $this->getProp($name);
        }
    }

    protected function getProp(string $name)
    {
            throw new PropException(get_class($this), $name);
    }

    public function __set($name, $value)
    {
        if (method_exists($this, $set = 'set' . $name)) {
            $this->$set($value);
        } elseif (array_key_exists($name, $this->data)) {
            $this->data[$name] = $value;
        } else {
            $this->setProp($name, $value);
        }
    }

    protected function setProp(string $name, $value)
    {
            throw new PropException(get_class($this), $name);
    }

    public function __isset($name)
    {
        return array_key_exists($name, $this->data) || method_exists($this, "get$name");
    }

    public function __call($name, $params)
    {
            throw new \UnexpectedValueException(sprintf('Call unknown method %s in %s', $name, get_class($this)));
    }

    public function methodExists(string $name)
    {
        return false;
    }
}
