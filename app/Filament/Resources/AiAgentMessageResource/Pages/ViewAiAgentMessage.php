<?php

namespace App\Filament\Resources\AiAgentMessageResource\Pages;

use App\Filament\Resources\AiAgentMessageResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewAiAgentMessage extends ViewRecord
{
    protected static string $resource = AiAgentMessageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
