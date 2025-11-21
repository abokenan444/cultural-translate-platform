<?php

namespace App\Filament\Resources\BackgroundJobResource\Pages;

use App\Filament\Resources\BackgroundJobResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListBackgroundJobs extends ListRecords
{
    protected static string $resource = BackgroundJobResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
