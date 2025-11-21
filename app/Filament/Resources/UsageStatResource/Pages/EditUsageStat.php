<?php

namespace App\Filament\Resources\UsageStatResource\Pages;

use App\Filament\Resources\UsageStatResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditUsageStat extends EditRecord
{
    protected static string $resource = UsageStatResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
