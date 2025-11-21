<?php

namespace App\Filament\Resources\SystemHealthResource\Pages;

use App\Filament\Resources\SystemHealthResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSystemHealth extends EditRecord
{
    protected static string $resource = SystemHealthResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
