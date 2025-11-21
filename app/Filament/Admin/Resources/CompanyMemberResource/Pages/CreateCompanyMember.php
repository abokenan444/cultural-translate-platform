<?php

namespace App\Filament\Admin\Resources\CompanyMemberResource\Pages;

use App\Filament\Admin\Resources\CompanyMemberResource;
use Filament\Resources\Pages\CreateRecord;

class CreateCompanyMember extends CreateRecord
{
    protected static string $resource = CompanyMemberResource::class;
    
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
