<?php

namespace Waavi\ValueObjects\Test\Models;

use Waavi\ValueObjects\ValueObject;

class Address extends ValueObject
{
    public function isValid()
    {
        return $this->street && $this->city && $this->country;
    }
}
