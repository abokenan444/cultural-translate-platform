<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\User;
use App\Models\Company;
use App\Models\Translation;
use App\Models\Invoice;

class StatsOverviewWidget extends BaseWidget
{
    protected static ?int $sort = 0;
    
    protected function getStats(): array
    {
        $totalRevenue = Invoice::where('status', 'paid')->sum('total');
        $pendingInvoices = Invoice::where('status', 'pending')->count();
        $todayTranslations = Translation::whereDate('created_at', today())->count();
        
        return [
            Stat::make('Total Users', User::count())
                ->description('Active users in the system')
                ->descriptionIcon('heroicon-m-user-group')
                ->chart([7, 3, 4, 5, 6, 3, 5, 3])
                ->color('success')
                ->extraAttributes([
                    'class' => 'cursor-pointer hover:shadow-lg transition-all duration-300',
                ]),
                
            Stat::make('Active Companies', Company::where('status', 'active')->count())
                ->description('Companies with active status')
                ->descriptionIcon('heroicon-m-building-office')
                ->chart([3, 5, 4, 6, 7, 5, 6, 8])
                ->color('info')
                ->extraAttributes([
                    'class' => 'cursor-pointer hover:shadow-lg transition-all duration-300',
                ]),
                
            Stat::make('Total Translations', number_format(Translation::count()))
                ->description('All time translations')
                ->descriptionIcon('heroicon-m-language')
                ->chart([10, 20, 15, 30, 25, 40, 35, 50])
                ->color('warning')
                ->extraAttributes([
                    'class' => 'cursor-pointer hover:shadow-lg transition-all duration-300',
                ]),
                
            Stat::make('Total Revenue', '$' . number_format($totalRevenue, 2))
                ->description('Total paid invoices')
                ->descriptionIcon('heroicon-m-currency-dollar')
                ->chart([100, 200, 150, 300, 250, 400, 350, 500])
                ->color('success')
                ->extraAttributes([
                    'class' => 'cursor-pointer hover:shadow-lg transition-all duration-300',
                ]),
                
            Stat::make('Pending Invoices', $pendingInvoices)
                ->description($pendingInvoices > 0 ? 'Awaiting payment' : 'All invoices paid!')
                ->descriptionIcon('heroicon-m-clock')
                ->color($pendingInvoices > 0 ? 'danger' : 'success')
                ->extraAttributes([
                    'class' => 'cursor-pointer hover:shadow-lg transition-all duration-300',
                ]),
                
            Stat::make('Today Translations', number_format($todayTranslations))
                ->description('Translations processed today')
                ->descriptionIcon('heroicon-m-chart-bar')
                ->chart([5, 10, 8, 15, 12, 20, 18, $todayTranslations])
                ->color('primary')
                ->extraAttributes([
                    'class' => 'cursor-pointer hover:shadow-lg transition-all duration-300',
                ]),
        ];
    }
    
    protected function getColumns(): int
    {
        return 3; // 3 columns layout
    }
}
