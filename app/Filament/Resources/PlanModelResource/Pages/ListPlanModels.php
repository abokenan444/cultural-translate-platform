<?php

namespace App\Filament\Resources\PlanModelResource\Pages;

use App\Filament\Resources\PlanModelResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPlanModels extends ListRecords
{
    protected static string $resource = PlanModelResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
