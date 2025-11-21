<?php

namespace App\Filament\Resources;

use App\Filament\Resources\GlossaryResource\Pages;
use App\Models\Glossary;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class GlossaryResource extends Resource
{
    protected static ?string $model = Glossary::class;

    protected static ?string $navigationIcon = 'heroicon-o-book-open';
    
    protected static ?string $navigationGroup = 'Translation Management';
    
    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('company_id')
                    ->label('Company')
                    ->relationship('company', 'name')
                    ->searchable()
                    ->preload(),
                Forms\Components\Select::make('language_id')
                    ->label('Language')
                    ->relationship('language', 'name_en')
                    ->required()
                    ->searchable()
                    ->preload(),
                Forms\Components\TextInput::make('term')
                    ->label('Term')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Textarea::make('definition')
                    ->label('Definition')
                    ->required()
                    ->rows(3),
                Forms\Components\TextInput::make('category')
                    ->label('Category')
                    ->maxLength(255),
                Forms\Components\Textarea::make('context')
                    ->label('Context')
                    ->rows(2),
                Forms\Components\Toggle::make('is_active')
                    ->label('Active')
                    ->default(true),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('term')
                    ->label('Term')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('definition')
                    ->label('Definition')
                    ->limit(50)
                    ->searchable(),
                Tables\Columns\TextColumn::make('language.name_en')
                    ->label('Language')
                    ->sortable(),
                Tables\Columns\TextColumn::make('category')
                    ->label('Category')
                    ->badge(),
                Tables\Columns\TextColumn::make('company.name')
                    ->label('Company')
                    ->searchable()
                    ->toggleable(),
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Active')
                    ->boolean(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('language_id')
                    ->label('Language')
                    ->relationship('language', 'name_en')
                    ->searchable()
                    ->preload(),
                Tables\Filters\SelectFilter::make('company_id')
                    ->label('Company')
                    ->relationship('company', 'name')
                    ->searchable()
                    ->preload(),
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Active')
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
            ->defaultSort('term');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListGlossaries::route('/'),
            'create' => Pages\CreateGlossary::route('/create'),
            'edit' => Pages\EditGlossary::route('/{record}/edit'),
        ];
    }
}
