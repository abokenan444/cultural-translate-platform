<?php

namespace App\Filament\Resources\SystemHealthResource\Pages;

use App\Filament\Resources\SystemHealthResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSystemHealths extends ListRecords
{
    protected static string $resource = SystemHealthResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
