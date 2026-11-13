<?php

namespace App\Filament\Widgets;

use App\Models\BlogPost;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class LatestBlogPostsWidget extends BaseWidget
{
    protected static bool $isLazy = false;

    protected static ?int $sort = 4;

    protected int|string|array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                BlogPost::query()
                    ->with(['user', 'category'])
                    ->latest()
            )
            ->heading('Recent Travel Blogs & Articles (නව බ්ලොග් සටහන්)')
            ->description('Community stories, local guides and approval management')
            ->columns([
                ImageColumn::make('featured_image')
                    ->label('Cover')
                    ->square(),

                TextColumn::make('title')
                    ->label('Article Title')
                    ->searchable()
                    ->weight('bold')
                    ->limit(45)
                    ->url(fn ($record) => route('filament.admin.resources.blog-posts.edit', ['record' => $record->id])),

                TextColumn::make('user.name')
                    ->label('Author')
                    ->description(fn ($record): ?string => $record->user?->email)
                    ->searchable(),

                TextColumn::make('category.name_en')
                    ->label('Category')
                    ->badge(),

                ToggleColumn::make('is_approved')
                    ->label('Approved & Live')
                    ->afterStateUpdated(function ($record, $state): void {
                        Notification::make()
                            ->title($state ? 'Blog post approved and published' : 'Blog post unapproved')
                            ->success()
                            ->send();
                    }),

                TextColumn::make('views_count')
                    ->label('Views')
                    ->numeric()
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label('Submitted')
                    ->since()
                    ->sortable(),
            ])
            ->actions([
                Action::make('edit')
                    ->label('Edit / Review')
                    ->icon('heroicon-m-pencil-square')
                    ->url(fn ($record) => route('filament.admin.resources.blog-posts.edit', ['record' => $record->id])),
            ])
            ->paginated([5, 10]);
    }
}
