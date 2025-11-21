<?php

namespace App\Filament\Admin\Pages;

use Filament\Pages\Page;

class AiServerConsole extends Page
{
    protected static ?string $navigationIcon  = 'heroicon-o-command-line';
    protected static ?string $navigationLabel = 'AI Server Agent';
    protected static ?string $navigationGroup = 'System Tools';

    protected static ?string $title = 'AI Server Agent Console';
    protected static ?string $slug  = 'ai-server-console';

    // هذا هو الـ Blade View الذي سيُستخدم لعرض الصفحة
    protected static string $view = 'filament.admin.pages.ai-server-console';
}
