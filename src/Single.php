<?php

namespace Waavi\ValueObjects;

use Illuminate\Support\Str;

class Single extends ValueObject
{
    public function __construct($value)
    {
        $this->setAttribute('value', $value);
    }

    /**
     *  Return the Json representation of this value object
     *
     *  @param  mixed   $options
     *  @return string
     */
    public function toJson($options = 0)
    {
        return array_get($this->attributes, 'value', null);
    }
}
