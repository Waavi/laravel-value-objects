<?php

namespace Waavi\ValueObjects\Test\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Waavi\ValueObjects\Traits\CastsValueObjects;

class Person extends Model
{
    use CastsValueObjects;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['first_name', 'last_name', 'email', 'address'];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'email'   => Email::class,
    ];

    public function posts() : BelongsToMany
    {
        return $this->belongsToMany(Post::class);
    }

    public function comments() : BelongsToMany
    {
        return $this->belongsToMany(Comment::class);
    }
}
