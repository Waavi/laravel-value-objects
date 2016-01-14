<?php

namespace Waavi\ValueObjects\Test\Models;

use Waavi\ValueObjects\ValueObject;

class Address extends ValueObject
{
    protected $fillable = ['street', 'city', 'country'];

    public function isValid()
    {
        return $this->street && $this->city && $this->country;
    }

    public function setCountryAttribute($value)
    {
        $this->attributes['country'] = trim($value);
    }

    public function getFormattedAttribute()
    {
        return "$this->street, $this->city, $this->country";
    }
}
