<?php

namespace App\Filament\Widgets;

use App\Models\DestinationReview;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class LatestDestinationReviewsWidget extends BaseWidget
{
    protected static bool $isLazy = false;

    protected static ?int $sort = 3;

    protected int|string|array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                DestinationReview::query()
                    ->with(['destination', 'user'])
                    ->latest()
            )
            ->heading('Recent Destination Reviews & Comments (සංචාරක ගමනාන්ත වලට ලැබුණු නව Comments)')
            ->description('Live ratings, tourist feedback and moderation controls for destination comments')
            ->columns([
                TextColumn::make('destination.title_en')
                    ->label('Destination')
                    ->searchable()
                    ->weight('bold')
                    ->color('primary')
                    ->url(fn ($record) => $record->destination_id ? route('filament.admin.resources.destinations.edit', ['record' => $record->destination_id]) : null),

                TextColumn::make('user.name')
                    ->label('Reviewer')
                    ->description(fn ($record): ?string => $record->user?->email)
                    ->searchable(),

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
                    ->label('Review Comment')
                    ->limit(90)
                    ->wrap()
                    ->searchable(),

                ToggleColumn::make('is_approved')
                    ->label('Approved & Live')
                    ->afterStateUpdated(function ($record, $state): void {
                        Notification::make()
                            ->title($state ? 'Review approved and published' : 'Review unapproved')
                            ->success()
                            ->send();
                    }),

                TextColumn::make('created_at')
                    ->label('Submitted')
                    ->since()
                    ->sortable(),
            ])
            ->actions([
                Action::make('approve')
                    ->label('Approve Now')
                    ->icon('heroicon-m-check-circle')
                    ->color('success')
                    ->visible(fn (DestinationReview $record): bool => ! $record->is_approved)
                    ->action(function (DestinationReview $record): void {
                        $record->update(['is_approved' => true]);
                        Notification::make()
                            ->title('Review approved successfully')
                            ->success()
                            ->send();
                    }),
            ])
            ->paginated([5, 10, 25]);
    }
}
