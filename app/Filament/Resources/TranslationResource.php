<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TranslationResource\Pages;
use App\Models\Translation;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class TranslationResource extends Resource
{
    protected static ?string $model = Translation::class;

    protected static ?string $navigationIcon = 'heroicon-o-language';
    
    protected static ?string $navigationGroup = 'Translation Management';
    
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('user_id')
                    ->label('User')
                    ->relationship('user', 'name')
                    ->searchable()
                    ->preload(),
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
                    ->rows(4),
                Forms\Components\Textarea::make('translated_text')
                    ->label('Translated Text')
                    ->rows(4),
                Forms\Components\Select::make('ai_model_id')
                    ->label('AI Model')
                    ->relationship('aiModel', 'name')
                    ->searchable()
                    ->preload(),
                Forms\Components\TextInput::make('character_count')
                    ->label('Character Count')
                    ->numeric()
                    ->default(0),
                Forms\Components\TextInput::make('processing_time')
                    ->label('Processing Time (seconds)')
                    ->numeric()
                    ->step(0.01),
                Forms\Components\Select::make('status')
                    ->label('Status')
                    ->options([
                        'pending' => 'Pending',
                        'processing' => 'Processing',
                        'completed' => 'Completed',
                        'failed' => 'Failed',
                    ])
                    ->default('pending'),
                Forms\Components\Textarea::make('error_message')
                    ->label('Error Message')
                    ->rows(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('User')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('company.name')
                    ->label('Company')
                    ->searchable()
                    ->sortable()
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
                    ->limit(50)
                    ->searchable(),
                Tables\Columns\TextColumn::make('character_count')
                    ->label('Characters')
                    ->formatStateUsing(fn ($state) => number_format($state))
                    ->sortable(),
                Tables\Columns\TextColumn::make('aiModel.name')
                    ->label('Model')
                    ->toggleable(),
                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'completed' => 'success',
                        'processing' => 'info',
                        'pending' => 'warning',
                        'failed' => 'danger',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'pending' => 'Pending',
                        'processing' => 'Processing',
                        'completed' => 'Completed',
                        'failed' => 'Failed',
                    ]),
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
                Tables\Filters\SelectFilter::make('user_id')
                    ->label('User')
                    ->relationship('user', 'name')
                    ->searchable()
                    ->preload(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTranslations::route('/'),
            'create' => Pages\CreateTranslation::route('/create'),
            'edit' => Pages\EditTranslation::route('/{record}/edit'),
        ];
    }
}
