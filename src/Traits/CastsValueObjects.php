<?php

namespace Waavi\ValueObjects\Traits;

trait CastsValueObjects
{
    /**
     * Cast an attribute to a native PHP type.
     *
     * @param  string  $key
     * @param  mixed  $value
     * @return mixed
     */
    protected function castAttribute($key, $value)
    {
        if (!class_exists($this->casts[$key])) {
            return parent::castAttribute($key, $value);
        }

        return new $this->casts[$key]($this->fromJson($value));
    }

    /**
     * Decode the given JSON back into an array or object.
     *
     * @param  string  $value
     * @param  bool  $asObject
     * @return mixed
     */
    public function fromJson($value, $asObject = false)
    {
        return json_decode($value, !$asObject) ?: $value;
    }

    /**
     * Encode the given value as JSON.
     *
     * @param  mixed  $value
     * @return string
     */
    protected function asJson($value)
    {
        if (!$this->isValueObject($value)) {
            return parent::asJson($value);
        }
        return $value->toJson();
    }

    /**
     *  Check if the given value is a ValueObject
     *
     *  @param  mixed $value
     *  @return boolean
     */
    protected function isValueObject($value)
    {
        return is_object($value) && is_subclass_of($value, ValueObject::class);
    }
}
