<?php

namespace App\Filament\Resources\ForumCategories\Schemas;

use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ForumCategoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Category Information')
                    ->schema([
                        TextInput::make('name_en')
                            ->label('Category Name (English)')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('name_si')
                            ->label('Category Name (Sinhala)')
                            ->maxLength(255),
                        TextInput::make('slug')
                            ->label('URL Slug')
                            ->unique(ignoreRecord: true)
                            ->helperText('URL identifier (leave blank to generate automatically from English name)'),
                        TextInput::make('icon')
                            ->label('Icon / Heroicon Class')
                            ->default('chat-bubble-left-right')
                            ->placeholder('chat-bubble-left-right'),
                        Textarea::make('description')
                            ->label('Description')
                            ->rows(3)
                            ->columnSpanFull(),
                    ])->columns(2),
            ]);
    }
}
