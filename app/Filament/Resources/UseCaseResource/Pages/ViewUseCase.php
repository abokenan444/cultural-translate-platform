<?php

namespace App\Filament\Resources\UseCaseResource\Pages;

use App\Filament\Resources\UseCaseResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewUseCase extends ViewRecord
{
    protected static string $resource = UseCaseResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
