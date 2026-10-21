<?php

namespace App\Filament\Resources\NewsEventComments\Pages;

use App\Filament\Resources\NewsEventComments\NewsEventCommentResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditNewsEventComment extends EditRecord
{
    protected static string $resource = NewsEventCommentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make()
                ->label('Delete Comment'),
        ];
    }
}
