<?php

namespace App\Filament\Resources\BusRoutes;

use App\Filament\Resources\BusRoutes\Pages\CreateBusRoute;
use App\Filament\Resources\BusRoutes\Pages\EditBusRoute;
use App\Filament\Resources\BusRoutes\Pages\ListBusRoutes;
use App\Filament\Resources\BusRoutes\Schemas\BusRouteForm;
use App\Filament\Resources\BusRoutes\Tables\BusRoutesTable;
use App\Models\BusRoute;
use BackedEnum;
use UnitEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class BusRouteResource extends Resource
{
    protected static ?string $model = BusRoute::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedTruck;

    protected static string|UnitEnum|null $navigationGroup = 'සංචාරක ගමනාන්ත & ප්‍රවාහනය';

    protected static ?string $navigationLabel = 'බස් කාලසටහන් (Bus Routes)';

    protected static ?string $modelLabel = 'බස් මාර්ගය';

    protected static ?string $pluralModelLabel = 'බස් කාලසටහන්';

    protected static ?int $navigationSort = 2;

    public static function form(Schema $schema): Schema
    {
        return BusRouteForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return BusRoutesTable::configure($table);
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
            'index' => ListBusRoutes::route('/'),
            'create' => CreateBusRoute::route('/create'),
            'edit' => EditBusRoute::route('/{record}/edit'),
        ];
    }
}
