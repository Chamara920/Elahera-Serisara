<?php

namespace App\Filament\Resources\Destinations;

use App\Filament\Resources\Destinations\Pages\CreateDestination;
use App\Filament\Resources\Destinations\Pages\EditDestination;
use App\Filament\Resources\Destinations\Pages\ListDestinations;
use App\Filament\Resources\Destinations\Schemas\DestinationForm;
use App\Filament\Resources\Destinations\Tables\DestinationsTable;
use App\Models\Destination;
use BackedEnum;
use UnitEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class DestinationResource extends Resource
{
    protected static ?string $model = Destination::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedMapPin;

    protected static string|UnitEnum|null $navigationGroup = 'Destinations & Transport';

    protected static ?string $navigationLabel = 'Destinations';

    protected static ?string $modelLabel = 'Destination';

    protected static ?string $pluralModelLabel = 'Destinations';

    protected static ?int $navigationSort = 1;

    public static function form(Schema $schema): Schema
    {
        return DestinationForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return DestinationsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListDestinations::route('/'),
            'create' => CreateDestination::route('/create'),
            'edit' => EditDestination::route('/{record}/edit'),
        ];
    }
}
