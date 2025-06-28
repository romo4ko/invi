<?php

namespace App\Filament\Resources;

use App\Enum\Gender;
use App\Filament\Resources\GuestResource\Pages;
use App\Filament\Resources\GuestResource\RelationManagers;
use App\Models\Guest;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class GuestResource extends Resource
{
    protected static ?string $model = Guest::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    protected static ?int $navigationSort = 2;

    protected static ?string $navigationLabel = 'Гости';

    protected static ?string $modeLabel = 'Гости';

    protected static ?string $pluralModelLabel = 'Гости';

    protected static ?string $breadcrumb = 'Гости';

    protected static ?string $label = 'Гостя';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->label('Имя'),
                Forms\Components\TextInput::make('surname')
                    ->required()
                    ->label('Фамилия'),
                Forms\Components\TextInput::make('patronymic')
                    ->label('Отчество'),
                Forms\Components\Select::make('gender')
                    ->label('Пол')
                    ->options(Gender::translations())
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Имя')
                    ->searchable(),
                Tables\Columns\TextColumn::make('surname')
                    ->label('Фамилия')
                    ->searchable(),
                Tables\Columns\TextColumn::make('patronymic')
                    ->label('Отчество')
                    ->searchable(),
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
            'index' => Pages\ListGuests::route('/'),
            'create' => Pages\CreateGuest::route('/create'),
            'edit' => Pages\EditGuest::route('/{record}/edit'),
        ];
    }
}
