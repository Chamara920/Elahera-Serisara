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
                TextColumn::make('route_zone')
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('distance_km')
                    ->suffix(' km')
                    ->sortable(),
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
                SelectFilter::make('category')
                    ->options([
                        'Nature & Wildlife' => 'Nature & Wildlife',
                        'Waterfalls' => 'Waterfalls',
                        'Viewpoints' => 'Viewpoints',
                        'Historical & Cultural' => 'Historical & Cultural',
                        'Canals & Lakes' => 'Canals & Lakes',
                    ]),
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
