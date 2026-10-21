<?php

namespace App\Filament\Resources\HeroSliders\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class HeroSliderForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Slider Content')
                    ->schema([
                        TextInput::make('title_en')
                            ->label('Title (English)')
                            ->required(),
                        TextInput::make('title_si')
                            ->label('Title (Sinhala)'),
                        TextInput::make('subtitle_en')
                            ->label('Subtitle (English)'),
                        TextInput::make('subtitle_si')
                            ->label('Subtitle (Sinhala)'),
                        FileUpload::make('image')
                            ->label('Slider Background Image')
                            ->image()
                            ->directory('sliders')
                            ->columnSpanFull(),
                    ])->columns(2),

                Section::make('Buttons & Action Links')
                    ->schema([
                        TextInput::make('button1_text_en')
                            ->default('Smart Trip Planner'),
                        TextInput::make('button1_text_si')
                            ->default('ස්මාර්ට් චාරිකා සැලසුම්කරු'),
                        TextInput::make('button1_url')
                            ->default('/trip-planner'),
                        TextInput::make('button2_text_en')
                            ->default('Explore Destinations'),
                        TextInput::make('button2_text_si')
                            ->default('ගමනාන්ත ගවේෂණය'),
                        TextInput::make('button2_url')
                            ->default('/destinations'),
                    ])->columns(3),

                Section::make('Order & Visibility')
                    ->schema([
                        TextInput::make('sort_order')
                            ->numeric()
                            ->default(0),
                        Toggle::make('is_active')
                            ->default(true),
                    ])->columns(2),
            ]);
    }
}
