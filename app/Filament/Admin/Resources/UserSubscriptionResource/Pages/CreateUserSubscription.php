<?php

namespace App\Filament\Admin\Resources\UserSubscriptionResource\Pages;

use App\Filament\Admin\Resources\UserSubscriptionResource;
use Filament\Resources\Pages\CreateRecord;

class CreateUserSubscription extends CreateRecord
{
    protected static string $resource = UserSubscriptionResource::class;
    
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
