<?php

namespace App\Filament\Resources\ForumTopics\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ForumTopicsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('category.name_en')
                    ->label('Category')
                    ->badge()
                    ->sortable(),
                TextColumn::make('title')
                    ->searchable()
                    ->sortable()
                    ->limit(40),
                TextColumn::make('user.name')
                    ->label('Author')
                    ->searchable(),
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
                TextColumn::make('slug')
                    ->searchable(),
                TextColumn::make('views_count')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
