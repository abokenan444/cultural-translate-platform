<?php

namespace App\Filament\Admin\Resources\SmtpSettingResource\Pages;

use App\Filament\Admin\Resources\SmtpSettingResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSmtpSetting extends EditRecord
{
    protected static string $resource = SmtpSettingResource::class;

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
