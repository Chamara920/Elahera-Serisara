<?php

namespace App\Filament\Resources\NewsEvents\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class NewsEventsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('featured_image')
                    ->label('Image')
                    ->circular(),
                TextColumn::make('title')
                    ->searchable()
                    ->sortable()
                    ->limit(40),
                TextColumn::make('type')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'event' => 'warning',
                        default => 'info',
                    }),
                TextColumn::make('category.name_en')
                    ->label('Category')
                    ->badge()
                    ->placeholder('-'),
                TextColumn::make('language')
                    ->label('Language')
                    ->badge()
                    ->formatStateUsing(fn ($state) => match ($state) {
                        'all' => 'All (Bilingual)',
                        'en' => 'English',
                        'si' => 'Sinhala',
                        default => $state ?? 'All',
                    })
                    ->color(fn ($state) => match ($state) {
                        'si' => 'warning',
                        'en' => 'info',
                        default => 'success',
                    }),
                TextColumn::make('user.name')
                    ->label('Submitted By')
                    ->searchable(),
                TextColumn::make('event_date')
                    ->date()
                    ->sortable(),
                IconColumn::make('is_approved')
                    ->label('Approved')
                    ->boolean()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('type')
                    ->options([
                        'news' => 'News',
                        'event' => 'Event',
                    ]),
                SelectFilter::make('is_approved')
                    ->label('Approval')
                    ->options([
                        '1' => 'Approved',
                        '0' => 'Pending Approval',
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
