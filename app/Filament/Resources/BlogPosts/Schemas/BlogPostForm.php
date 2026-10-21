<?php

namespace App\Filament\Resources\BlogPosts\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class BlogPostForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Blog Article')
                    ->schema([
                        Select::make('user_id')
                            ->relationship('user', 'name')
                            ->required()
                            ->label('Author / Community Member'),
                        Select::make('category_id')
                            ->relationship('category', 'name_en')
                            ->label('Category'),
                        TextInput::make('title')
                            ->required()
                            ->maxLength(255)
                            ->columnSpanFull(),
                        TextInput::make('slug')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->columnSpanFull(),
                        Textarea::make('content')
                            ->required()
                            ->rows(10)
                            ->columnSpanFull(),
                        FileUpload::make('featured_image')
                            ->image()
                            ->directory('blog')
                            ->columnSpanFull(),
                    ])->columns(2),

                Section::make('Moderation & Status')
                    ->description('Community user blog posts must be approved by admin before appearing on public frontend.')
                    ->schema([
                        Toggle::make('is_approved')
                            ->label('Approved for Publication')
                            ->helperText('Enable this to publish the community article on the website')
                            ->default(false),
                    ]),
            ]);
    }
}
