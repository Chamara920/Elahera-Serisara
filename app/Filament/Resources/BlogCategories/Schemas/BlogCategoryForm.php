<?php

namespace App\Filament\Resources\BlogCategories\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class BlogCategoryForm
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
                    ])->columns(2),
            ]);
    }
}
