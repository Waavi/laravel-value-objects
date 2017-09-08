<?php

namespace Waavi\ValueObjects\Test\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Waavi\ValueObjects\Traits\CastsValueObjects;

class Post extends Model
{
    use CastsValueObjects;

    protected $with = [
        'author',
        'comments',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['title', 'text'];


    public function author() : HasOne
    {
        return $this->hasOne(Person::class);
    }

    public function blog() : BelongsTo
    {
        return $this->belongsTo(Blog::class);
    }

    public function comments() : HasMany
    {
        return $this->hasMany(Comment::class);
    }
}
