<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\PaymentSettingResource\Pages;
use App\Models\PaymentSetting;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class PaymentSettingResource extends Resource
{
    protected static ?string $model = PaymentSetting::class;

    protected static ?string $navigationIcon = 'heroicon-o-credit-card';
    
    protected static ?string $navigationLabel = 'إعدادات الدفع';
    
    protected static ?string $modelLabel = 'إعداد دفع';
    
    protected static ?string $pluralModelLabel = 'إعدادات الدفع';
    
    protected static ?string $navigationGroup = 'الإعدادات';
    
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('معلومات عامة')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('اسم الإعداد')
                            ->required()
                            ->maxLength(255)
                            ->default('Stripe Payment'),
                        
                        Forms\Components\Select::make('provider')
                            ->label('مزود الدفع')
                            ->options([
                                'stripe' => 'Stripe',
                                'paypal' => 'PayPal',
                            ])
                            ->default('stripe')
                            ->required(),
                        
                        Forms\Components\Select::make('currency')
                            ->label('العملة الافتراضية')
                            ->options([
                                'USD' => 'USD - دولار أمريكي',
                                'EUR' => 'EUR - يورو',
                                'GBP' => 'GBP - جنيه إسترليني',
                                'SAR' => 'SAR - ريال سعودي',
                                'AED' => 'AED - درهم إماراتي',
                            ])
                            ->default('USD')
                            ->required(),
                    ])
                    ->columns(3),

                Forms\Components\Section::make('مفاتيح Stripe API')
                    ->schema([
                        Forms\Components\TextInput::make('stripe_public_key')
                            ->label('Public Key')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('pk_test_...')
                            ->helperText('المفتاح العام من Stripe Dashboard'),
                        
                        Forms\Components\TextInput::make('stripe_secret_key')
                            ->label('Secret Key')
                            ->password()
                            ->required()
                            ->maxLength(255)
                            ->placeholder('sk_test_...')
                            ->helperText('المفتاح السري - سيتم تشفيره تلقائياً'),
                        
                        Forms\Components\TextInput::make('stripe_webhook_secret')
                            ->label('Webhook Secret')
                            ->password()
                            ->maxLength(255)
                            ->placeholder('whsec_...')
                            ->helperText('مفتاح Webhook (اختياري)'),
                    ])
                    ->columns(3),

                Forms\Components\Section::make('الإعدادات')
                    ->schema([
                        Forms\Components\Toggle::make('is_active')
                            ->label('نشط')
                            ->default(true)
                            ->helperText('تفعيل/تعطيل هذا الإعداد'),
                        
                        Forms\Components\Toggle::make('is_test_mode')
                            ->label('وضع الاختبار')
                            ->default(true)
                            ->helperText('استخدام مفاتيح الاختبار أو الإنتاج'),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('الاسم')
                    ->searchable()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('provider')
                    ->label('المزود')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'stripe' => 'success',
                        'paypal' => 'info',
                        default => 'gray',
                    }),
                
                Tables\Columns\TextColumn::make('currency')
                    ->label('العملة')
                    ->sortable(),
                
                Tables\Columns\IconColumn::make('is_active')
                    ->label('نشط')
                    ->boolean(),
                
                Tables\Columns\IconColumn::make('is_test_mode')
                    ->label('وضع الاختبار')
                    ->boolean(),
                
                Tables\Columns\TextColumn::make('created_at')
                    ->label('تاريخ الإنشاء')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('نشط'),
                
                Tables\Filters\TernaryFilter::make('is_test_mode')
                    ->label('وضع الاختبار'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
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
            'index' => Pages\ListPaymentSettings::route('/'),
            'create' => Pages\CreatePaymentSetting::route('/create'),
            'edit' => Pages\EditPaymentSetting::route('/{record}/edit'),
        ];
    }
}
