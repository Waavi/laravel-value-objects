# Value Objects for Laravel 5

[![Latest Version on Packagist](https://img.shields.io/packagist/v/waavi/laravel-value-objects.svg?style=flat-square)](https://packagist.org/packages/waavi/laravel-value-objects)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Build Status](https://img.shields.io/travis/Waavi/laravel-value-objects/master.svg?style=flat-square)](https://travis-ci.org/Waavi/laravel-value-objects)
[![Total Downloads](https://img.shields.io/packagist/dt/waavi/laravel-value-objects.svg?style=flat-square)](https://packagist.org/packages/waavi/laravel-value-objects)

Allows you to create value objects in Eloquent Models that are then saved into the database as either plain strings or json encoded.

WAAVI is a web development studio based in Madrid, Spain. You can learn more about us at [waavi.com](http://waavi.com)

## Installation

You may install the package via composer

    composer require waavi/laravel-value-objects 1.0.x

## Usage

### Using single field value objects

Say you wish to add an Temperature field to a Device eloquent model. You may wish to apply transformations to this field, or get the value in Celsius, Fahrenheit or Kelvin. If you use this type of field in several models, it might become cumbersome to copy paste these functions between all models.

Here is were value objects become very useful. Let's see how. First we created the Device model, which will have a temperature field cast to the Temperature value object and will use the 'CastsValueObjects' trait.

```php
    use Illuminate\Database\Eloquent\Model;

    class Device extends Model
    {
        use \Waavi\ValueObjects\Traits\CastsValueObjects;

        /**
         * The attributes that should be casted to native types.
         *
         * @var array
         */
        protected $casts = [
            'temperature'   => Temperature::class,
        ];
    }
```

The Temperature value object will extend from 'Waavi\ValueObjects\Single' and could be defined as follows:

```php
    use Waavi\ValueObjects\Single;

    class Temperature extends Single
    {
        /**
         * @return float
         */
        public function inCelsius()
        {
            // In single field value objects, the name of the field is always $value
            return (float) $this->value;
        }

        /**
         * @return float
         */
        public function inKelvin()
        {
            return (float) $this->value + 273.15;
        }

        /**
         * @return float
         */
        public function inFahrenheit()
        {
            return (float) $this->value * 1.8 + 32;
        }
    }
```

Single field value objects are saved as plain string in the database, and can be uses as follows:

```php
    $device = new Device;
    $device->temperature = new Temperature(30);
    $device->temperature = 30;          // This also works
    echo $device->temperature;          // Prints '30'
    echo $device->temperature->value;         // Prints '30'
    echo $device->temperature->inKelvin();    // Prints 303.15
```

You may also use Accessors and Mutators, just like in Eloquent Models. For example you could rewrite the Temperature class as:

```php
    use Waavi\ValueObjects\Single;

    class Temperature extends Single
    {
        /**
         *  Make all subzero temperatures equal to zero.
         *  @return void
         */
        public function setValueAttribute($value)
        {
            $this->attributes['value'] = $value > 0 ? $value : 0;
        }

        /**
         * @return float
         */
        public function getCelsiusAttribute()
        {
            return (float) $this->value;
        }

        /**
         * @return float
         */
        public function getKelvinAttribute()
        {
            return (float) $this->value + 273.15;
        }

        /**
         * @return float
         */
        public function getFahrenheitAttribute()
        {
            return (float) $this->value * 1.8 + 32;
        }
    }
```

You could then access the temperature properties as follows:

```php
    $device = new Device;
    $device->temperature = 30;
    echo $device->temperature;            // Prints '30'
    echo $device->temperature->value;     // Prints '30'
    echo $device->temperature->kelvin;    // Prints 303.15
```

### Using multiple fields value objects

Sometimes are value objects might not be so simple, and might require several fields instead of just one. Let's say we have a Trip model with origin and destination fields made up of coordinates. We could then define the Trip model like we did before:

```php
    use Illuminate\Database\Eloquent\Model;

    class Trip extends Model
    {
        use \Waavi\ValueObjects\Traits\CastsValueObjects;

        /**
         * The attributes that should be casted to native types.
         *
         * @var array
         */
        protected $casts = [
            'origin'        => Coordinate::class,
            'destination'   => Coordinate::class,
        ];
    }
```

We would then be able to define the Coordinate value object like:

```php
    use Waavi\ValueObjects\Json;

    class Coordinate extends Json
    {
        protected $fillable = ['lat', 'lng'];

        /**
         *  Return the distance between this coordinate and another
         *
         *  @return float
         */
        public function distanceTo(Coordinate $coord) {
            /** Calculate distance **/
            return $distance;
        }
    }
```

Now we need to extend from the 'Waavi\ValueObjects\Json' Value Object, since we will need several fields to represent our Value Object, and we will need to define a fillable array with the names of the fields that are allowed in the value object. This time, the field will be json encoded before being saved.

As before, mutators and accessors are available.

We can work with this model object like so:

```php
    $trip = new Trip;
    $trip->origin = new Coordinate(['lat' => 20.1221, 'lng' => 12.1231]);
    $trip->destination = new Coordinate(['lat' => 10.13, 'lng' => 12.14]);
    echo $trip->origin;                     // Prints json representation {'lat':'20.1221','lng':'12.1231'}
    echo $trip->origin->lat;              // Prints '20.1221'
    echo $trip->origin->distanceTo($trip->destination);      // Prints distance between them.
```