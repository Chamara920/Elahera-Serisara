<?php

namespace App\Filament\Resources\NewsCategories\Schemas;

use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class NewsCategoryForm
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
                            ->maxLength(191)
                            ->helperText('URL identifier (leave blank to generate automatically from English name)'),
                        TextInput::make('icon')
                            ->label('Icon / Heroicon / Emoji')
                            ->placeholder('e.g. heroicon-o-newspaper, 📰'),
                        Textarea::make('description')
                            ->label('Description')
                            ->rows(3)
                            ->columnSpanFull(),
                    ])->columns(2),

                Section::make('Ordering & Visibility')
                    ->schema([
                        TextInput::make('sort_order')
                            ->label('Sort Order')
                            ->numeric()
                            ->default(0),
                        Toggle::make('is_active')
                            ->label('Active / Visible')
                            ->default(true),
                    ])->columns(2),
            ]);
    }
}
