<?php

namespace App\Filament\Resources;

use App\Models\TranslationLog;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Tables;
use App\Filament\Resources\TranslationLogResource\Pages;

class TranslationLogResource extends Resource
{
    protected static ?string $model = TranslationLog::class;

    protected static ?string $navigationIcon  = 'heroicon-o-document-text';
    protected static ?string $navigationGroup = 'API & Usage';
    protected static ?string $navigationLabel = 'Translation Logs';

    public static function form(\Filament\Forms\Form $form): \Filament\Forms\Form
    {
        // Read-only resource; no form.
        return $form;
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('company.name')->label('Company'),
                Tables\Columns\TextColumn::make('user.name')->label('User'),
                Tables\Columns\TextColumn::make('source_lang')->label('From'),
                Tables\Columns\TextColumn::make('target_lang')->label('To'),
                Tables\Columns\TextColumn::make('word_count')->label('Words'),
                Tables\Columns\TextColumn::make('model')->label('Model'),
                Tables\Columns\TextColumn::make('created_at')->dateTime('Y-m-d H:i'),
                Tables\Columns\IconColumn::make('has_error')->boolean()->label('Error?'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('company')->relationship('company', 'name'),
                Tables\Filters\SelectFilter::make('model'),
                Tables\Filters\TernaryFilter::make('has_error'),
            ])
            ->defaultSort('created_at', 'desc')
            ->actions([])
            ->bulkActions([]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTranslationLogs::route('/'),
        ];
    }
}
