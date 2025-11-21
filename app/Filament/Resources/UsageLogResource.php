<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UsageLogResource\Pages;
use App\Models\UsageLog;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class UsageLogResource extends Resource
{
    protected static ?string $model = UsageLog::class;

    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';
    
    protected static ?string $navigationGroup = 'Analytics & Logs';
    
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
                Forms\Components\Select::make('service_id')
                    ->label('Service')
                    ->relationship('service', 'name_en')
                    ->searchable()
                    ->preload(),
                Forms\Components\Select::make('action_type')
                    ->label('Action Type')
                    ->options([
                        'translation' => 'Translation',
                        'api_call' => 'API Call',
                        'file_upload' => 'File Upload',
                        'export' => 'Export',
                    ]),
                Forms\Components\TextInput::make('characters_used')
                    ->label('Characters Used')
                    ->numeric()
                    ->default(0),
                Forms\Components\TextInput::make('api_calls_used')
                    ->label('API Calls Used')
                    ->numeric()
                    ->default(0),
                Forms\Components\Textarea::make('metadata')
                    ->label('Metadata (JSON)')
                    ->rows(3),
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
                    ->sortable(),
                Tables\Columns\TextColumn::make('service.name_en')
                    ->label('Service')
                    ->sortable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('action_type')
                    ->label('Action')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'translation' => 'success',
                        'api_call' => 'info',
                        'file_upload' => 'warning',
                        'export' => 'primary',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('characters_used')
                    ->label('Characters')
                    ->formatStateUsing(fn ($state) => number_format($state))
                    ->sortable(),
                Tables\Columns\TextColumn::make('api_calls_used')
                    ->label('API Calls')
                    ->formatStateUsing(fn ($state) => number_format($state))
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Date')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('action_type')
                    ->label('Action Type')
                    ->options([
                        'translation' => 'Translation',
                        'api_call' => 'API Call',
                        'file_upload' => 'File Upload',
                        'export' => 'Export',
                    ]),
                Tables\Filters\SelectFilter::make('service_id')
                    ->label('Service')
                    ->relationship('service', 'name_en')
                    ->searchable()
                    ->preload(),
                Tables\Filters\SelectFilter::make('company_id')
                    ->label('Company')
                    ->relationship('company', 'name')
                    ->searchable()
                    ->preload(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
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
            'index' => Pages\ListUsageLogs::route('/'),
        ];
    }
}
