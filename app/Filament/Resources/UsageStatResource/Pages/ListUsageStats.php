<?php

namespace App\Filament\Resources\UsageStatResource\Pages;

use App\Filament\Resources\UsageStatResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListUsageStats extends ListRecords
{
    protected static string $resource = UsageStatResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
