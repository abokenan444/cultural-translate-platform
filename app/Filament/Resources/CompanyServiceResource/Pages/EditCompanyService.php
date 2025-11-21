<?php

namespace App\Filament\Resources\CompanyServiceResource\Pages;

use App\Filament\Resources\CompanyServiceResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCompanyService extends EditRecord
{
    protected static string $resource = CompanyServiceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
