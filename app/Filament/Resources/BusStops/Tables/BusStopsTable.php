<?php

namespace App\Filament\Resources\BusStops\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class BusStopsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('code')
                    ->label('Stop Code')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                TextColumn::make('name_en')
                    ->label('Name (EN)')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('name_si')
                    ->label('Name (SI)')
                    ->searchable()
                    ->placeholder('No SI'),
                TextColumn::make('connecting_routes')
                    ->label('Connecting Routes')
                    ->searchable()
                    ->badge()
                    ->color('info')
                    ->wrap(),
                IconColumn::make('is_hub')
                    ->label('Transfer Hub')
                    ->boolean()
                    ->sortable(),
                TextColumn::make('latitude')
                    ->label('Lat')
                    ->numeric(decimalPlaces: 4)
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('longitude')
                    ->label('Lng')
                    ->numeric(decimalPlaces: 4)
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('destinations_count')
                    ->counts('destinations')
                    ->label('Nearby Spots')
                    ->badge()
                    ->color('success')
                    ->sortable(),
                TextColumn::make('route_halts_count')
                    ->counts('routeHalts')
                    ->label('Route Halts')
                    ->badge()
                    ->sortable(),
            ])
            ->filters([
                TernaryFilter::make('is_hub')
                    ->label('Transit Hub Status')
                    ->placeholder('All Stops')
                    ->trueLabel('Transfer Hubs Only (මාරුවීම් සන්ධිස්ථාන)')
                    ->falseLabel('Regular Stops Only (සාමාන්‍ය නැවතුම්)'),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
