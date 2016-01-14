<?php

namespace Waavi\ValueObjects\Test\Models;

use Waavi\ValueObjects\Json;

class Address extends Json
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
