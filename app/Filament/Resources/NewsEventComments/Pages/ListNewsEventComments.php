<?php

namespace App\Filament\Resources\NewsEventComments\Pages;

use App\Filament\Resources\NewsEventComments\NewsEventCommentResource;
use Filament\Resources\Pages\ListRecords;

class ListNewsEventComments extends ListRecords
{
    protected static string $resource = NewsEventCommentResource::class;
}
