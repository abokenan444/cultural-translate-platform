<?php

namespace App\Filament\Admin\Resources\CompanyMemberResource\Pages;

use App\Filament\Admin\Resources\CompanyMemberResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCompanyMember extends EditRecord
{
    protected static string $resource = CompanyMemberResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
    
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
