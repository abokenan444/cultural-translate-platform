# Deprecated Files and Components

This document lists files and components that have been deprecated after migrating to Filament Admin Panel.

## Deprecated Controllers

The following controllers are no longer used as their functionality has been moved to Filament:

- `App\Http\Controllers\Admin\AdminDashboardController` - Replaced by Filament Dashboard
- `App\Http\Controllers\Admin\DashboardController` - Replaced by Filament Dashboard  
- `App\Http\Controllers\Admin\AIAgentChatController` - Replaced by Filament Page `AiDevChat`

**Note**: These files are kept for reference but are not loaded or used in the application.

## Deprecated Routes

The following routes in `routes/web.php` have been commented out:

```php
// OLD: Admin Dashboard Routes (replaced by Filament)
// Route::middleware(['auth'])->prefix('admin-dashboard')...

// OLD: AI Dev Chat Routes (replaced by Filament Pages)
// Route::middleware(['auth', 'admin'])->prefix('admin')...
```

## Deprecated Views

Old admin dashboard views (if any) in `resources/views/admin-dashboard/` are no longer used.

## Current Admin System

**Active Admin Panel**: Filament v3
- **Path**: `/admin`
- **Provider**: `App\Providers\Filament\AdminPanelProvider`
- **Pages**:
  - Dashboard (default)
  - AiDevChat (`App\Filament\Admin\Pages\AiDevChat`)
  - AiServerConsole (`App\Filament\Admin\Pages\AiServerConsole`)

## Migration Notes

- All admin functionality is now centralized in Filament
- AI Agent integration uses `App\Services\AIAgentService`
- Configuration in `config/ai_agent.php`
- No route conflicts between old and new systems

## Cleanup Recommendations

For production deployment, consider:
1. Removing deprecated controller files
2. Removing old admin dashboard views
3. Removing unused Blade templates
4. Keeping only Filament-based admin system

Last Updated: 2024-11-21
