<?php

namespace App\Filament\Admin\Resources\PaymentSettingResource\Pages;

use App\Filament\Admin\Resources\PaymentSettingResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPaymentSetting extends EditRecord
{
    protected static string $resource = PaymentSettingResource::class;

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
