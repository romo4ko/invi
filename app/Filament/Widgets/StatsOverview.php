<?php

namespace App\Filament\Widgets;

use App\Models\Invite;
use App\Models\User;
use Carbon\Carbon;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\DB;

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
        ];
    }
}
