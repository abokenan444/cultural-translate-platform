<?php

namespace App\Filament\Resources;

use App\Models\Plan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Tables;
use App\Filament\Resources\PlanResource\Pages;

class PlanResource extends Resource
{
    protected static ?string $model = Plan::class;

    protected static ?string $navigationIcon  = 'heroicon-o-currency-dollar';
    protected static ?string $navigationGroup = 'Billing & Plans';
    protected static ?string $navigationLabel = 'Plans';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('name')->required(),
            Forms\Components\TextInput::make('slug')->required()->unique(ignoreRecord: true),
            Forms\Components\TextInput::make('price_monthly')->numeric()->required(),
            Forms\Components\TextInput::make('currency')->default('USD'),
            Forms\Components\TextInput::make('word_limit_monthly')
                ->numeric()
                ->label('Monthly word limit'),
            Forms\Components\Textarea::make('description')->rows(3),
            Forms\Components\Toggle::make('is_active')->default(true),
        ])->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->sortable(),
                Tables\Columns\TextColumn::make('price_monthly')
                    ->money('usd', true)
                    ->label('Price / month'),
                Tables\Columns\TextColumn::make('word_limit_monthly')
                    ->label('Word limit / month'),
                Tables\Columns\IconColumn::make('is_active')->boolean(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListPlans::route('/'),
            'create' => Pages\CreatePlan::route('/create'),
            'edit'   => Pages\EditPlan::route('/{record}/edit'),
        ];
    }
}
