<?php

namespace App\Filament\Resources\BackgroundJobResource\Pages;

use App\Filament\Resources\BackgroundJobResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditBackgroundJob extends EditRecord
{
    protected static string $resource = BackgroundJobResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
