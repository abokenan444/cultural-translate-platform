<?php

namespace App\Filament\Resources\TranslationTypeResource\Pages;

use App\Filament\Resources\TranslationTypeResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTranslationType extends EditRecord
{
    protected static string $resource = TranslationTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
