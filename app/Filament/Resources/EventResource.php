<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EventResource\Pages;
use App\Filament\Resources\EventResource\RelationManagers;
use App\Models\Event;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class EventResource extends Resource
{
    protected static ?string $model = Event::class;

    protected static ?string $navigationIcon = 'heroicon-o-map-pin';

    protected static ?int $navigationSort = 3;

    protected static ?string $navigationLabel = 'Мероприятия';

    protected static ?string $modeLabel = 'Мероприятия';

    protected static ?string $pluralModelLabel = 'Мероприятия';

    protected static ?string $breadcrumb = 'Мероприятия';

    protected static ?string $label = 'Мероприятие';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->label('Название мероприятия'),
                Forms\Components\TextInput::make('location')
                    ->required()
                    ->label('Место проведения'),
                Forms\Components\DateTimePicker::make('datetime')
                    ->required()
                    ->seconds(false)
                    ->label('Дата и время'),
                Forms\Components\TextInput::make('caption')
                    ->label('Подпись')
                    ->nullable(),
                Forms\Components\Section::make()
                    ->schema([
                        Forms\Components\RichEditor::make('content')
                            ->label('Текст приглашения')
                            ->columns(1),
                        Forms\Components\FileUpload::make('image')
                            ->label('Фоновое изображение')
                            ->rules(['image'])
                            ->nullable()
                            ->image()
                            ->imageCropAspectRatio('1:1')
                            ->disk('public')
                            ->visibility('public')
                            ->directory('events')
                            ->nullable(),
                    ]),
                Forms\Components\ColorPicker::make('color')
                    ->label('Цвет фона')
                    ->default('#ffffff')
                    ->nullable(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Название')
                    ->searchable(),
                Tables\Columns\TextColumn::make('datetime')
                    ->label('Дата и время')
                    ->dateTime('Y-m-d H:i')
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListEvents::route('/'),
            'create' => Pages\CreateEvent::route('/create'),
            'edit' => Pages\EditEvent::route('/{record}/edit'),
        ];
    }
}
