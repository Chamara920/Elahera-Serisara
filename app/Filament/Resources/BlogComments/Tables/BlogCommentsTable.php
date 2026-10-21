<?php

namespace App\Filament\Resources\BlogComments\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class BlogCommentsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('blogPost.title')
                    ->label('Blog Post')
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
                SelectFilter::make('blog_post_id')
                    ->relationship('blogPost', 'title')
                    ->label('Blog Post'),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make()
                    ->label('Delete')
                    ->modalHeading('Delete Blog Comment')
                    ->modalDescription('Are you sure you want to delete this user comment?'),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->modalHeading('Delete Selected Blog Comments'),
                ]),
            ]);
    }
}
