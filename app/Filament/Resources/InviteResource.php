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
                    ->preload(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
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
            'index' => Pages\ListInvites::route('/'),
            'create' => Pages\CreateInvite::route('/create'),
            'edit' => Pages\EditInvite::route('/{record}/edit'),
        ];
    }
}
