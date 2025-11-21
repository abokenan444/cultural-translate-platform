<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\ComplaintResource\Pages;
use App\Models\Complaint;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ComplaintResource extends Resource
{
    protected static ?string $model = Complaint::class;

    protected static ?string $navigationIcon = 'heroicon-o-megaphone';
    
    protected static ?string $navigationLabel = 'الشكاوى والدعم';
    
    protected static ?string $modelLabel = 'شكوى';
    
    protected static ?string $pluralModelLabel = 'الشكاوى';
    
    protected static ?string $navigationGroup = 'خدمة العملاء';
    
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('معلومات التذكرة')
                    ->schema([
                        Forms\Components\TextInput::make('ticket_number')
                            ->label('رقم التذكرة')
                            ->disabled()
                            ->dehydrated(false),
                        
                        Forms\Components\Select::make('user_id')
                            ->label('المستخدم')
                            ->relationship('user', 'name')
                            ->searchable()
                            ->preload(),
                        
                        Forms\Components\Select::make('status')
                            ->label('الحالة')
                            ->options([
                                'open' => 'مفتوحة',
                                'in_progress' => 'قيد المعالجة',
                                'waiting_response' => 'بانتظار الرد',
                                'resolved' => 'تم الحل',
                                'closed' => 'مغلقة',
                            ])
                            ->default('open')
                            ->required()
                            ->reactive(),
                        
                        Forms\Components\Select::make('priority')
                            ->label('الأولوية')
                            ->options([
                                'low' => 'منخفضة',
                                'medium' => 'متوسطة',
                                'high' => 'عالية',
                                'urgent' => 'عاجلة',
                            ])
                            ->default('medium')
                            ->required(),
                        
                        Forms\Components\Select::make('category')
                            ->label('التصنيف')
                            ->options([
                                'technical' => 'مشكلة تقنية',
                                'billing' => 'الفواتير والاشتراكات',
                                'feature_request' => 'طلب ميزة جديدة',
                                'bug_report' => 'الإبلاغ عن خطأ',
                                'other' => 'أخرى',
                            ])
                            ->default('other')
                            ->required(),
                        
                        Forms\Components\Select::make('assigned_to')
                            ->label('مسند إلى')
                            ->options(User::where('is_admin', true)->pluck('name', 'id'))
                            ->searchable()
                            ->preload(),
                    ])
                    ->columns(3),

                Forms\Components\Section::make('معلومات العميل')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('الاسم')
                            ->required()
                            ->maxLength(255),
                        
                        Forms\Components\TextInput::make('email')
                            ->label('البريد الإلكتروني')
                            ->email()
                            ->required()
                            ->maxLength(255),
                        
                        Forms\Components\TextInput::make('phone')
                            ->label('رقم الهاتف')
                            ->tel()
                            ->maxLength(255),
                    ])
                    ->columns(3),

                Forms\Components\Section::make('تفاصيل الشكوى')
                    ->schema([
                        Forms\Components\TextInput::make('subject')
                            ->label('الموضوع')
                            ->required()
                            ->maxLength(255)
                            ->columnSpanFull(),
                        
                        Forms\Components\Textarea::make('message')
                            ->label('الرسالة')
                            ->required()
                            ->rows(5)
                            ->columnSpanFull(),
                        
                        Forms\Components\Textarea::make('admin_response')
                            ->label('رد الإدارة')
                            ->rows(5)
                            ->columnSpanFull()
                            ->visible(fn ($record) => $record !== null),
                    ]),

                Forms\Components\Section::make('التواريخ')
                    ->schema([
                        Forms\Components\DateTimePicker::make('responded_at')
                            ->label('تاريخ الرد'),
                        
                        Forms\Components\DateTimePicker::make('resolved_at')
                            ->label('تاريخ الحل'),
                        
                        Forms\Components\DateTimePicker::make('closed_at')
                            ->label('تاريخ الإغلاق'),
                    ])
                    ->columns(3)
                    ->visible(fn ($record) => $record !== null),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('ticket_number')
                    ->label('رقم التذكرة')
                    ->searchable()
                    ->sortable()
                    ->copyable()
                    ->weight('bold'),
                
                Tables\Columns\TextColumn::make('name')
                    ->label('الاسم')
                    ->searchable()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('email')
                    ->label('البريد الإلكتروني')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                
                Tables\Columns\TextColumn::make('subject')
                    ->label('الموضوع')
                    ->searchable()
                    ->limit(50)
                    ->tooltip(fn ($record): string => $record->subject),
                
                Tables\Columns\TextColumn::make('category')
                    ->label('التصنيف')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'technical' => 'danger',
                        'billing' => 'warning',
                        'feature_request' => 'info',
                        'bug_report' => 'danger',
                        'other' => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'technical' => 'مشكلة تقنية',
                        'billing' => 'فواتير',
                        'feature_request' => 'ميزة جديدة',
                        'bug_report' => 'خطأ',
                        'other' => 'أخرى',
                    }),
                
                Tables\Columns\TextColumn::make('priority')
                    ->label('الأولوية')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'low' => 'gray',
                        'medium' => 'info',
                        'high' => 'warning',
                        'urgent' => 'danger',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'low' => 'منخفضة',
                        'medium' => 'متوسطة',
                        'high' => 'عالية',
                        'urgent' => 'عاجلة',
                    }),
                
                Tables\Columns\TextColumn::make('status')
                    ->label('الحالة')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'open' => 'danger',
                        'in_progress' => 'warning',
                        'waiting_response' => 'info',
                        'resolved' => 'success',
                        'closed' => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'open' => 'مفتوحة',
                        'in_progress' => 'قيد المعالجة',
                        'waiting_response' => 'بانتظار الرد',
                        'resolved' => 'تم الحل',
                        'closed' => 'مغلقة',
                    }),
                
                Tables\Columns\TextColumn::make('assignedUser.name')
                    ->label('مسند إلى')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                
                Tables\Columns\TextColumn::make('created_at')
                    ->label('تاريخ الإنشاء')
                    ->dateTime()
                    ->sortable()
                    ->since()
                    ->tooltip(fn ($record): string => $record->created_at->format('Y-m-d H:i:s')),
                
                Tables\Columns\TextColumn::make('resolved_at')
                    ->label('تاريخ الحل')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
                
                Tables\Filters\SelectFilter::make('status')
                    ->label('الحالة')
                    ->options([
                        'open' => 'مفتوحة',
                        'in_progress' => 'قيد المعالجة',
                        'waiting_response' => 'بانتظار الرد',
                        'resolved' => 'تم الحل',
                        'closed' => 'مغلقة',
                    ]),
                
                Tables\Filters\SelectFilter::make('priority')
                    ->label('الأولوية')
                    ->options([
                        'low' => 'منخفضة',
                        'medium' => 'متوسطة',
                        'high' => 'عالية',
                        'urgent' => 'عاجلة',
                    ]),
                
                Tables\Filters\SelectFilter::make('category')
                    ->label('التصنيف')
                    ->options([
                        'technical' => 'مشكلة تقنية',
                        'billing' => 'الفواتير',
                        'feature_request' => 'ميزة جديدة',
                        'bug_report' => 'خطأ',
                        'other' => 'أخرى',
                    ]),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('respond')
                    ->label('الرد')
                    ->icon('heroicon-o-chat-bubble-left-right')
                    ->color('success')
                    ->form([
                        Forms\Components\Textarea::make('admin_response')
                            ->label('رد الإدارة')
                            ->required()
                            ->rows(5),
                    ])
                    ->action(function (Complaint $record, array $data) {
                        $record->update([
                            'admin_response' => $data['admin_response'],
                            'responded_at' => now(),
                            'status' => 'waiting_response',
                        ]);
                        
                        // Send email notification
                        // Mail::to($record->email)->send(new ComplaintResponseMail($record));
                    })
                    ->visible(fn (Complaint $record) => !$record->admin_response),
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
            'index' => Pages\ListComplaints::route('/'),
            'create' => Pages\CreateComplaint::route('/create'),
            'edit' => Pages\EditComplaint::route('/{record}/edit'),
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
        return static::getModel()::where('status', 'open')->count();
    }
    
    public static function getNavigationBadgeColor(): ?string
    {
        $count = static::getModel()::where('status', 'open')->count();
        return $count > 0 ? 'danger' : 'success';
    }
}
