<?php

namespace App\Filament\Resources\BusRoutes\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class BusRoutesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('route_number')
                    ->label('Route #')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                TextColumn::make('origin')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('destination')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('service_type')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Express' => 'danger',
                        'Semi-Luxury' => 'warning',
                        default => 'info',
                    }),
                TextColumn::make('origin_departure_time')
                    ->label('Departure')
                    ->sortable(),
                TextColumn::make('via_cities')
                    ->label('Via')
                    ->limit(30),
                TextColumn::make('halts_count')
                    ->counts('halts')
                    ->label('Halts')
                    ->badge(),
                IconColumn::make('is_active')
                    ->boolean()
                    ->label('Active'),
            ])
            ->filters([
                //
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
