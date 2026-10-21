<?php

namespace App\Filament\Resources\NewsEventComments\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class NewsEventCommentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Comment Details')
                    ->schema([
                        Select::make('news_event_id')
                            ->relationship('newsEvent', 'title')
                            ->disabled()
                            ->label('News / Event'),
                        Select::make('user_id')
                            ->relationship('user', 'name')
                            ->disabled()
                            ->label('User / Author'),
                        Textarea::make('comment')
                            ->label('User Comment')
                            ->rows(4)
                            ->columnSpanFull()
                            ->required(),
                    ])->columns(2),
            ]);
    }
}
