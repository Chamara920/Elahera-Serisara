<?php

namespace App\Filament\Resources\Destinations\RelationManagers;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;

class ReviewsRelationManager extends RelationManager
{
    protected static string $relationship = 'reviews';

    protected static ?string $title = 'User Reviews & Ratings';

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('comment')
            ->columns([
                TextColumn::make('user.name')
                    ->label('User')
                    ->searchable(),
                TextColumn::make('rating')
                    ->label('Rating')
                    ->badge()
                    ->color(fn ($state): string => match ((int) $state) {
                        5, 4 => 'success',
                        3 => 'warning',
                        default => 'danger',
                    })
                    ->formatStateUsing(fn ($state): string => str_repeat('★', (int) $state)." ({$state}/5)"),
                TextColumn::make('comment')
                    ->label('User Comment')
                    ->wrap(),
                ToggleColumn::make('is_approved')
                    ->label('Approved'),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->label('Posted Date'),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make()
                    ->label('Remove')
                    ->modalHeading('Delete Review & Rating')
                    ->modalDescription('Are you sure you want to remove this user comment and rating?'),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->modalHeading('Delete Selected Reviews'),
                ]),
            ]);
    }
}
