<?php

namespace App\Filament\Resources\SystemHealthResource\Pages;

use App\Filament\Resources\SystemHealthResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewSystemHealth extends ViewRecord
{
    protected static string $resource = SystemHealthResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
