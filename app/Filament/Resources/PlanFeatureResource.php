<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PlanFeatureResource\Pages;
use App\Models\PlanFeature;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class PlanFeatureResource extends Resource
{
    protected static ?string $model = PlanFeature::class;

    protected static ?string $navigationIcon = 'heroicon-o-list-bullet';
    
    protected static ?string $navigationGroup = 'Subscription Management';
    
    protected static ?int $navigationSort = 5;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('plan_id')
                    ->label('Plan')
                    ->relationship('plan', 'name_en')
                    ->required()
                    ->searchable()
                    ->preload(),
                Forms\Components\Select::make('feature_id')
                    ->label('Feature')
                    ->relationship('feature', 'name_en')
                    ->required()
                    ->searchable()
                    ->preload(),
                Forms\Components\Toggle::make('is_included')
                    ->label('Included')
                    ->default(true),
                Forms\Components\TextInput::make('limit_value')
                    ->label('Limit Value')
                    ->numeric()
                    ->helperText('Numeric limit for this feature (0 = unlimited)'),
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
                Tables\Columns\TextColumn::make('plan.name_en')
                    ->label('Plan')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('feature.name_en')
                    ->label('Feature')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_included')
                    ->label('Included')
                    ->boolean(),
                Tables\Columns\TextColumn::make('limit_value')
                    ->label('Limit')
                    ->formatStateUsing(fn ($state) => $state == 0 || $state === null ? 'Unlimited' : number_format($state)),
                Tables\Columns\TextColumn::make('display_order')
                    ->label('Order')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('plan_id')
                    ->label('Plan')
                    ->relationship('plan', 'name_en')
                    ->searchable()
                    ->preload(),
                Tables\Filters\TernaryFilter::make('is_included')
                    ->label('Included')
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
            'index' => Pages\ListPlanFeatures::route('/'),
            'create' => Pages\CreatePlanFeature::route('/create'),
            'edit' => Pages\EditPlanFeature::route('/{record}/edit'),
        ];
    }
}
