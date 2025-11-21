<?php

namespace App\Filament\Resources\TranslationMemoryResource\Pages;

use App\Filament\Resources\TranslationMemoryResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTranslationMemorys extends ListRecords
{
    protected static string $resource = TranslationMemoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
