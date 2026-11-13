<?php

namespace App\Filament\Resources\BusStops\Schemas;

use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class BusStopForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Bus Stop Identification (නැවතුම්පළ මූලික තොරතුරු)')
                    ->description('Names, unique code, connecting routes, and transfer hub flag.')
                    ->schema([
                        TextInput::make('name_en')
                            ->label('Stop Name (English)')
                            ->placeholder('e.g., Bakamuna Town Hub or Elahera Junction')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('name_si')
                            ->label('Stop Name (Sinhala)')
                            ->placeholder('e.g., බකමූණ නගර මධ්‍යස්ථානය හෝ ඇලහැර හන්දිය')
                            ->maxLength(255),
                        TextInput::make('code')
                            ->label('Unique Stop Code')
                            ->placeholder('e.g., BAKAMUNA, POLATHUWADIYA, ELAHERA_JNC')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->helperText('Uppercase unique identifier code used in schedules and GPS routing.'),
                        TextInput::make('connecting_routes')
                            ->label('Connecting Bus Routes (සම්බන්ධ වන බස් මාර්ග අංක)')
                            ->placeholder('e.g., Route 422, Route 45/1, Route 582')
                            ->helperText('List route numbers passing through or originating here for passenger transfers.'),
                        Toggle::make('is_hub')
                            ->label('Transfer Hub (බස් රථ අතර මාරුවීමේ ප්‍රධාන සන්ධිස්ථානයක්)')
                            ->helperText('Enable if passengers can switch between different connecting bus routes at this halt (e.g. Bakamuna, Polathuwadiya).')
                            ->default(false)
                            ->columnSpanFull(),
                    ])->columns(2),

                Section::make('GPS Coordinates & Geographic Location (භූගෝලීය පිහිටීම)')
                    ->description('Exact GPS coordinates used for interactive transit maps and travel route planning.')
                    ->schema([
                        TextInput::make('latitude')
                            ->label('Latitude')
                            ->numeric()
                            ->default(7.9300)
                            ->required()
                            ->helperText('Geographic latitude (e.g., 7.9300)'),
                        TextInput::make('longitude')
                            ->label('Longitude')
                            ->numeric()
                            ->default(80.8200)
                            ->required()
                            ->helperText('Geographic longitude (e.g., 80.8200)'),
                        Textarea::make('location_description_en')
                            ->label('Location Description / Landmarks (English)')
                            ->placeholder('e.g., Near Bakamuna Clock Tower and weekly fairgrounds')
                            ->rows(2),
                        Textarea::make('location_description_si')
                            ->label('Location Description / Landmarks (Sinhala)')
                            ->placeholder('e.g., ඔරලෝසු කණුව අසල, ප්‍රධාන වෙළඳපොළ ඉදිරිපිට')
                            ->rows(2),
                    ])->columns(2),
            ]);
    }
}
