<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TranslationMemoryResource\Pages;
use App\Models\TranslationMemory;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class TranslationMemoryResource extends Resource
{
    protected static ?string $model = TranslationMemory::class;

    protected static ?string $navigationIcon = 'heroicon-o-archive-box';
    
    protected static ?string $navigationGroup = 'Translation Management';
    
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('company_id')
                    ->label('Company')
                    ->relationship('company', 'name')
                    ->searchable()
                    ->preload(),
                Forms\Components\Select::make('source_language_id')
                    ->label('Source Language')
                    ->relationship('sourceLanguage', 'name_en')
                    ->required()
                    ->searchable()
                    ->preload(),
                Forms\Components\Select::make('target_language_id')
                    ->label('Target Language')
                    ->relationship('targetLanguage', 'name_en')
                    ->required()
                    ->searchable()
                    ->preload(),
                Forms\Components\Textarea::make('source_text')
                    ->label('Source Text')
                    ->required()
                    ->rows(3),
                Forms\Components\Textarea::make('target_text')
                    ->label('Target Text')
                    ->required()
                    ->rows(3),
                Forms\Components\TextInput::make('usage_count')
                    ->label('Usage Count')
                    ->numeric()
                    ->default(0),
                Forms\Components\Toggle::make('is_verified')
                    ->label('Verified')
                    ->default(false),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('company.name')
                    ->label('Company')
                    ->searchable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('sourceLanguage.code')
                    ->label('From')
                    ->badge()
                    ->color('info'),
                Tables\Columns\TextColumn::make('targetLanguage.code')
                    ->label('To')
                    ->badge()
                    ->color('success'),
                Tables\Columns\TextColumn::make('source_text')
                    ->label('Source')
                    ->limit(40)
                    ->searchable(),
                Tables\Columns\TextColumn::make('target_text')
                    ->label('Target')
                    ->limit(40)
                    ->searchable(),
                Tables\Columns\TextColumn::make('usage_count')
                    ->label('Usage')
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_verified')
                    ->label('Verified')
                    ->boolean(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('source_language_id')
                    ->label('Source Language')
                    ->relationship('sourceLanguage', 'name_en')
                    ->searchable()
                    ->preload(),
                Tables\Filters\SelectFilter::make('target_language_id')
                    ->label('Target Language')
                    ->relationship('targetLanguage', 'name_en')
                    ->searchable()
                    ->preload(),
                Tables\Filters\TernaryFilter::make('is_verified')
                    ->label('Verified')
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
            ->defaultSort('usage_count', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTranslationMemories::route('/'),
            'create' => Pages\CreateTranslationMemory::route('/create'),
            'edit' => Pages\EditTranslationMemory::route('/{record}/edit'),
        ];
    }
}
