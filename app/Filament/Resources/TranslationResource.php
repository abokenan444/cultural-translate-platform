<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TranslationResource\Pages;
use App\Filament\Resources\TranslationResource\RelationManagers;
use App\Models\Translation;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TranslationResource extends Resource
{
    protected static ?string $model = Translation::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('user_id')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('company_id')
                    ->numeric(),
                Forms\Components\TextInput::make('type')
                    ->required(),
                Forms\Components\TextInput::make('source_language')
                    ->required(),
                Forms\Components\TextInput::make('target_language')
                    ->required(),
                Forms\Components\TextInput::make('source_culture'),
                Forms\Components\TextInput::make('target_culture'),
                Forms\Components\TextInput::make('model_id')
                    ->numeric(),
                Forms\Components\TextInput::make('api_key_id')
                    ->numeric(),
                Forms\Components\TextInput::make('tokens_in')
                    ->required()
                    ->numeric()
                    ->default(0),
                Forms\Components\TextInput::make('tokens_out')
                    ->required()
                    ->numeric()
                    ->default(0),
                Forms\Components\TextInput::make('total_tokens')
                    ->required()
                    ->numeric()
                    ->default(0),
                Forms\Components\TextInput::make('cost')
                    ->required()
                    ->numeric()
                    ->default(0)
                    ->prefix('$'),
                Forms\Components\TextInput::make('response_time_ms')
                    ->required()
                    ->numeric()
                    ->default(0),
                Forms\Components\TextInput::make('status')
                    ->required(),
                Forms\Components\Textarea::make('error_message')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('company_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('type')
                    ->searchable(),
                Tables\Columns\TextColumn::make('source_language')
                    ->searchable(),
                Tables\Columns\TextColumn::make('target_language')
                    ->searchable(),
                Tables\Columns\TextColumn::make('source_culture')
                    ->searchable(),
                Tables\Columns\TextColumn::make('target_culture')
                    ->searchable(),
                Tables\Columns\TextColumn::make('model_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('api_key_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('tokens_in')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('tokens_out')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('total_tokens')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('cost')
                    ->money()
                    ->sortable(),
                Tables\Columns\TextColumn::make('response_time_ms')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
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
            'index' => Pages\ListTranslations::route('/'),
            'create' => Pages\CreateTranslation::route('/create'),
            'view' => Pages\ViewTranslation::route('/{record}'),
            'edit' => Pages\EditTranslation::route('/{record}/edit'),
        ];
    }
}
