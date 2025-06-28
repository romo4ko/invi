<?php

namespace App\Filament\Resources\InviteResource\Pages;

use App\Filament\Resources\InviteResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Str;

class CreateInvite extends CreateRecord
{
    protected static string $resource = InviteResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        if (empty($data['slug'])) {
            $guest = \App\Models\Guest::query()->find($data['guest_id']);
            $slug = $guest ? Str::slug($guest->surname) : null;

            if (!$slug) {
                $data['slug'] = Str::random(6);
            } else {
                $invite = \App\Models\Invite::query()->where('slug', $slug)->first();

                if ($invite) {
                    $data['slug'] = $slug . '-' . Str::random(6);
                } else {
                    $data['slug'] = $slug;
                }
            }
        }

        return $data;
    }
}
