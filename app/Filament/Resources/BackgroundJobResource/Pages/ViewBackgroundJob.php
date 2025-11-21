<?php

namespace App\Filament\Resources\BackgroundJobResource\Pages;

use App\Filament\Resources\BackgroundJobResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewBackgroundJob extends ViewRecord
{
    protected static string $resource = BackgroundJobResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
