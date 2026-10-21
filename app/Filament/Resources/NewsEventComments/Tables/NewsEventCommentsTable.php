<?php

namespace App\Filament\Resources\NewsEventComments\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class NewsEventCommentsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('newsEvent.title')
                    ->label('News / Event')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('user.name')
                    ->label('User')
                    ->description(fn ($record): ?string => $record->user?->email)
                    ->searchable()
                    ->sortable(),
                TextColumn::make('comment')
                    ->label('Comment')
                    ->limit(70)
                    ->wrap()
                    ->searchable(),
                TextColumn::make('created_at')
                    ->label('Date')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('news_event_id')
                    ->relationship('newsEvent', 'title')
                    ->label('News / Event'),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make()
                    ->label('Delete')
                    ->modalHeading('Delete News / Event Comment')
                    ->modalDescription('Are you sure you want to delete this user comment?'),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->modalHeading('Delete Selected Comments'),
                ]),
            ]);
    }
}
