<?php

namespace Waavi\ValueObjects;

class Json extends ValueObject
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [];

    public function __construct(array $attributes)
    {
        $this->fill($attributes);
    }

    /**
     * Fill the model with an array of attributes.
     *
     * @param  array  $attributes
     * @return $this
     *
     * @throws \Illuminate\Database\Eloquent\MassAssignmentException
     */
    public function fill(array $attributes)
    {
        foreach ($attributes as $key => $value) {
            if (in_array($key, $this->fillable)) {
                $this->setAttribute($key, $value);
            }
        }
    }

    /**
     *  Return the Json representation of this value object
     *
     *  @param  mixed   $options
     *  @return string
     */
    public function toJson($options = 0)
    {
        return json_encode($this->attributes, $options);
    }
}
