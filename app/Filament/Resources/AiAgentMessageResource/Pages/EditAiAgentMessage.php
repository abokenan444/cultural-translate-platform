<?php

namespace App\Filament\Resources\AiAgentMessageResource\Pages;

use App\Filament\Resources\AiAgentMessageResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAiAgentMessage extends EditRecord
{
    protected static string $resource = AiAgentMessageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
