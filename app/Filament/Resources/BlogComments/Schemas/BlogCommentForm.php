<?php

namespace App\Filament\Resources\BlogComments\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class BlogCommentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Comment Details')
                    ->schema([
                        Select::make('blog_post_id')
                            ->relationship('blogPost', 'title')
                            ->disabled()
                            ->label('Blog Post'),
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
