<?php

namespace App\Filament\Pages;

use Filament\Pages\Dashboard as BaseDashboard;
use Filament\Widgets\StatsOverviewWidget as BaseStatsOverview;
use Filament\Widgets\StatsOverviewWidget\Card;

class Dashboard extends BaseDashboard
{
    protected static ?string $navigationIcon = 'heroicon-o-home';
    protected static ?string $navigationGroup = 'Overview';
    protected static ?string $navigationLabel = 'Dashboard';

    protected function getHeaderWidgets(): array
    {
        return [
            DashboardStats::class,
        ];
    }
}

class DashboardStats extends BaseStatsOverview
{
    protected function getCards(): array
    {
        return [
            Card::make('Active Companies', \App\Models\Company::query()->where('is_active', true)->count())
                ->description('Companies with active status')
                ->chart([7, 9, 12, 15, 18, 21])
                ->color('success'),

            Card::make('Total Users', \App\Models\User::query()->count())
                ->description('All registered users')
                ->chart([10, 12, 14, 18, 20, 24])
                ->color('primary'),

            Card::make(
                'Monthly Translations',
                \App\Models\TranslationLog::query()
                    ->whereMonth('created_at', now()->month)
                    ->sum('word_count')
            )
                ->description('Words translated this month')
                ->chart([1000, 3000, 4500, 8000, 12000, 16000])
                ->color('warning'),

            Card::make('API Error Rate', '0.3%')
                ->description('Errors per 1,000 requests (last 24h)')
                ->color('danger'),
        ];
    }
}
