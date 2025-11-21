<?php

namespace App\Filament\Resources\UsageLogResource\Pages;

use App\Filament\Resources\UsageLogResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditUsageLog extends EditRecord
{
    protected static string $resource = UsageLogResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
