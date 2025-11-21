<?php

namespace App\Filament\Resources\PlanComparisonResource\Pages;

use App\Filament\Resources\PlanComparisonResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPlanComparison extends EditRecord
{
    protected static string $resource = PlanComparisonResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
