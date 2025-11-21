<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AiAgentMessageResource\Pages;
use App\Models\AiAgentMessage;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class AiAgentMessageResource extends Resource
{
    protected static ?string $model = AiAgentMessage::class;

    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-left-right';
    
    protected static ?string $navigationGroup = 'AI Management';
    
    protected static ?string $navigationLabel = 'AI Chat Messages';
    
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('user_id')
                    ->label('User')
                    ->relationship('user', 'name')
                    ->searchable()
                    ->preload(),
                    
                Forms\Components\Select::make('role')
                    ->label('Role')
                    ->required()
                    ->options([
                        'user' => 'User',
                        'assistant' => 'Assistant',
                        'system' => 'System',
                    ])
                    ->default('user'),
                    
                Forms\Components\Textarea::make('message')
                    ->label('Message')
                    ->required()
                    ->rows(4)
                    ->columnSpanFull(),
                    
                Forms\Components\Textarea::make('response')
                    ->label('Response')
                    ->rows(4)
                    ->columnSpanFull(),
                    
                Forms\Components\Select::make('status')
                    ->label('Status')
                    ->required()
                    ->options([
                        'pending' => 'Pending',
                        'processing' => 'Processing',
                        'completed' => 'Completed',
                        'failed' => 'Failed',
                    ])
                    ->default('pending'),
                    
                Forms\Components\KeyValue::make('meta')
                    ->label('Metadata')
                    ->helperText('Additional information like model, tokens, etc.')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('user.name')
                    ->label('User')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
                    
                Tables\Columns\TextColumn::make('role')
                    ->label('Role')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'user' => 'info',
                        'assistant' => 'success',
                        'system' => 'warning',
                        default => 'gray',
                    }),
                    
                Tables\Columns\TextColumn::make('message')
                    ->label('Message')
                    ->limit(50)
                    ->searchable()
                    ->wrap(),
                    
                Tables\Columns\TextColumn::make('response')
                    ->label('Response')
                    ->limit(50)
                    ->searchable()
                    ->wrap()
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
                    ->sortable()
                    ->since(),
                    
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Updated')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('role')
                    ->label('Role')
                    ->options([
                        'user' => 'User',
                        'assistant' => 'Assistant',
                        'system' => 'System',
                    ]),
                    
                Tables\Filters\SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'pending' => 'Pending',
                        'processing' => 'Processing',
                        'completed' => 'Completed',
                        'failed' => 'Failed',
                    ]),
                    
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
            'index' => Pages\ListAiAgentMessages::route('/'),
            'create' => Pages\CreateAiAgentMessage::route('/create'),
            'view' => Pages\ViewAiAgentMessage::route('/{record}'),
            'edit' => Pages\EditAiAgentMessage::route('/{record}/edit'),
        ];
    }
    
    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('status', 'pending')->count();
    }
}
