<?php

namespace Waavi\ValueObjects;

use Illuminate\Contracts\Support\Jsonable;
use Illuminate\Support\Str;
use ReflectionClass;

class ValueObject implements Jsonable
{
    protected $attributes = [];

    public function __construct($values)
    {
        if (is_array($values)) {
            $this->attributes = $values;
        } else {
            $name                    = Str::snake((new ReflectionClass(get_class($this)))->getShortName());
            $this->attributes[$name] = $values;
        }
    }

    public function toJson($options = 0)
    {
        if (sizeof($this->attributes) > 1) {
            return json_encode($this->attributes, $options);
        } else {
            //var_dump($this->attributes);
            return current($this->attributes) ?: '';
        }
    }

    public function __toString()
    {
        return $this->toJson();
    }
}
