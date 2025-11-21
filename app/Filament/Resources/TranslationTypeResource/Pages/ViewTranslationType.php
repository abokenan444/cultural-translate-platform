<?php

namespace App\Filament\Resources\TranslationTypeResource\Pages;

use App\Filament\Resources\TranslationTypeResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewTranslationType extends ViewRecord
{
    protected static string $resource = TranslationTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
