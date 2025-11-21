<?php

namespace App\Filament\Resources\SmtpSettingResource\Pages;

use App\Filament\Resources\SmtpSettingResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewSmtpSetting extends ViewRecord
{
    protected static string $resource = SmtpSettingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
