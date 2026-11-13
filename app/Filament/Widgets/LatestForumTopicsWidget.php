<?php

namespace App\Filament\Widgets;

use App\Models\ForumTopic;
use Filament\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class LatestForumTopicsWidget extends BaseWidget
{
    protected static bool $isLazy = false;

    protected static ?int $sort = 6;

    protected int|string|array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                ForumTopic::query()
                    ->with(['user', 'category'])
                    ->withCount('replies')
                    ->latest()
            )
            ->heading('Recent Forum Community Discussions (නව Forum සාකච්ඡා)')
            ->description('Traveler questions, road inquiries, camping spots, and local guidance')
            ->columns([
                TextColumn::make('title')
                    ->label('Discussion Topic')
                    ->searchable()
                    ->weight('bold')
                    ->limit(55)
                    ->url(fn ($record) => route('filament.admin.resources.forum-topics.edit', ['record' => $record->id])),

                TextColumn::make('user.name')
                    ->label('Asked By')
                    ->description(fn ($record): ?string => $record->user?->email)
                    ->searchable(),

                TextColumn::make('category.name_en')
                    ->label('Category')
                    ->badge(),

                TextColumn::make('replies_count')
                    ->label('Replies')
                    ->badge()
                    ->color(fn ($state): string => (int) $state > 0 ? 'success' : 'gray')
                    ->sortable(),

                TextColumn::make('views_count')
                    ->label('Views')
                    ->numeric()
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label('Started')
                    ->since()
                    ->sortable(),
            ])
            ->actions([
                Action::make('edit')
                    ->label('View / Manage')
                    ->icon('heroicon-m-chat-bubble-bottom-center-text')
                    ->url(fn ($record) => route('filament.admin.resources.forum-topics.edit', ['record' => $record->id])),
            ])
            ->paginated([5, 10]);
    }
}
