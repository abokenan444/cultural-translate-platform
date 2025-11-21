<?php

namespace App\Filament\Resources\PlanComparisonResource\Pages;

use App\Filament\Resources\PlanComparisonResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPlanComparisons extends ListRecords
{
    protected static string $resource = PlanComparisonResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
