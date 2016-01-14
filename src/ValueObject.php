<?php

namespace Waavi\ValueObjects;

use Illuminate\Contracts\Support\Jsonable;
use Illuminate\Support\Str;
use ReflectionClass;

class ValueObject implements Jsonable
{
    use CastsValueObjects;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [];

    /**
     * The value object's attributes.
     *
     * @var array
     */
    protected $attributes = [];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [];

    public function __construct($values)
    {
        if (is_array($values)) {
            $this->fill($values);
        } else {
            $key = Str::snake((new ReflectionClass(get_class($this)))->getShortName());
            $this->setAttribute($key, $values);
        }
    }

    public function fill($attributes)
    {
        foreach ($attributes as $key => $value) {
            if (in_array($key, $this->fillable)) {
                $this->setAttribute($key, $value);
            }
        }
    }

    /**
     * Set a given attribute on the model.
     *
     * @param  string  $key
     * @param  mixed  $value
     * @return $this
     */
    public function setAttribute($key, $value)
    {
        if ($this->hasSetMutator($key)) {
            $method = 'set' . Str::studly($key) . 'Attribute';
            return $this->{$method}($value);
        }
        $this->attributes[$key] = $value;
        return $this;
    }

    /**
     * Determine if a set mutator exists for an attribute.
     *
     * @param  string  $key
     * @return bool
     */
    public function hasSetMutator($key)
    {
        return method_exists($this, 'set' . Str::studly($key) . 'Attribute');
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

    /**
     * Get a plain attribute (not a relationship).
     *
     * @param  string  $key
     * @return mixed
     */
    public function getAttribute($key)
    {
        $value = array_get($this->attributes, $key, null);
        if ($this->hasGetMutator($key)) {
            return $this->mutateAttribute($key, $value);
        }
        return $value;
    }

    /**
     * Get the value of an attribute using its mutator.
     *
     * @param  string  $key
     * @param  mixed  $value
     * @return mixed
     */
    protected function mutateAttribute($key, $value)
    {
        return $this->{'get' . Str::studly($key) . 'Attribute'}($value);
    }

    /**
     * Determine if a get mutator exists for an attribute.
     *
     * @param  string  $key
     * @return bool
     */
    public function hasGetMutator($key)
    {
        return method_exists($this, 'get' . Str::studly($key) . 'Attribute');
    }

    /**
     * Dynamically retrieve attributes on the model.
     *
     * @param  string  $key
     * @return mixed
     */
    public function __get($key)
    {
        return $this->getAttribute($key);
    }

    /**
     * Dynamically set attributes on the model.
     *
     * @param  string  $key
     * @param  mixed  $value
     * @return void
     */
    public function __set($key, $value)
    {
        $this->setAttribute($key, $value);
    }

    public function __toString()
    {
        return $this->toJson();
    }
}
