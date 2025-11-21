<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\SubscriptionPlanResource\Pages;
use App\Models\SubscriptionPlan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SubscriptionPlanResource extends Resource
{
    protected static ?string $model = SubscriptionPlan::class;

    protected static ?string $navigationIcon = 'heroicon-o-credit-card';
    
    protected static ?string $navigationLabel = 'باقات الاشتراك';
    
    protected static ?string $modelLabel = 'باقة اشتراك';
    
    protected static ?string $pluralModelLabel = 'باقات الاشتراك';
    
    protected static ?string $navigationGroup = 'إدارة الاشتراكات';
    
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('معلومات الباقة الأساسية')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('اسم الباقة')
                            ->required()
                            ->maxLength(255),
                        
                        Forms\Components\TextInput::make('slug')
                            ->label('الرمز')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255),
                        
                        Forms\Components\Textarea::make('description')
                            ->label('الوصف')
                            ->rows(3)
                            ->columnSpanFull(),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('التسعير')
                    ->schema([
                        Forms\Components\TextInput::make('price')
                            ->label('السعر')
                            ->required()
                            ->numeric()
                            ->prefix('$')
                            ->default(0),
                        
                        Forms\Components\Select::make('currency')
                            ->label('العملة')
                            ->options([
                                'USD' => 'USD - دولار أمريكي',
                                'EUR' => 'EUR - يورو',
                                'SAR' => 'SAR - ريال سعودي',
                                'AED' => 'AED - درهم إماراتي',
                            ])
                            ->default('USD')
                            ->required(),
                        
                        Forms\Components\Select::make('billing_period')
                            ->label('فترة الفوترة')
                            ->options([
                                'monthly' => 'شهرياً',
                                'yearly' => 'سنوياً',
                                'lifetime' => 'مدى الحياة',
                            ])
                            ->default('monthly')
                            ->required(),
                    ])
                    ->columns(3),

                Forms\Components\Section::make('الحدود والمميزات')
                    ->schema([
                        Forms\Components\TextInput::make('tokens_limit')
                            ->label('عدد التوكنات الشهرية')
                            ->required()
                            ->numeric()
                            ->default(1000)
                            ->helperText('عدد التوكنات المسموح بها شهرياً'),
                        
                        Forms\Components\TextInput::make('max_projects')
                            ->label('عدد المشاريع')
                            ->required()
                            ->numeric()
                            ->default(1),
                        
                        Forms\Components\TextInput::make('max_team_members')
                            ->label('عدد أعضاء الفريق')
                            ->required()
                            ->numeric()
                            ->default(1),
                        
                        Forms\Components\Toggle::make('api_access')
                            ->label('الوصول إلى API')
                            ->default(false),
                        
                        Forms\Components\Toggle::make('priority_support')
                            ->label('الدعم الفني المميز')
                            ->default(false),
                        
                        Forms\Components\Toggle::make('custom_integrations')
                            ->label('التكاملات المخصصة')
                            ->default(false),
                    ])
                    ->columns(3),

                Forms\Components\Section::make('المميزات الإضافية')
                    ->schema([
                        Forms\Components\TagsInput::make('features')
                            ->label('قائمة المميزات')
                            ->placeholder('أضف ميزة جديدة')
                            ->helperText('اضغط Enter لإضافة ميزة جديدة')
                            ->columnSpanFull(),
                    ]),

                Forms\Components\Section::make('الإعدادات')
                    ->schema([
                        Forms\Components\Toggle::make('is_popular')
                            ->label('باقة شائعة')
                            ->helperText('سيتم عرض شارة "الأكثر شعبية"')
                            ->default(false),
                        
                        Forms\Components\Toggle::make('is_custom')
                            ->label('باقة مخصصة')
                            ->helperText('تتطلب التواصل مع الإدارة')
                            ->default(false),
                        
                        Forms\Components\Toggle::make('is_active')
                            ->label('نشطة')
                            ->default(true),
                        
                        Forms\Components\TextInput::make('sort_order')
                            ->label('ترتيب العرض')
                            ->numeric()
                            ->default(0)
                            ->helperText('الترتيب في صفحة الباقات'),
                    ])
                    ->columns(4),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('اسم الباقة')
                    ->searchable()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('price')
                    ->label('السعر')
                    ->money(fn ($record) => $record->currency)
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('billing_period')
                    ->label('فترة الفوترة')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'monthly' => 'info',
                        'yearly' => 'success',
                        'lifetime' => 'warning',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'monthly' => 'شهرياً',
                        'yearly' => 'سنوياً',
                        'lifetime' => 'مدى الحياة',
                    }),
                
                Tables\Columns\TextColumn::make('tokens_limit')
                    ->label('التوكنات')
                    ->numeric()
                    ->sortable()
                    ->suffix(' توكن'),
                
                Tables\Columns\IconColumn::make('is_popular')
                    ->label('شائعة')
                    ->boolean(),
                
                Tables\Columns\IconColumn::make('is_custom')
                    ->label('مخصصة')
                    ->boolean(),
                
                Tables\Columns\IconColumn::make('is_active')
                    ->label('نشطة')
                    ->boolean(),
                
                Tables\Columns\TextColumn::make('subscriptions_count')
                    ->label('عدد المشتركين')
                    ->counts('activeSubscriptions')
                    ->badge()
                    ->color('success'),
                
                Tables\Columns\TextColumn::make('sort_order')
                    ->label('الترتيب')
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('created_at')
                    ->label('تاريخ الإنشاء')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
                
                Tables\Filters\SelectFilter::make('billing_period')
                    ->label('فترة الفوترة')
                    ->options([
                        'monthly' => 'شهرياً',
                        'yearly' => 'سنوياً',
                        'lifetime' => 'مدى الحياة',
                    ]),
                
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('نشطة')
                    ->placeholder('الكل')
                    ->trueLabel('نشطة فقط')
                    ->falseLabel('غير نشطة فقط'),
                
                Tables\Filters\TernaryFilter::make('is_popular')
                    ->label('شائعة'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\RestoreAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('sort_order');
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSubscriptionPlans::route('/'),
            'create' => Pages\CreateSubscriptionPlan::route('/create'),
            'edit' => Pages\EditSubscriptionPlan::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
