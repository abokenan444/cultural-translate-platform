<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\CompanyMemberResource\Pages;
use App\Models\CompanyMember;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class CompanyMemberResource extends Resource
{
    protected static ?string $model = CompanyMember::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';
    
    protected static ?string $navigationLabel = 'أعضاء الشركات';
    
    protected static ?string $modelLabel = 'عضو شركة';
    
    protected static ?string $pluralModelLabel = 'أعضاء الشركات';
    
    protected static ?string $navigationGroup = 'إدارة الشركات';
    
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('معلومات العضوية')
                    ->schema([
                        Forms\Components\Select::make('company_id')
                            ->label('الشركة')
                            ->relationship('company', 'name')
                            ->required()
                            ->searchable(),
                        
                        Forms\Components\Select::make('user_id')
                            ->label('المستخدم')
                            ->relationship('user', 'name')
                            ->required()
                            ->searchable(),
                        
                        Forms\Components\Select::make('role')
                            ->label('الدور')
                            ->options([
                                'owner' => 'المالك',
                                'admin' => 'مدير',
                                'manager' => 'مشرف',
                                'member' => 'عضو',
                                'viewer' => 'مشاهد',
                            ])
                            ->default('member')
                            ->required(),
                    ])
                    ->columns(3),

                Forms\Components\Section::make('الصلاحيات المخصصة')
                    ->schema([
                        Forms\Components\CheckboxList::make('permissions')
                            ->label('الصلاحيات')
                            ->options([
                                'manage_members' => 'إدارة الأعضاء',
                                'manage_projects' => 'إدارة المشاريع',
                                'manage_translations' => 'إدارة الترجمات',
                                'view_analytics' => 'عرض الإحصائيات',
                                'manage_billing' => 'إدارة الفواتير',
                                'create_translations' => 'إنشاء ترجمات',
                                'view_projects' => 'عرض المشاريع',
                                'view_translations' => 'عرض الترجمات',
                            ])
                            ->columns(2)
                            ->helperText('صلاحيات إضافية بجانب صلاحيات الدور'),
                    ]),

                Forms\Components\Section::make('الحالة')
                    ->schema([
                        Forms\Components\Toggle::make('is_active')
                            ->label('نشط')
                            ->default(true),
                        
                        Forms\Components\DateTimePicker::make('invited_at')
                            ->label('تاريخ الدعوة'),
                        
                        Forms\Components\DateTimePicker::make('joined_at')
                            ->label('تاريخ الانضمام'),
                    ])
                    ->columns(3),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('company.name')
                    ->label('الشركة')
                    ->searchable()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('user.name')
                    ->label('المستخدم')
                    ->searchable()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('user.email')
                    ->label('البريد الإلكتروني')
                    ->searchable(),
                
                Tables\Columns\TextColumn::make('role')
                    ->label('الدور')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'owner' => 'danger',
                        'admin' => 'warning',
                        'manager' => 'info',
                        'member' => 'success',
                        'viewer' => 'gray',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'owner' => 'المالك',
                        'admin' => 'مدير',
                        'manager' => 'مشرف',
                        'member' => 'عضو',
                        'viewer' => 'مشاهد',
                        default => $state,
                    }),
                
                Tables\Columns\IconColumn::make('is_active')
                    ->label('نشط')
                    ->boolean(),
                
                Tables\Columns\TextColumn::make('joined_at')
                    ->label('تاريخ الانضمام')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(),
                
                Tables\Columns\TextColumn::make('created_at')
                    ->label('تاريخ الإنشاء')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('company_id')
                    ->label('الشركة')
                    ->relationship('company', 'name'),
                
                Tables\Filters\SelectFilter::make('role')
                    ->label('الدور')
                    ->options([
                        'owner' => 'المالك',
                        'admin' => 'مدير',
                        'manager' => 'مشرف',
                        'member' => 'عضو',
                        'viewer' => 'مشاهد',
                    ]),
                
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('نشط'),
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
            'index' => Pages\ListCompanyMembers::route('/'),
            'create' => Pages\CreateCompanyMember::route('/create'),
            'edit' => Pages\EditCompanyMember::route('/{record}/edit'),
        ];
    }
}
