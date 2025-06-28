<?php

namespace App\Filament\Resources;

use App\Filament\Resources\InviteResource\Pages;
use App\Filament\Resources\InviteResource\RelationManagers;
use App\Models\Invite;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class InviteResource extends Resource
{
    protected static ?string $model = Invite::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';

    protected static ?int $navigationSort = 4;

    protected static ?string $navigationLabel = 'Приглашения';

    protected static ?string $modeLabel = 'Приглашения';

    protected static ?string $pluralModelLabel = 'Приглашения';

    protected static ?string $breadcrumb = 'Приглашения';

    protected static ?string $label = 'Приглашение';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('guest_id')
                    ->label('Гость')
                    ->relationship('guest', 'name')
                    ->required()
                    ->searchable()
                    ->disabled(fn (Invite $invite): bool => $invite->guest_id !== null)
                    ->preload(),
                Forms\Components\Select::make('event_id')
                    ->label('Мероприятие')
                    ->relationship('event', 'name')
                    ->required()
                    ->searchable()
                    ->disabled(fn (Invite $invite): bool => $invite->event_id !== null)
                    ->preload(),
                Forms\Components\TextInput::make('slug')
                    ->label('Ссылка')
                    ->unique(Invite::class, 'slug', ignoreRecord: true)
                    ->disabled(fn (Invite $invite): bool => $invite->slug !== null)
                    ->default(fn (Invite $invite): string => $invite->slug ?? ''),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('guest.name')
                    ->label('Гость')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('event.name')
                    ->label('Мероприятие')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('approval')
                    ->label('Статус')
                    ->formatStateUsing(
                        function (Invite $invite): string {
                            if (is_null($invite->approval)) {
                                return 'Не подтверждено';
                            }
                            return $invite->approval ? 'Подтверждено' : 'Отклонено';
                        }
                    )
                    ->default('Не подтверждено')
                    ->badge()
                    ->color(function (Invite $invite): string {
                        if (is_null($invite->approval)) {
                            return 'gray';
                        }
                        return $invite->approval ? 'success' : 'danger';
                    }),
                Tables\Columns\TextColumn::make('slug')
                    ->formatStateUsing(
                        fn (Invite $invite): string => $invite->url
                    )
                    ->label('Ссылка')
                    ->copyable()
                    ->copyableState(
                        fn (Invite $invite): string => $invite->url
                    ),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('approval')
                    ->options([
                        '1' => 'Подтверждено',
                        '0' => 'Отклонено',
                    ])
                    ->label('Статус'),
            ])
            ->actions([
                Tables\Actions\Action::make('view')
                    ->label('Перейти')
                    ->url(fn (Invite $invite): string => $invite->url)
                    ->icon('heroicon-o-eye')
                    ->openUrlInNewTab()
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListInvites::route('/'),
            'create' => Pages\CreateInvite::route('/create'),
        ];
    }
}
