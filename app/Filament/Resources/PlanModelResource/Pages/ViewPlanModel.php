<?php

namespace App\Filament\Resources\PlanModelResource\Pages;

use App\Filament\Resources\PlanModelResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewPlanModel extends ViewRecord
{
    protected static string $resource = PlanModelResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
