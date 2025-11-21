<?php

namespace App\Filament\Resources\KpiSnapshotResource\Pages;

use App\Filament\Resources\KpiSnapshotResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewKpiSnapshot extends ViewRecord
{
    protected static string $resource = KpiSnapshotResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
