<?php

namespace App\Filament\Resources;

use App\Models\ApiKey;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Tables;
use App\Filament\Resources\ApiKeyResource\Pages;

class ApiKeyResource extends Resource
{
    protected static ?string $model = ApiKey::class;

    protected static ?string $navigationIcon  = 'heroicon-o-key';
    protected static ?string $navigationGroup = 'API & Usage';
    protected static ?string $navigationLabel = 'API Keys';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Select::make('company_id')
                ->relationship('company', 'name')
                ->required(),

            Forms\Components\TextInput::make('name')
                ->label('Key label')
                ->required(),

            Forms\Components\TextInput::make('key')
                ->required()
                ->helperText('Store hashed in DB and only display once if needed.'),

            Forms\Components\Select::make('type')
                ->options([
                    'primary'  => 'Primary',
                    'backup'   => 'Backup',
                    'internal' => 'Internal',
                ])
                ->required(),

            Forms\Components\TextInput::make('daily_limit')
                ->numeric()
                ->label('Daily request limit'),

            Forms\Components\Toggle::make('is_active')
                ->default(true),
        ])->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('company.name')->label('Company'),
                Tables\Columns\TextColumn::make('name'),
                Tables\Columns\TextColumn::make('type'),
                Tables\Columns\TextColumn::make('daily_limit'),
                Tables\Columns\IconColumn::make('is_active')->boolean(),
                Tables\Columns\TextColumn::make('last_used_at')->dateTime('Y-m-d H:i'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('company')->relationship('company', 'name'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListApiKeys::route('/'),
            'create' => Pages\CreateApiKey::route('/create'),
            'edit'   => Pages\EditApiKey::route('/{record}/edit'),
        ];
    }
}
