<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $fillable = [
        'name',
        'content',
        'location',
        'datetime',
        'caption',
        'image',
    ];

    protected $casts = [
        'datetime' => 'datetime',
    ];

    public function getImageUrlAttribute()
    {
        return $this->image ? asset('storage/' . $this->image) : null;
    }
}
