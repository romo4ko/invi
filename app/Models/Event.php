<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Event extends Model
{
    protected $fillable = [
        'name',
        'content',
        'location',
        'datetime',
        'caption',
        'image',
        'color',
    ];

    protected $casts = [
        'datetime' => 'datetime',
    ];

    public function invites(): HasMany
    {
        return $this->hasMany(Invite::class);
    }

    public function getImageUrlAttribute()
    {
        return $this->image ? asset('storage/' . $this->image) : null;
    }
}
