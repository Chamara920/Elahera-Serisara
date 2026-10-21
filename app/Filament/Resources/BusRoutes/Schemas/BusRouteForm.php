<?php

namespace App\Filament\Resources\BusRoutes\Schemas;

use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class BusRouteForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Bus Route Basic Information')
                    ->description('General details about the route and start time')
                    ->schema([
                        TextInput::make('route_number')
                            ->label('Route Number (මාර්ග අංකය)')
                            ->placeholder('e.g. 422 or 45/1')
                            ->required(),
                        Select::make('service_type')
                            ->label('Service Type (සේවා වර්ගය)')
                            ->options([
                                'Normal' => 'Normal (සාමාන්‍ය)',
                                'Semi-Luxury' => 'Semi-Luxury (අර්ධ සුඛෝපභෝගී)',
                                'Express' => 'Express (අධිවේගී)',
                            ])
                            ->default('Normal')
                            ->required(),
                        TextInput::make('origin')
                            ->label('Origin (ගමන ආරම්භ වන ස්ථානය)')
                            ->placeholder('e.g. Matale, Polonnaruwa, Dambulla')
                            ->required(),
                        TextInput::make('destination')
                            ->label('Destination (අවසාන ස්ථානය)')
                            ->placeholder('e.g. Bakamuna, Habarana, Elahera')
                            ->required(),
                        TextInput::make('origin_departure_time')
                            ->label('Origin Departure Time (ගමන ආරම්භක වේලාව)')
                            ->placeholder('e.g. 05:30 AM')
                            ->required(),
                        TextInput::make('via_cities')
                            ->label('Via Cities (ප්‍රධාන හරස් මාර්ග)')
                            ->placeholder('e.g. Via Naula, Elahera, Bakamuna'),
                        Textarea::make('remarks')
                            ->label('Admin Notice / Remarks (විශේෂ සටහන් සහ වෙනස්කම්)')
                            ->placeholder('Notice regarding delays, festival schedules, or special remarks')
                            ->columnSpanFull(),
                        Toggle::make('is_active')
                            ->label('Active Route Schedule')
                            ->default(true),
                    ])->columns(2),

                Section::make('Halts & Timings Schedule (නැවතුම්පළවල් සහ කාල අන්තරයන්)')
                    ->description('Sequential list of major stops, arrival/departure timings, and associated tourist spots.')
                    ->schema([
                        Repeater::make('halts')
                            ->relationship('halts')
                            ->schema([
                                TextInput::make('stop_name')
                                    ->label('Stop Name (නැවතුම්පළේ නම)')
                                    ->placeholder('e.g. Elahera Junction, Bakamuna')
                                    ->required(),
                                TextInput::make('arrival_time')
                                    ->label('Arrival Time (පැමිණෙන වේලාව)')
                                    ->placeholder('e.g. 06:15 AM'),
                                TextInput::make('departure_time')
                                    ->label('Departure Time (පිටත්වන වේලාව)')
                                    ->placeholder('e.g. 06:20 AM'),
                                Select::make('destination_id')
                                    ->label('Associated Destination (සමීප සංචාරක ස්ථානය)')
                                    ->relationship('destination', 'title_en')
                                    ->searchable()
                                    ->preload()
                                    ->placeholder('Select nearby destination if applicable'),
                                TextInput::make('nearest_spot_name')
                                    ->label('Or Spot Name')
                                    ->placeholder('e.g. Elahera Anicut Viewpoint'),
                                TextInput::make('stop_order')
                                    ->numeric()
                                    ->default(0)
                                    ->label('Stop Order'),
                                TextInput::make('remarks')
                                    ->placeholder('Platform or halt notes')
                                    ->columnSpanFull(),
                            ])
                            ->columns(3)
                            ->orderColumn('stop_order')
                            ->defaultItems(2)
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
