<?php

namespace App\Filament\Admin\Resources\CompanyMemberResource\Pages;

use App\Filament\Admin\Resources\CompanyMemberResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCompanyMembers extends ListRecords
{
    protected static string $resource = CompanyMemberResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
