<?php

namespace App\Filament\Resources\PlanComparisonResource\Pages;

use App\Filament\Resources\PlanComparisonResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewPlanComparison extends ViewRecord
{
    protected static string $resource = PlanComparisonResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
