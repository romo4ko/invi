<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invite extends Model
{
    protected $fillable = [
        'event_id',
        'guest_id',
        'approval',
        'plus_one',
        'slug',
    ];

    protected $casts = [
        'approval' => 'boolean',
        'plus_one' => 'boolean',
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function guest()
    {
        return $this->belongsTo(Guest::class);
    }

    public function getUrlAttribute(): string
    {
        return route('invite.show', ['slug' => $this->slug]);
    }
}
