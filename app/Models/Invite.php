<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

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

    protected static function booted(): void
    {
        static::creating(function (Invite $invite): void {
            if (!empty($invite->slug)) {
                return;
            }

            $invite->slug = static::generateUniqueSlug($invite->guest);
        });
    }

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

    protected static function generateUniqueSlug(?Guest $guest): string
    {
        $baseSlug = $guest && !empty($guest->surname)
            ? Str::slug($guest->surname)
            : Str::random(6);

        $slug = $baseSlug;

        while (static::query()->where('slug', $slug)->exists()) {
            $slug = $baseSlug . '-' . Str::random(6);
        }

        return $slug;
    }
}
