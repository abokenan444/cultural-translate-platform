<?php

namespace App\Filament\Admin\Resources\PaymentSettingResource\Pages;

use App\Filament\Admin\Resources\PaymentSettingResource;
use Filament\Resources\Pages\CreateRecord;

class CreatePaymentSetting extends CreateRecord
{
    protected static string $resource = PaymentSettingResource::class;
    
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
