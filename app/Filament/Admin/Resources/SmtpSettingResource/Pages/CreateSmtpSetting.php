<?php

namespace App\Filament\Admin\Resources\SmtpSettingResource\Pages;

use App\Filament\Admin\Resources\SmtpSettingResource;
use Filament\Resources\Pages\CreateRecord;

class CreateSmtpSetting extends CreateRecord
{
    protected static string $resource = SmtpSettingResource::class;
    
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
