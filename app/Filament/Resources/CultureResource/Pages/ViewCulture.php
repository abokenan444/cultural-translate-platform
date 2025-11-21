<?php

namespace App\Filament\Resources\CultureResource\Pages;

use App\Filament\Resources\CultureResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewCulture extends ViewRecord
{
    protected static string $resource = CultureResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
