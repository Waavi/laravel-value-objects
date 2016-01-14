<?php

namespace Waavi\ValueObjects\Test\Models;

use Illuminate\Database\Eloquent\Model;
use Waavi\ValueObjects\Traits\CastsValueObjects;

class Account extends Model
{
    use CastsValueObjects;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'accounts';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'email', 'address'];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'email'   => Email::class,
        'address' => Address::class,
    ];
}
