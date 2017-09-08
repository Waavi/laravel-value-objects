<?php

namespace Waavi\ValueObjects\Test\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Blog extends Model
{
    protected $with = [
        'owner',
        'posts',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['title'];

    public function owner() : BelongsTo
    {
        return $this->belongsTo(Person::class);
    }

    public function posts() : HasMany
    {
        return $this->hasMany(Post::class);
    }
}
