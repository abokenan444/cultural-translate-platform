<?php

namespace App\Filament\Resources\KpiSnapshotResource\Pages;

use App\Filament\Resources\KpiSnapshotResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditKpiSnapshot extends EditRecord
{
    protected static string $resource = KpiSnapshotResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
