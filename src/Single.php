<?php

namespace Waavi\ValueObjects;

use Illuminate\Support\Str;
use ReflectionClass;

class Single extends ValueObject
{
    public function __construct($value)
    {
        $key = Str::snake((new ReflectionClass(get_class($this)))->getShortName());
        $this->setAttribute($key, $value);
    }
}
