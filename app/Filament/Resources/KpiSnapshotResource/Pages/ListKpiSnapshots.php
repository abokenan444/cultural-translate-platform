<?php

namespace App\Filament\Resources\KpiSnapshotResource\Pages;

use App\Filament\Resources\KpiSnapshotResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListKpiSnapshots extends ListRecords
{
    protected static string $resource = KpiSnapshotResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
