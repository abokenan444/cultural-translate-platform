<?php

namespace App\Filament\Resources\CompanyServiceResource\Pages;

use App\Filament\Resources\CompanyServiceResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCompanyServices extends ListRecords
{
    protected static string $resource = CompanyServiceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
