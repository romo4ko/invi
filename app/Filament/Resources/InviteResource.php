<?php

namespace App\Filament\Resources;

use App\Filament\Resources\InviteResource\Pages;
use App\Models\Guest;
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

    protected static bool $shouldRegisterNavigation = false;

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
                Forms\Components\Section::make('Основное')
                    ->schema([
                        Forms\Components\Select::make('guest_id')
                            ->label('Гость')
                            ->relationship('guest', 'name')
                            ->getOptionLabelFromRecordUsing(fn (Guest $record): string => $record->full_name)
                            ->required()
                            ->searchable()
                            ->preload()
                            ->disabledOn('edit'),
                        Forms\Components\Select::make('event_id')
                            ->label('Мероприятие')
                            ->relationship('event', 'name')
                            ->required()
                            ->searchable()
                            ->preload()
                            ->disabledOn('edit'),
                        Forms\Components\TextInput::make('slug')
                            ->label('Slug')
                            ->unique(Invite::class, 'slug', ignoreRecord: true)
                            ->disabledOn('edit')
                            ->default(fn (Invite $invite): string => $invite->slug ?? ''),
                        Forms\Components\Toggle::make('plus_one')
                            ->label('+1')
                            ->helperText('Придёт с кем-то')
                            ->default(false),
                    ])
                    ->columns(2),
                Forms\Components\Section::make('Информация')
                    ->visibleOn('edit')
                    ->schema([
                        Forms\Components\Placeholder::make('guest_info')
                            ->label('Гость')
                            ->content(fn (Invite $record): string => $record->guest?->full_name ?: 'Не указан'),
                        Forms\Components\Placeholder::make('event_info')
                            ->label('Мероприятие')
                            ->content(fn (Invite $record): string => $record->event?->name ?: 'Не указано'),
                        Forms\Components\Placeholder::make('datetime_info')
                            ->label('Дата и время мероприятия')
                            ->content(fn (Invite $record): string => $record->event?->datetime?->format('d.m.Y H:i') ?: 'Не указаны'),
                        Forms\Components\Placeholder::make('location_info')
                            ->label('Место')
                            ->content(fn (Invite $record): string => $record->event?->location ?: 'Не указано'),
                        Forms\Components\Placeholder::make('approval_info')
                            ->label('Статус ответа')
                            ->content(function (Invite $record): string {
                                if (is_null($record->approval)) {
                                    return 'Без ответа';
                                }

                                return $record->approval ? 'Подтверждено' : 'Отклонено';
                            }),
                        Forms\Components\Placeholder::make('sent_info')
                            ->label('Отправлено')
                            ->content(fn (Invite $record): string => $record->sent ? 'Да' : 'Нет'),
                        Forms\Components\Placeholder::make('url_info')
                            ->label('Публичная ссылка')
                            ->content(fn (Invite $record): string => $record->url),
                        Forms\Components\Placeholder::make('created_at_info')
                            ->label('Создано')
                            ->content(fn (Invite $record): string => $record->created_at?->format('d.m.Y H:i') ?: 'Неизвестно'),
                        Forms\Components\Placeholder::make('updated_at_info')
                            ->label('Обновлено')
                            ->content(fn (Invite $record): string => $record->updated_at?->format('d.m.Y H:i') ?: 'Неизвестно'),
                    ])
                    ->columns(2),
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
                Tables\Columns\IconColumn::make('plus_one')
                    ->label('+1')
                    ->boolean()
                    ->alignCenter()
                    ->tooltip(fn (Invite $invite): string => $invite->plus_one ? 'Придёт с +1' : 'Без +1'),
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
                Tables\Filters\TernaryFilter::make('plus_one')
                    ->label('+1'),
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
            ])
            ->recordUrl(null);
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
            'edit' => Pages\EditInvite::route('/{record}/edit'),
        ];
    }
}
