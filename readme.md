# Value Objects for Laravel 5

[![Latest Version on Packagist](https://img.shields.io/packagist/v/waavi/laravel-value-objects.svg?style=flat-square)](https://packagist.org/packages/waavi/laravel-value-objects)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Build Status](https://img.shields.io/travis/Waavi/laravel-value-objects/master.svg?style=flat-square)](https://travis-ci.org/Waavi/laravel-value-objects)
[![Total Downloads](https://img.shields.io/packagist/dt/waavi/laravel-value-objects.svg?style=flat-square)](https://packagist.org/packages/waavi/laravel-value-objects)

Allows you to create value objects related to Eloquent Models that are then saved into the database as either plain strings or json encoded.

WAAVI is a web development studio based in Madrid, Spain. You can learn more about us at [waavi.com](http://waavi.com)

## Installation

You may install the package via composer

    composer require waavi/laravel-value-objects 1.0.x

## Usage

### Using single field value objects

Say you wish to add an Email field to your Customer Model as a Value Object. This would be useful in order to validate the field, get some properties related to this field and keep your code as clean as possible.

Your Customer Model must use the 'CastsValueObjects' trait, and in the casts array you must add a reference to your Value Object:

```php
    class Customer extends Model
    {
        use \Waavi\ValueObjects\Traits\CastsValueObjects;

        /**
         * The attributes that should be casted to native types.
         *
         * @var array
         */
        protected $casts = [
            'email'   => Email::class,
        ];
    }
```

You may now create an Email class extending from 'ValueObject':

```php
    use Waavi\ValueObjects\Single;

    class Email extends Single
    {
        protected $fillable = ['email'];

        public function domain()
        {
            return substr($this->email, strrpos($this->email, '@') + 1);
        }
    }
```

Properties of the single variable value object:

```php
    $customer->email = new Email('info@waavi.com'); // Set the customer's email
    $customer->email = 'info@waavi.com';            // This also works
    echo $customer->email;              // Prints 'info@waavi.com'
    echo $customer->email->domain();    // Prints 'waavi.com'
```

The email field will be saved as a string to the database.

### Using multiple fields value objects

Say you have both a Customer and a Store models that both have addresses. In order to work with theses addresses, you would have to replicate methods and attributes among both, which could become cumbersome. In order to simplify this, we can use a common Address Value Object.

Your Customer Model must use the 'CastsValueObjects' trait, and in the casts array you must add a reference to your Value Object:

```php
    class Customer extends Model
    {
        use \Waavi\ValueObjects\Traits\CastsValueObjects;

        /**
         * The attributes that should be casted to native types.
         *
         * @var array
         */
        protected $casts = [
            'address'   => Address::class,
        ];
    }
}
```

You may now create an Address class extending from 'ValueObject':

```php
    use use Waavi\ValueObjects\Json;

    class Address extends Json
    {
        protected $fillable = ['street', 'street_number', 'city', 'zip_code', 'country'];

        public function getFormatted()
        {
            return "$this->street, $this->street_number, $this->city, $this->zip_code, $this->country";
        }
    }
```

Properties of the multiple variable value object:

```php
    $customer->address = new Address(['street' => '...']);  // Set the customer's address
    echo $customer->address;                    // Prints json representation
    echo $customer->address->getFormatted();    // Prints the response from getFormatted
```

The address field will be saved as a json encoded string to the database.