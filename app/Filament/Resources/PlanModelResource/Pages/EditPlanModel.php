<?php

namespace App\Filament\Resources\PlanModelResource\Pages;

use App\Filament\Resources\PlanModelResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPlanModel extends EditRecord
{
    protected static string $resource = PlanModelResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
