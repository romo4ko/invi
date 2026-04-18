<?php

namespace App\Filament\Widgets;

use App\Models\Invite;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make(
                'Приглашено гостей',
                Invite::query()->count()
            ),
            Stat::make(
                'Подтвердили участие',
                Invite::query()->where('approval')->count()
            ),
            Stat::make(
                'Подтвердили с +1',
                Invite::query()->where('approval', true)->where('plus_one', true)->count()
            ),
        ];
    }
}
