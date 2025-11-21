<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\UserSubscriptionResource\Pages;
use App\Models\UserSubscription;
use App\Models\SubscriptionPlan;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Carbon\Carbon;

class UserSubscriptionResource extends Resource
{
    protected static ?string $model = UserSubscription::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';
    
    protected static ?string $navigationLabel = 'اشتراكات المستخدمين';
    
    protected static ?string $modelLabel = 'اشتراك';
    
    protected static ?string $pluralModelLabel = 'اشتراكات المستخدمين';
    
    protected static ?string $navigationGroup = 'إدارة الاشتراكات';
    
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('معلومات الاشتراك')
                    ->schema([
                        Forms\Components\Select::make('user_id')
                            ->label('المستخدم')
                            ->options(User::all()->pluck('name', 'id'))
                            ->searchable()
                            ->required()
                            ->preload(),
                        
                        Forms\Components\Select::make('subscription_plan_id')
                            ->label('الباقة')
                            ->options(SubscriptionPlan::active()->pluck('name', 'id'))
                            ->searchable()
                            ->required()
                            ->preload()
                            ->reactive()
                            ->afterStateUpdated(function ($state, callable $set) {
                                $plan = SubscriptionPlan::find($state);
                                if ($plan) {
                                    $set('tokens_remaining', $plan->tokens_limit);
                                }
                            }),
                        
                        Forms\Components\Select::make('status')
                            ->label('الحالة')
                            ->options([
                                'active' => 'نشط',
                                'expired' => 'منتهي',
                                'cancelled' => 'ملغي',
                                'pending' => 'قيد الانتظار',
                            ])
                            ->default('pending')
                            ->required(),
                    ])
                    ->columns(3),

                Forms\Components\Section::make('التوكنات')
                    ->schema([
                        Forms\Components\TextInput::make('tokens_used')
                            ->label('التوكنات المستخدمة')
                            ->numeric()
                            ->default(0)
                            ->required(),
                        
                        Forms\Components\TextInput::make('tokens_remaining')
                            ->label('التوكنات المتبقية')
                            ->numeric()
                            ->required()
                            ->helperText('سيتم تحديثه تلقائياً عند اختيار الباقة'),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('التواريخ')
                    ->schema([
                        Forms\Components\DateTimePicker::make('starts_at')
                            ->label('تاريخ البدء')
                            ->default(now())
                            ->required(),
                        
                        Forms\Components\DateTimePicker::make('expires_at')
                            ->label('تاريخ الانتهاء')
                            ->default(now()->addMonth())
                            ->required(),
                        
                        Forms\Components\DateTimePicker::make('cancelled_at')
                            ->label('تاريخ الإلغاء'),
                    ])
                    ->columns(3),

                Forms\Components\Section::make('الإعدادات')
                    ->schema([
                        Forms\Components\Toggle::make('auto_renew')
                            ->label('التجديد التلقائي')
                            ->default(true),
                        
                        Forms\Components\Textarea::make('cancellation_reason')
                            ->label('سبب الإلغاء')
                            ->rows(3)
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('المستخدم')
                    ->searchable()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('user.email')
                    ->label('البريد الإلكتروني')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                
                Tables\Columns\TextColumn::make('plan.name')
                    ->label('الباقة')
                    ->searchable()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('status')
                    ->label('الحالة')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'active' => 'success',
                        'expired' => 'danger',
                        'cancelled' => 'warning',
                        'pending' => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'active' => 'نشط',
                        'expired' => 'منتهي',
                        'cancelled' => 'ملغي',
                        'pending' => 'قيد الانتظار',
                    }),
                
                Tables\Columns\TextColumn::make('tokens_remaining')
                    ->label('التوكنات المتبقية')
                    ->numeric()
                    ->sortable()
                    ->color(fn ($record): string => 
                        $record->tokens_remaining < ($record->plan->tokens_limit * 0.2) ? 'danger' : 'success'
                    ),
                
                Tables\Columns\TextColumn::make('tokens_used')
                    ->label('التوكنات المستخدمة')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                
                Tables\Columns\TextColumn::make('token_usage_percentage')
                    ->label('نسبة الاستخدام')
                    ->formatStateUsing(fn ($record): string => 
                        number_format($record->token_usage_percentage, 1) . '%'
                    )
                    ->color(fn ($record): string => 
                        $record->token_usage_percentage > 80 ? 'danger' : 
                        ($record->token_usage_percentage > 50 ? 'warning' : 'success')
                    ),
                
                Tables\Columns\TextColumn::make('starts_at')
                    ->label('تاريخ البدء')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                
                Tables\Columns\TextColumn::make('expires_at')
                    ->label('تاريخ الانتهاء')
                    ->dateTime()
                    ->sortable()
                    ->color(fn ($record): string => 
                        $record->expires_at && $record->expires_at->isPast() ? 'danger' : 
                        ($record->expires_at && $record->expires_at->diffInDays() < 7 ? 'warning' : 'success')
                    ),
                
                Tables\Columns\TextColumn::make('days_remaining')
                    ->label('الأيام المتبقية')
                    ->formatStateUsing(fn ($record): string => 
                        $record->days_remaining !== null ? 
                        ($record->days_remaining > 0 ? $record->days_remaining . ' يوم' : 'منتهي') : 
                        'غير محدد'
                    )
                    ->color(fn ($record): string => 
                        $record->days_remaining !== null && $record->days_remaining < 7 ? 'danger' : 'success'
                    ),
                
                Tables\Columns\IconColumn::make('auto_renew')
                    ->label('تجديد تلقائي')
                    ->boolean()
                    ->toggleable(isToggledHiddenByDefault: true),
                
                Tables\Columns\TextColumn::make('created_at')
                    ->label('تاريخ الإنشاء')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
                
                Tables\Filters\SelectFilter::make('status')
                    ->label('الحالة')
                    ->options([
                        'active' => 'نشط',
                        'expired' => 'منتهي',
                        'cancelled' => 'ملغي',
                        'pending' => 'قيد الانتظار',
                    ]),
                
                Tables\Filters\SelectFilter::make('subscription_plan_id')
                    ->label('الباقة')
                    ->relationship('plan', 'name'),
                
                Tables\Filters\Filter::make('low_tokens')
                    ->label('توكنات منخفضة')
                    ->query(fn (Builder $query): Builder => 
                        $query->whereRaw('tokens_remaining < (SELECT tokens_limit FROM subscription_plans WHERE id = subscription_plan_id) * 0.2')
                    ),
                
                Tables\Filters\Filter::make('expiring_soon')
                    ->label('ينتهي قريباً')
                    ->query(fn (Builder $query): Builder => 
                        $query->where('expires_at', '<=', Carbon::now()->addDays(7))
                              ->where('expires_at', '>', Carbon::now())
                    ),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
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
            'index' => Pages\ListUserSubscriptions::route('/'),
            'create' => Pages\CreateUserSubscription::route('/create'),
            'edit' => Pages\EditUserSubscription::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
    
    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('status', 'active')->count();
    }
}
