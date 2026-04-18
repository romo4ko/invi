<?php

namespace App\Filament\Resources\EventResource\RelationManagers;

use App\Filament\Resources\InviteResource;
use App\Models\Guest;
use App\Models\Invite;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class InvitesRelationManager extends RelationManager
{
    protected static string $relationship = 'invites';

    protected static ?string $title = 'Приглашённые гости';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('guest_id')
                    ->label('Гость')
                    ->options(fn (): array => Guest::query()
                        ->whereNotIn('id', $this->getOwnerRecord()->invites()->pluck('guest_id'))
                        ->orderBy('surname')
                        ->orderBy('name')
                        ->get()
                        ->mapWithKeys(fn (Guest $guest): array => [$guest->id => $guest->full_name])
                        ->all()
                    )
                    ->required()
                    ->searchable()
                    ->preload(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('slug')
            ->columns([
                Tables\Columns\TextColumn::make('guest.full_name')
                    ->label('Гость')
                    ->searchable(query: function ($query, string $search) {
                        return $query->whereHas('guest', function ($guestQuery) use ($search) {
                            $guestQuery
                                ->where('name', 'like', "%{$search}%")
                                ->orWhere('surname', 'like', "%{$search}%")
                                ->orWhere('patronymic', 'like', "%{$search}%");
                        });
                    })
                    ->sortable(query: function ($query, string $direction) {
                        return $query->join('guests', 'invites.guest_id', '=', 'guests.id')
                            ->orderBy('guests.surname', $direction)
                            ->orderBy('guests.name', $direction)
                            ->select('invites.*');
                    }),
                Tables\Columns\TextColumn::make('approval')
                    ->label('Статус')
                    ->formatStateUsing(function (Invite $invite): string {
                        if (is_null($invite->approval)) {
                            return 'Без ответа';
                        }

                        return $invite->approval ? 'Придёт' : 'Не придёт';
                    })
                    ->badge()
                    ->color(function (Invite $invite): string {
                        if (is_null($invite->approval)) {
                            return 'gray';
                        }

                        return $invite->approval ? 'success' : 'danger';
                    }),
                Tables\Columns\IconColumn::make('plus_one')
                    ->label('+1')
                    ->boolean(),
                Tables\Columns\ToggleColumn::make('sent')
                    ->label('Отправлено'),
                Tables\Columns\TextColumn::make('slug')
                    ->label('Ссылка')
                    ->formatStateUsing(fn (Invite $record): string => $record->url)
                    ->copyable()
                    ->copyableState(fn (Invite $record): string => $record->url)
                    ->toggleable(),
            ])
            ->headerActions([
                Tables\Actions\Action::make('inviteGuests')
                    ->label('Пригласить гостей')
                    ->icon('heroicon-o-user-plus')
                    ->form([
                        Forms\Components\Select::make('guest_ids')
                            ->label('Гости')
                            ->multiple()
                            ->required()
                            ->searchable()
                            ->preload()
                            ->options(fn (InvitesRelationManager $livewire): array => Guest::query()
                                ->whereNotIn('id', $livewire->getOwnerRecord()->invites()->pluck('guest_id'))
                                ->orderBy('surname')
                                ->orderBy('name')
                                ->get()
                                ->mapWithKeys(fn (Guest $guest): array => [$guest->id => $guest->full_name])
                                ->all()
                            ),
                    ])
                    ->action(function (array $data): void {
                        foreach ($data['guest_ids'] as $guestId) {
                            $this->getOwnerRecord()->invites()->firstOrCreate([
                                'guest_id' => $guestId,
                            ]);
                        }
                    }),
                Tables\Actions\CreateAction::make()
                    ->label('Пригласить одного')
                    ->mutateFormDataUsing(function (array $data): array {
                        $data['event_id'] = $this->getOwnerRecord()->getKey();

                        return $data;
                    }),
            ])
            ->actions([
                Tables\Actions\Action::make('view')
                    ->label('Открыть')
                    ->url(fn (Invite $record): string => $record->url)
                    ->openUrlInNewTab()
                    ->icon('heroicon-o-eye'),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->recordUrl(fn (Invite $record): string => InviteResource::getUrl('edit', ['record' => $record]));
    }
}
