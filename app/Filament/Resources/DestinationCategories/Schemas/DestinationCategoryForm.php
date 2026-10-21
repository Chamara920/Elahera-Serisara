<?php

namespace App\Filament\Resources\DestinationCategories\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class DestinationCategoryForm
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
                            ->helperText('URL identifier (leave blank to generate from English name)'),
                        TextInput::make('icon')
                            ->label('Icon / Heroicon Class')
                            ->default('heroicon-o-tag')
                            ->placeholder('heroicon-o-tag'),
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
