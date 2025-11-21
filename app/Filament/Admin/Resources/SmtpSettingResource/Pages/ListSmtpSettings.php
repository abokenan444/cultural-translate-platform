<?php

namespace App\Filament\Admin\Resources\SmtpSettingResource\Pages;

use App\Filament\Admin\Resources\SmtpSettingResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSmtpSettings extends ListRecords
{
    protected static string $resource = SmtpSettingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
