<?php

namespace App\Filament\Resources\UsageStatResource\Pages;

use App\Filament\Resources\UsageStatResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewUsageStat extends ViewRecord
{
    protected static string $resource = UsageStatResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
