<?php

namespace App\Filament\Resources\BusRoutes\Schemas;

use App\Models\BusStop;
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
                    ->description('General details about the route and origin departure time')
                    ->schema([
                        TextInput::make('route_number')
                            ->label('Route Number')
                            ->placeholder('e.g. 422 or 45/1')
                            ->required(),
                        Select::make('service_type')
                            ->label('Service Type')
                            ->options([
                                'Normal' => 'Normal',
                                'Semi-Luxury' => 'Semi-Luxury',
                                'Express' => 'Express',
                            ])
                            ->default('Normal')
                            ->required(),
                        TextInput::make('origin')
                            ->label('Origin')
                            ->placeholder('e.g. Matale, Polonnaruwa, Dambulla')
                            ->required(),
                        TextInput::make('destination')
                            ->label('Destination')
                            ->placeholder('e.g. Bakamuna, Habarana, Elahera')
                            ->required(),
                        TextInput::make('origin_departure_time')
                            ->label('Origin Departure Time')
                            ->placeholder('e.g. 05:30 AM')
                            ->required(),
                        TextInput::make('via_cities')
                            ->label('Via Cities')
                            ->placeholder('e.g. Via Naula, Elahera, Bakamuna'),
                        Textarea::make('remarks')
                            ->label('Admin Notice / Remarks')
                            ->placeholder('Notice regarding delays, festival schedules, or special remarks')
                            ->columnSpanFull(),
                        Toggle::make('is_active')
                            ->label('Active Route Schedule')
                            ->default(true),
                    ])->columns(2),

                Section::make('Halts & Timings Schedule')
                    ->description('Sequential list of major stops, arrival/departure timings, and associated tourist spots.')
                    ->schema([
                        Repeater::make('halts')
                            ->relationship('halts')
                            ->schema([
                                Select::make('bus_stop_id')
                                    ->label('Bus Stop (නැවතුම්පළ තෝරන්න)')
                                    ->relationship('busStop', 'name_en')
                                    ->searchable()
                                    ->preload()
                                    ->placeholder('Choose registered stop...')
                                    ->createOptionForm([
                                        TextInput::make('name_en')
                                            ->label('Stop Name (English)')
                                            ->required(),
                                        TextInput::make('name_si')
                                            ->label('Stop Name (Sinhala)'),
                                        TextInput::make('code')
                                            ->label('Stop Code (e.g. BAKAMUNA)')
                                            ->required(),
                                        TextInput::make('connecting_routes')
                                            ->label('Connecting Routes (e.g. 422, 45/1)'),
                                        Toggle::make('is_hub')
                                            ->label('Transfer Hub (මාරුවීම් සන්ධිස්ථානයක්ද)'),
                                    ])
                                    ->live()
                                    ->afterStateUpdated(function ($state, callable $set) {
                                        if ($state) {
                                            $stop = BusStop::find($state);
                                            if ($stop) {
                                                $set('stop_name', $stop->name_en);
                                            }
                                        }
                                    }),
                                TextInput::make('stop_name')
                                    ->label('Stop Name Display')
                                    ->placeholder('e.g. Elahera Junction, Bakamuna')
                                    ->required(),
                                TextInput::make('arrival_time')
                                    ->label('Arrival Time')
                                    ->placeholder('e.g. 06:15 AM'),
                                TextInput::make('departure_time')
                                    ->label('Departure Time')
                                    ->placeholder('e.g. 06:20 AM'),
                                Select::make('destination_id')
                                    ->label('Associated Destination')
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
