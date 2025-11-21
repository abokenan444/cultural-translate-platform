<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AiModelResource\Pages;
use App\Models\AiModel;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class AiModelResource extends Resource
{
    protected static ?string $model = AiModel::class;

    protected static ?string $navigationIcon = 'heroicon-o-cpu-chip';
    
    protected static ?string $navigationGroup = 'API Management';
    
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('api_provider_id')
                    ->label('API Provider')
                    ->relationship('apiProvider', 'name')
                    ->required()
                    ->searchable()
                    ->preload()
                    ->createOptionForm([
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('code')
                            ->required()
                            ->maxLength(255),
                    ]),
                Forms\Components\TextInput::make('name')
                    ->label('Model Name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('model_id')
                    ->label('Model ID')
                    ->required()
                    ->maxLength(255)
                    ->helperText('API model identifier (e.g., gpt-4, claude-3)'),
                Forms\Components\Textarea::make('description')
                    ->label('Description')
                    ->rows(3),
                Forms\Components\TextInput::make('max_tokens')
                    ->label('Max Tokens')
                    ->numeric()
                    ->default(4096),
                Forms\Components\TextInput::make('cost_per_1k_tokens')
                    ->label('Cost per 1K Tokens')
                    ->numeric()
                    ->step(0.0001)
                    ->prefix('$')
                    ->default(0),
                Forms\Components\Toggle::make('supports_translation')
                    ->label('Supports Translation')
                    ->default(true),
                Forms\Components\Toggle::make('supports_chat')
                    ->label('Supports Chat')
                    ->default(false),
                Forms\Components\Toggle::make('supports_vision')
                    ->label('Supports Vision')
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
                    ->label('Model')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('model_id')
                    ->label('Model ID')
                    ->searchable()
                    ->badge(),
                Tables\Columns\TextColumn::make('apiProvider.name')
                    ->label('Provider')
                    ->sortable(),
                Tables\Columns\TextColumn::make('max_tokens')
                    ->label('Max Tokens')
                    ->formatStateUsing(fn ($state) => number_format($state)),
                Tables\Columns\TextColumn::make('cost_per_1k_tokens')
                    ->label('Cost/1K')
                    ->money('USD')
                    ->sortable(),
                Tables\Columns\IconColumn::make('supports_translation')
                    ->label('Translation')
                    ->boolean(),
                Tables\Columns\IconColumn::make('supports_chat')
                    ->label('Chat')
                    ->boolean(),
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Active')
                    ->boolean(),
                Tables\Columns\TextColumn::make('display_order')
                    ->label('Order')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('api_provider_id')
                    ->label('Provider')
                    ->relationship('apiProvider', 'name')
                    ->searchable()
                    ->preload(),
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Active')
                    ->boolean(),
                Tables\Filters\TernaryFilter::make('supports_translation')
                    ->label('Translation')
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
            'index' => Pages\ListAiModels::route('/'),
            'create' => Pages\CreateAiModel::route('/create'),
            'edit' => Pages\EditAiModel::route('/{record}/edit'),
        ];
    }
}
