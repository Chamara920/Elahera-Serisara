<?php

namespace App\Filament\Resources\Destinations\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class DestinationForm
{
    public static function configure(Schema $schema): Schema
    {
        $months = [
            'January' => 'January',
            'February' => 'February',
            'March' => 'March',
            'April' => 'April',
            'May' => 'May',
            'June' => 'June',
            'July' => 'July',
            'August' => 'August',
            'September' => 'September',
            'October' => 'October',
            'November' => 'November',
            'December' => 'December',
        ];

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
                            ->label('Slug / URL Identifier')
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
                            ->label('Title (Sinhala Translation)')
                            ->maxLength(255),
                        TextInput::make('category_si')
                            ->label('Category (Sinhala Translation)'),
                        Textarea::make('short_description_si')
                            ->label('Short Description (Sinhala Translation)')
                            ->rows(3)
                            ->columnSpanFull(),
                        Textarea::make('description_si')
                            ->label('Full Description (Sinhala Translation)')
                            ->rows(6)
                            ->columnSpanFull(),
                    ])->columns(2),

                Section::make('Classification & Location')
                    ->schema([
                        Select::make('category_id')
                            ->label('Destination Category')
                            ->relationship('destinationCategory', 'name_en')
                            ->searchable()
                            ->preload()
                            ->live()
                            ->afterStateUpdated(function ($state, callable $set) {
                                $cat = \App\Models\DestinationCategory::find($state);
                                if ($cat) {
                                    $set('category', $cat->name_en);
                                    $set('category_si', $cat->name_si);
                                }
                            }),
                        Select::make('category')
                            ->label('Category Code / Label')
                            ->options([
                                'Nature & Wildlife' => 'Nature & Wildlife',
                                'Waterfalls' => 'Waterfalls',
                                'Viewpoints' => 'Viewpoints',
                                'Historical & Cultural' => 'Historical & Cultural',
                                'Canals & Lakes' => 'Canals & Lakes',
                            ])
                            ->default('Nature & Wildlife')
                            ->required(),
                        Select::make('route_zone')
                            ->label('Route Zone')
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
                            ->label('Travel / Drive Time (Minutes)')
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

                Section::make('Visiting Hours & Weekly Schedule')
                    ->description('Specify operational hours and weekly closed days for traveler planning.')
                    ->schema([
                        TimePicker::make('opening_time')
                            ->label('Opening Time')
                            ->seconds(false)
                            ->default('06:00')
                            ->placeholder('06:00'),
                        TimePicker::make('closing_time')
                            ->label('Closing Time')
                            ->seconds(false)
                            ->default('18:00')
                            ->placeholder('18:00'),
                        Toggle::make('is_always_open')
                            ->label('Open 24/7 (Always Open)')
                            ->default(false)
                            ->columnSpanFull(),
                        Select::make('closed_days')
                            ->label('Closed Days / Weekly Holidays')
                            ->multiple()
                            ->options([
                                'Monday' => 'Monday',
                                'Tuesday' => 'Tuesday',
                                'Wednesday' => 'Wednesday',
                                'Thursday' => 'Thursday',
                                'Friday' => 'Friday',
                                'Saturday' => 'Saturday',
                                'Sunday' => 'Sunday',
                                'Full Moon Poya Days' => 'Full Moon Poya Days',
                                'Public Holidays' => 'Public Holidays',
                            ])
                            ->placeholder('Select closed days (if any)'),
                        TextInput::make('closure_notes')
                            ->label('Closure Notes / Specific Conditions')
                            ->placeholder('e.g., Closed on Poya Days, or during severe spillway flooding alerts'),
                    ])->columns(2),

                Section::make('12-Month Seasonal Suitability')
                    ->description('Categorize the 12 calendar months into Best, Moderate, and Not Recommended periods for this destination.')
                    ->schema([
                        Select::make('best_months')
                            ->label('Best Months to Visit (Peak / Recommended Season)')
                            ->multiple()
                            ->options($months)
                            ->placeholder('Select prime visiting months')
                            ->helperText('Ideal weather, calm waters, or prime wildlife viewing.'),
                        Select::make('moderate_months')
                            ->label('Moderate Months to Visit (Fair Season)')
                            ->multiple()
                            ->options($months)
                            ->placeholder('Select fair weather months')
                            ->helperText('Acceptable for travel with moderate precautions.'),
                        Select::make('not_recommended_months')
                            ->label('Not Recommended Months (Adverse Weather / Off-Season)')
                            ->multiple()
                            ->options($months)
                            ->placeholder('Select not recommended months')
                            ->helperText('Heavy monsoon rains, high water levels, or restricted access.'),
                    ])->columns(3),

                Section::make('Ticket Pricing & Entrance Fees')
                    ->description('Set ticket price range and estimated entrance fees for trip budget calculations.')
                    ->schema([
                        TextInput::make('ticket_price_range')
                            ->label('Ticket Price Range / Summary')
                            ->placeholder('e.g., Free Entry, or LKR 100 - 500, or LKR 1,500 (Foreigners)')
                            ->helperText('Human-readable display text shown on cards and details.')
                            ->columnSpanFull(),
                        TextInput::make('ticket_price_min')
                            ->label('Minimum Ticket Price (LKR)')
                            ->numeric()
                            ->prefix('LKR')
                            ->default(0)
                            ->helperText('Set 0 for free entry.'),
                        TextInput::make('ticket_price_max')
                            ->label('Maximum Ticket Price (LKR)')
                            ->numeric()
                            ->prefix('LKR')
                            ->default(0)
                            ->helperText('Used for total budget estimates in the Trip Planner.'),
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
                            ->label('Safety Alerts (English)')
                            ->rows(2),
                        Textarea::make('safety_alerts_si')
                            ->label('Safety Alerts (Sinhala Translation)')
                            ->rows(2),
                        Textarea::make('packing_list_en')
                            ->label('Packing List (English)')
                            ->rows(2),
                        Textarea::make('packing_list_si')
                            ->label('Packing List (Sinhala Translation)')
                            ->rows(2),
                        Textarea::make('dress_code_en')
                            ->label('Dress Code / Advice (English)')
                            ->rows(2),
                        Textarea::make('dress_code_si')
                            ->label('Dress Code / Advice (Sinhala Translation)')
                            ->rows(2),
                    ])->columns(2),

                Section::make('Public Transport Connections')
                    ->schema([
                        TextInput::make('nearest_bus_stop')
                            ->label('Nearest Bus Stop')
                            ->placeholder('e.g., Elahera Junction or Bakamuna Bus Stand'),
                        TextInput::make('bus_route_numbers')
                            ->label('Bus Route Numbers')
                            ->placeholder('e.g., Route 422, 45/1'),
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
