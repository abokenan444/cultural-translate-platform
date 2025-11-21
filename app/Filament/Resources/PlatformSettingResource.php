<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PlatformSettingResource\Pages;
use App\Models\PlatformSetting;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class PlatformSettingResource extends Resource
{
    protected static ?string $model = PlatformSetting::class;

    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';
    
    protected static ?string $navigationGroup = 'Website Settings';
    
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('key')
                    ->label('Setting Key')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(255)
                    ->helperText('Unique identifier for this setting'),
                Forms\Components\Textarea::make('value')
                    ->label('Value')
                    ->rows(3),
                Forms\Components\Select::make('type')
                    ->label('Type')
                    ->required()
                    ->options([
                        'text' => 'Text',
                        'number' => 'Number',
                        'boolean' => 'Boolean',
                        'json' => 'JSON',
                        'url' => 'URL',
                        'email' => 'Email',
                    ])
                    ->default('text'),
                Forms\Components\Select::make('group')
                    ->label('Group')
                    ->options([
                        'general' => 'General',
                        'email' => 'Email',
                        'api' => 'API',
                        'payment' => 'Payment',
                        'seo' => 'SEO',
                        'social' => 'Social',
                    ])
                    ->default('general'),
                Forms\Components\Textarea::make('description')
                    ->label('Description')
                    ->rows(2),
                Forms\Components\Toggle::make('is_public')
                    ->label('Public')
                    ->default(false)
                    ->helperText('Can be accessed via API'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('key')
                    ->label('Key')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('value')
                    ->label('Value')
                    ->limit(50)
                    ->searchable(),
                Tables\Columns\TextColumn::make('type')
                    ->label('Type')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'text' => 'gray',
                        'number' => 'info',
                        'boolean' => 'success',
                        'json' => 'warning',
                        'url' => 'primary',
                        'email' => 'danger',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('group')
                    ->label('Group')
                    ->badge(),
                Tables\Columns\IconColumn::make('is_public')
                    ->label('Public')
                    ->boolean(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Updated')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('type')
                    ->label('Type')
                    ->options([
                        'text' => 'Text',
                        'number' => 'Number',
                        'boolean' => 'Boolean',
                        'json' => 'JSON',
                        'url' => 'URL',
                        'email' => 'Email',
                    ]),
                Tables\Filters\SelectFilter::make('group')
                    ->label('Group')
                    ->options([
                        'general' => 'General',
                        'email' => 'Email',
                        'api' => 'API',
                        'payment' => 'Payment',
                        'seo' => 'SEO',
                        'social' => 'Social',
                    ]),
                Tables\Filters\TernaryFilter::make('is_public')
                    ->label('Public')
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
            ->defaultSort('key');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPlatformSettings::route('/'),
            'create' => Pages\CreatePlatformSetting::route('/create'),
            'edit' => Pages\EditPlatformSetting::route('/{record}/edit'),
        ];
    }
}
