<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ApiProviderResource\Pages;
use App\Models\ApiProvider;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ApiProviderResource extends Resource
{
    protected static ?string $model = ApiProvider::class;

    protected static ?string $navigationIcon = 'heroicon-o-server';
    
    protected static ?string $navigationGroup = 'API Management';
    
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Provider Name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('code')
                    ->label('Provider Code')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(255)
                    ->helperText('Unique identifier (e.g., openai, google, anthropic)'),
                Forms\Components\Textarea::make('description')
                    ->label('Description')
                    ->rows(3),
                Forms\Components\TextInput::make('api_base_url')
                    ->label('API Base URL')
                    ->url()
                    ->maxLength(255),
                Forms\Components\TextInput::make('api_key')
                    ->label('API Key')
                    ->password()
                    ->maxLength(255)
                    ->helperText('Will be encrypted'),
                Forms\Components\TextInput::make('api_version')
                    ->label('API Version')
                    ->maxLength(255),
                Forms\Components\TextInput::make('max_tokens')
                    ->label('Max Tokens')
                    ->numeric()
                    ->default(4096),
                Forms\Components\TextInput::make('rate_limit')
                    ->label('Rate Limit (requests/minute)')
                    ->numeric()
                    ->default(60),
                Forms\Components\Toggle::make('supports_streaming')
                    ->label('Supports Streaming')
                    ->default(false),
                Forms\Components\Toggle::make('is_active')
                    ->label('Active')
                    ->default(true),
                Forms\Components\TextInput::make('display_order')
                    ->label('Display Order')
                    ->numeric()
                    ->default(0),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Provider')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('code')
                    ->label('Code')
                    ->searchable()
                    ->badge(),
                Tables\Columns\TextColumn::make('models_count')
                    ->label('Models')
                    ->counts('models'),
                Tables\Columns\TextColumn::make('max_tokens')
                    ->label('Max Tokens')
                    ->formatStateUsing(fn ($state) => number_format($state)),
                Tables\Columns\TextColumn::make('rate_limit')
                    ->label('Rate Limit')
                    ->suffix(' req/min'),
                Tables\Columns\IconColumn::make('supports_streaming')
                    ->label('Streaming')
                    ->boolean(),
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Active')
                    ->boolean(),
                Tables\Columns\TextColumn::make('display_order')
                    ->label('Order')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Active')
                    ->boolean(),
                Tables\Filters\TernaryFilter::make('supports_streaming')
                    ->label('Supports Streaming')
                    ->boolean(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('display_order');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListApiProviders::route('/'),
            'create' => Pages\CreateApiProvider::route('/create'),
            'edit' => Pages\EditApiProvider::route('/{record}/edit'),
        ];
    }
}
