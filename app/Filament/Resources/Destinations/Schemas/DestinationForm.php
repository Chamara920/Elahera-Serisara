<?php

namespace App\Filament\Resources\Destinations\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class DestinationForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Mandatory Information (English)')
                    ->description('English details are compulsory for all destinations.')
                    ->schema([
                        TextInput::make('title_en')
                            ->label('Title (English)')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('slug')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->helperText('URL identifier (e.g., elahera-anicut)'),
                        Textarea::make('short_description_en')
                            ->label('Short Description (English)')
                            ->required()
                            ->rows(3)
                            ->columnSpanFull(),
                        Textarea::make('description_en')
                            ->label('Full Description & History (English)')
                            ->required()
                            ->rows(6)
                            ->columnSpanFull(),
                    ])->columns(2),

                Section::make('Optional Translations (Sinhala)')
                    ->description('If left empty, users choosing Sinhala will be shown an indicator and the English text will be displayed.')
                    ->collapsible()
                    ->schema([
                        TextInput::make('title_si')
                            ->label('Title (Sinhala) - ගමනාන්තයේ නම')
                            ->maxLength(255),
                        TextInput::make('category_si')
                            ->label('Category (Sinhala) - වර්ගීකරණය'),
                        Textarea::make('short_description_si')
                            ->label('Short Description (Sinhala) - කෙටි විස්තරය')
                            ->rows(3)
                            ->columnSpanFull(),
                        Textarea::make('description_si')
                            ->label('Full Description (Sinhala) - පූර්ණ විස්තරය')
                            ->rows(6)
                            ->columnSpanFull(),
                    ])->columns(2),

                Section::make('Classification & Location')
                    ->schema([
                        Select::make('category')
                            ->options([
                                'Nature & Wildlife' => 'Nature & Wildlife (සොබාදහම සහ වනජීවී)',
                                'Waterfalls' => 'Waterfalls (දියඇලි)',
                                'Viewpoints' => 'Viewpoints (දර්ශනීය ස්ථාන)',
                                'Historical & Cultural' => 'Historical & Cultural (ඓතිහාසික හා සංස්කෘතික)',
                                'Canals & Lakes' => 'Canals & Lakes (ඇළ මාර්ග සහ ජලාශ)',
                            ])
                            ->default('Nature & Wildlife')
                            ->required(),
                        Select::make('route_zone')
                            ->options([
                                'Zone A - Bakamuna Route' => 'Zone A - Bakamuna Route',
                                'Zone B - Elahera Anicut' => 'Zone B - Elahera Anicut',
                                'Zone C - Wildlife & Angamedilla' => 'Zone C - Wildlife & Angamedilla',
                                'Zone D - Moragahakanda Viewpoints' => 'Zone D - Moragahakanda Viewpoints',
                            ])
                            ->default('Zone A - Bakamuna Route')
                            ->required(),
                        TextInput::make('distance_km')
                            ->label('Distance from Bakamuna/Main Town (KM)')
                            ->numeric()
                            ->default(0)
                            ->required(),
                        TextInput::make('travel_time_minutes')
                            ->label('Travel/Drive Time (Minutes)')
                            ->numeric()
                            ->default(15)
                            ->required(),
                        TextInput::make('stay_duration_minutes')
                            ->label('Recommended Stay Duration (Minutes)')
                            ->numeric()
                            ->default(60)
                            ->required(),
                        TextInput::make('latitude')
                            ->numeric()
                            ->default(7.9866)
                            ->required(),
                        TextInput::make('longitude')
                            ->numeric()
                            ->default(80.8144)
                            ->required(),
                    ])->columns(2),

                Section::make('Media & Photos')
                    ->schema([
                        FileUpload::make('featured_image')
                            ->label('Main Featured Image')
                            ->image()
                            ->directory('destinations')
                            ->imageEditor(),
                        FileUpload::make('gallery_images')
                            ->label('Gallery Images')
                            ->image()
                            ->multiple()
                            ->directory('destinations/gallery'),
                    ]),

                Section::make('Guide Highlights & Safety Information')
                    ->schema([
                        Textarea::make('safety_alerts_en')
                            ->label('Safety Alerts (EN) - ⚠️')
                            ->rows(2),
                        Textarea::make('safety_alerts_si')
                            ->label('Safety Alerts (SI) - ⚠️ ආරක්ෂක අවවාද')
                            ->rows(2),
                        Textarea::make('packing_list_en')
                            ->label('Packing List (EN) - 🎒')
                            ->rows(2),
                        Textarea::make('packing_list_si')
                            ->label('Packing List (SI) - 🎒 රැගෙන යා යුතු දෑ')
                            ->rows(2),
                        Textarea::make('dress_code_en')
                            ->label('Dress Code / Advice (EN) - 👗')
                            ->rows(2),
                        Textarea::make('dress_code_si')
                            ->label('Dress Code / Advice (SI) - 👗 ඇඳුම් පැළඳුම්')
                            ->rows(2),
                    ])->columns(2),

                Section::make('Public Transport Connections')
                    ->schema([
                        TextInput::make('nearest_bus_stop')
                            ->label('Nearest Bus Stop (ළඟම බස් නැවතුම)')
                            ->placeholder('e.g. Elahera Junction or Bakamuna Bus Stand'),
                        TextInput::make('bus_route_numbers')
                            ->label('Bus Route Numbers (මාර්ග අංක)')
                            ->placeholder('e.g. Route 422, 45/1'),
                    ])->columns(2),

                Section::make('Publishing & Visibility')
                    ->schema([
                        Toggle::make('is_featured')
                            ->label('Show on Homepage & Top Slider')
                            ->default(false),
                        TextInput::make('featured_priority')
                            ->numeric()
                            ->default(0)
                            ->label('Feature Priority (Higher comes first)'),
                        Toggle::make('is_active')
                            ->label('Active / Visible')
                            ->default(true),
                    ])->columns(3),
            ]);
    }
}
