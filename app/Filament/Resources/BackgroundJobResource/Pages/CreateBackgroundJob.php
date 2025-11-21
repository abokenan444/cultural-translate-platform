<?php

namespace App\Filament\Resources\BackgroundJobResource\Pages;

use App\Filament\Resources\BackgroundJobResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateBackgroundJob extends CreateRecord
{
    protected static string $resource = BackgroundJobResource::class;
}
