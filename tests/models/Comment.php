<?php

namespace Waavi\ValueObjects\Test\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Waavi\ValueObjects\Traits\CastsValueObjects;

class Comment extends Model
{
    use CastsValueObjects;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'comments';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['text', 'date', 'author'];

    protected $dates = [
        'date'
    ];

    public function post() : BelongsTo
    {
        return $this->belongsTo(Post::class);
    }

    public function author() : HasOne
    {
        return $this->hasOne(Person::class);
    }
}
