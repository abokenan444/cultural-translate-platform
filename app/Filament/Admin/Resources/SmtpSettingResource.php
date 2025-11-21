<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\SmtpSettingResource\Pages;
use App\Models\SmtpSetting;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Notifications\Notification;

class SmtpSettingResource extends Resource
{
    protected static ?string $model = SmtpSetting::class;

    protected static ?string $navigationIcon = 'heroicon-o-envelope';
    
    protected static ?string $navigationLabel = 'إعدادات SMTP';
    
    protected static ?string $modelLabel = 'إعداد SMTP';
    
    protected static ?string $pluralModelLabel = 'إعدادات SMTP';
    
    protected static ?string $navigationGroup = 'الإعدادات';
    
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('معلومات الخادم')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('اسم الإعداد')
                            ->required()
                            ->maxLength(255)
                            ->default('Default SMTP'),
                        
                        Forms\Components\TextInput::make('host')
                            ->label('عنوان الخادم (Host)')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('smtp.gmail.com'),
                        
                        Forms\Components\TextInput::make('port')
                            ->label('المنفذ (Port)')
                            ->required()
                            ->numeric()
                            ->default(587)
                            ->minValue(1)
                            ->maxValue(65535),
                        
                        Forms\Components\Select::make('encryption')
                            ->label('نوع التشفير')
                            ->options([
                                'tls' => 'TLS',
                                'ssl' => 'SSL',
                                'none' => 'بدون تشفير',
                            ])
                            ->default('tls')
                            ->required(),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('بيانات الاعتماد')
                    ->schema([
                        Forms\Components\TextInput::make('username')
                            ->label('اسم المستخدم')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('your-email@gmail.com'),
                        
                        Forms\Components\TextInput::make('password')
                            ->label('كلمة المرور')
                            ->password()
                            ->required()
                            ->maxLength(255)
                            ->dehydrateStateUsing(fn ($state) => $state ? encrypt($state) : null)
                            ->helperText('سيتم تشفير كلمة المرور تلقائياً'),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('معلومات المرسل')
                    ->schema([
                        Forms\Components\TextInput::make('from_address')
                            ->label('البريد الإلكتروني للمرسل')
                            ->email()
                            ->required()
                            ->maxLength(255)
                            ->placeholder('noreply@culturaltranslate.com'),
                        
                        Forms\Components\TextInput::make('from_name')
                            ->label('اسم المرسل')
                            ->required()
                            ->maxLength(255)
                            ->default('CulturalTranslate'),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('الإعدادات')
                    ->schema([
                        Forms\Components\Toggle::make('is_active')
                            ->label('نشط')
                            ->default(true)
                            ->helperText('تفعيل/تعطيل هذا الإعداد'),
                        
                        Forms\Components\Toggle::make('is_default')
                            ->label('افتراضي')
                            ->default(false)
                            ->helperText('استخدام هذا الإعداد كإعداد افتراضي')
                            ->reactive()
                            ->afterStateUpdated(function ($state, $record) {
                                if ($state && $record) {
                                    $record->setAsDefault();
                                }
                            }),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('حالة الاختبار')
                    ->schema([
                        Forms\Components\Placeholder::make('last_tested_at')
                            ->label('آخر اختبار')
                            ->content(fn ($record): string => $record?->last_tested_at 
                                ? $record->last_tested_at->diffForHumans() 
                                : 'لم يتم الاختبار بعد'),
                        
                        Forms\Components\Placeholder::make('test_passed')
                            ->label('نتيجة الاختبار')
                            ->content(fn ($record): string => $record?->test_passed 
                                ? '✅ نجح' 
                                : ($record?->last_tested_at ? '❌ فشل' : 'لم يتم الاختبار')),
                        
                        Forms\Components\Placeholder::make('test_error')
                            ->label('رسالة الخطأ')
                            ->content(fn ($record): string => $record?->test_error ?? 'لا يوجد')
                            ->visible(fn ($record) => $record?->test_error !== null),
                    ])
                    ->columns(3)
                    ->visible(fn ($record) => $record !== null),
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
                
                Tables\Columns\TextColumn::make('host')
                    ->label('الخادم')
                    ->searchable(),
                
                Tables\Columns\TextColumn::make('port')
                    ->label('المنفذ')
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('encryption')
                    ->label('التشفير')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'tls' => 'success',
                        'ssl' => 'warning',
                        'none' => 'danger',
                    }),
                
                Tables\Columns\TextColumn::make('from_address')
                    ->label('البريد المرسل')
                    ->searchable(),
                
                Tables\Columns\IconColumn::make('is_active')
                    ->label('نشط')
                    ->boolean(),
                
                Tables\Columns\IconColumn::make('is_default')
                    ->label('افتراضي')
                    ->boolean(),
                
                Tables\Columns\IconColumn::make('test_passed')
                    ->label('الاختبار')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger'),
                
                Tables\Columns\TextColumn::make('last_tested_at')
                    ->label('آخر اختبار')
                    ->dateTime()
                    ->since()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                
                Tables\Columns\TextColumn::make('created_at')
                    ->label('تاريخ الإنشاء')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('نشط'),
                
                Tables\Filters\TernaryFilter::make('is_default')
                    ->label('افتراضي'),
                
                Tables\Filters\TernaryFilter::make('test_passed')
                    ->label('اجتاز الاختبار'),
            ])
            ->actions([
                Tables\Actions\Action::make('test')
                    ->label('اختبار الاتصال')
                    ->icon('heroicon-o-play')
                    ->color('info')
                    ->action(function (SmtpSetting $record) {
                        $result = $record->testConnection();
                        
                        if ($result) {
                            Notification::make()
                                ->title('نجح الاختبار!')
                                ->success()
                                ->send();
                        } else {
                            Notification::make()
                                ->title('فشل الاختبار')
                                ->body($record->test_error)
                                ->danger()
                                ->send();
                        }
                    }),
                
                Tables\Actions\Action::make('set_default')
                    ->label('تعيين كافتراضي')
                    ->icon('heroicon-o-star')
                    ->color('warning')
                    ->action(function (SmtpSetting $record) {
                        $record->setAsDefault();
                        
                        Notification::make()
                            ->title('تم التعيين كإعداد افتراضي')
                            ->success()
                            ->send();
                    })
                    ->visible(fn (SmtpSetting $record) => !$record->is_default),
                
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
            'index' => Pages\ListSmtpSettings::route('/'),
            'create' => Pages\CreateSmtpSetting::route('/create'),
            'edit' => Pages\EditSmtpSetting::route('/{record}/edit'),
        ];
    }
}
