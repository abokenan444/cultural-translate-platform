<?php

namespace App\Providers\Filament;

use Filament\Panel;
use Filament\Pages;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use App\Filament\Admin\Pages\AiDevChat;
use App\Filament\Admin\Pages\AiServerConsole;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin-dashboard')
            ->login()
            ->colors([
                'primary' => Color::Sky,
            ])
            ->discoverResources(
                in: app_path('Filament/Resources'),
                for: 'App\\Filament\\Resources',
            )
            ->discoverPages(
                in: app_path('Filament/Admin/Pages'),
                for: 'App\\Filament\\Admin\\Pages',
            )
            ->discoverWidgets(
                in: app_path('Filament/Admin/Widgets'),
                for: 'App\\Filament\\Admin\\Widgets',
            )
            ->pages([
                Pages\Dashboard::class,
                AiDevChat::class,
                AiServerConsole::class,
            ]);
    }
}
