<?php

namespace App\Filament\Widgets;

use App\Models\NewsEvent;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class LatestNewsEventsWidget extends BaseWidget
{
    protected static bool $isLazy = false;

    protected static ?int $sort = 5;

    protected int|string|array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                NewsEvent::query()
                    ->with(['user', 'category'])
                    ->latest()
            )
            ->heading('Recent News & Upcoming Events (නව පුවත් සහ සිදුවීම්)')
            ->description('Regional alerts, festivals, meetings and event scheduling')
            ->columns([
                TextColumn::make('title')
                    ->label('Title')
                    ->searchable()
                    ->weight('bold')
                    ->limit(45)
                    ->url(fn ($record) => route('filament.admin.resources.news-events.edit', ['record' => $record->id])),

                TextColumn::make('type')
                    ->label('Type')
                    ->badge()
                    ->formatStateUsing(fn ($state) => match ($state) {
                        'event' => 'Public Event',
                        'news' => 'News Notice',
                        default => ucfirst((string) $state),
                    })
                    ->color(fn ($state) => match ($state) {
                        'event' => 'warning',
                        default => 'info',
                    }),

                TextColumn::make('event_date')
                    ->label('Event Date')
                    ->date()
                    ->placeholder('N/A (Notice)')
                    ->sortable(),

                TextColumn::make('location')
                    ->label('Location / Venue')
                    ->placeholder('Regional')
                    ->limit(30),

                TextColumn::make('user.name')
                    ->label('Submitted By')
                    ->searchable(),

                ToggleColumn::make('is_approved')
                    ->label('Approved & Live')
                    ->afterStateUpdated(function ($record, $state): void {
                        Notification::make()
                            ->title($state ? 'News/Event approved and published' : 'News/Event unapproved')
                            ->success()
                            ->send();
                    }),

                TextColumn::make('created_at')
                    ->label('Posted')
                    ->since()
                    ->sortable(),
            ])
            ->actions([
                Action::make('edit')
                    ->label('Edit / Review')
                    ->icon('heroicon-m-pencil-square')
                    ->url(fn ($record) => route('filament.admin.resources.news-events.edit', ['record' => $record->id])),
            ])
            ->paginated([5, 10]);
    }
}
