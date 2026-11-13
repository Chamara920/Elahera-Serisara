<?php

namespace App\Filament\Resources\Destinations\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class DestinationsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('featured_image')
                    ->label('Photo')
                    ->circular(),
                TextColumn::make('title_en')
                    ->label('Title (EN)')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('title_si')
                    ->label('Title (SI)')
                    ->searchable()
                    ->placeholder('No SI (Fallback)'),
                TextColumn::make('category')
                    ->badge()
                    ->sortable(),
                TextColumn::make('categories.name_en')
                    ->label('All Categories')
                    ->badge()
                    ->color('info')
                    ->separator(',')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('nearestBusStop.name_en')
                    ->label('Nearest Bus Halt')
                    ->badge()
                    ->color('gray')
                    ->placeholder(fn ($record) => $record->nearest_bus_stop ?: 'Not set')
                    ->toggleable(),
                TextColumn::make('route_zone')
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('distance_km')
                    ->suffix(' km')
                    ->sortable(),
                TextColumn::make('opening_time')
                    ->label('Visiting Hours')
                    ->formatStateUsing(fn ($record) => $record->getFormattedOpeningHours())
                    ->toggleable(),
                TextColumn::make('ticket_price_range')
                    ->label('Entrance Fee')
                    ->formatStateUsing(fn ($record) => $record->getFormattedTicketPrice())
                    ->toggleable(),
                IconColumn::make('is_featured')
                    ->label('Featured')
                    ->boolean()
                    ->sortable(),
                IconColumn::make('is_active')
                    ->label('Active')
                    ->boolean()
                    ->sortable(),
                TextColumn::make('views_count')
                    ->label('Views')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('category_id')
                    ->label('Category')
                    ->relationship('destinationCategory', 'name_en'),
                SelectFilter::make('route_zone')
                    ->options([
                        'Zone A - Bakamuna Route' => 'Zone A - Bakamuna Route',
                        'Zone B - Elahera Anicut' => 'Zone B - Elahera Anicut',
                        'Zone C - Wildlife & Angamedilla' => 'Zone C - Wildlife & Angamedilla',
                        'Zone D - Moragahakanda Viewpoints' => 'Zone D - Moragahakanda Viewpoints',
                    ]),
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
