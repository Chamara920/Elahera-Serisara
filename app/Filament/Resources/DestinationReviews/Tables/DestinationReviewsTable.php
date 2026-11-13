<?php

namespace App\Filament\Resources\DestinationReviews\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class DestinationReviewsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('destination.title_en')
                    ->label('Destination')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('user.name')
                    ->label('User')
                    ->description(fn ($record): ?string => $record->user?->email)
                    ->searchable()
                    ->sortable(),
                TextColumn::make('rating')
                    ->label('Rating')
                    ->badge()
                    ->color(fn ($state): string => match ((int) $state) {
                        5, 4 => 'success',
                        3 => 'warning',
                        default => 'danger',
                    })
                    ->formatStateUsing(fn ($state): string => str_repeat('★', (int) $state)." ({$state}/5)")
                    ->sortable(),
                TextColumn::make('comment')
                    ->label('User Comment')
                    ->limit(70)
                    ->wrap()
                    ->searchable(),
                ToggleColumn::make('is_approved')
                    ->label('Approved'),
                TextColumn::make('created_at')
                    ->label('Date')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('rating')
                    ->options([
                        5 => '5 Stars',
                        4 => '4 Stars',
                        3 => '3 Stars',
                        2 => '2 Stars',
                        1 => '1 Star',
                    ]),
                SelectFilter::make('destination_id')
                    ->relationship('destination', 'title_en')
                    ->label('Destination'),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make()
                    ->label('Delete')
                    ->modalHeading('Delete User Review & Rating')
                    ->modalDescription('Are you sure you want to permanently delete this user comment and rating?'),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->modalHeading('Delete Selected Reviews & Ratings'),
                ]),
            ]);
    }
}
