<?php

namespace App\Filament\Pages;

use App\Filament\Resources\Destinations\DestinationResource;
use App\Models\Destination;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\TextInputColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Collection;
use UnitEnum;

class HomepageDestinationsPage extends Page implements HasTable
{
    use InteractsWithTable;

    protected static string|UnitEnum|null $navigationGroup = 'Home Page';

    protected static ?string $navigationLabel = 'Homepage Destinations';

    protected static ?string $title = 'Featured Homepage Destinations';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedSparkles;

    protected static ?int $navigationSort = 2;

    protected static ?string $slug = 'homepage-destinations';

    protected string $view = 'filament.pages.homepage-destinations';

    public function table(Table $table): Table
    {
        return $table
            ->query(Destination::query()->orderBy('is_featured', 'desc')->orderBy('featured_priority', 'asc'))
            ->columns([
                ImageColumn::make('featured_image')
                    ->label('Thumbnail')
                    ->circular(),
                TextColumn::make('title_en')
                    ->label('Destination (English Title)')
                    ->description(fn (Destination $record): ?string => $record->title_si)
                    ->searchable()
                    ->sortable(),
                TextColumn::make('category')
                    ->label('Category')
                    ->badge()
                    ->color('info'),
                ToggleColumn::make('is_featured')
                    ->label('Show on Homepage')
                    ->afterStateUpdated(function ($record, $state) {
                        Notification::make()
                            ->title($state ? 'Destination added to homepage' : 'Destination removed from homepage')
                            ->success()
                            ->send();
                    }),
                TextInputColumn::make('featured_priority')
                    ->label('Sort Order (Priority)')
                    ->rules(['numeric', 'min:0'])
                    ->afterStateUpdated(function () {
                        Notification::make()
                            ->title('Sort order updated successfully')
                            ->success()
                            ->send();
                    }),
                TextColumn::make('views_count')
                    ->label('Views')
                    ->sortable(),
            ])
            ->recordActions([
                Action::make('edit')
                    ->label('Edit')
                    ->icon(Heroicon::OutlinedPencilSquare)
                    ->url(fn (Destination $record): string => DestinationResource::getUrl('edit', ['record' => $record])),
            ]);
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('preview_home')
                ->label('View Homepage')
                ->icon(Heroicon::OutlinedEye)
                ->color('gray')
                ->url('/')
                ->openUrlInNewTab(),
            Action::make('create_destination')
                ->label('Add New Destination')
                ->icon(Heroicon::OutlinedPlus)
                ->color('primary')
                ->url(DestinationResource::getUrl('create')),
        ];
    }

    public function getFeaturedDestinations(): Collection
    {
        return Destination::where('is_featured', true)
            ->where('is_active', true)
            ->orderBy('featured_priority', 'asc')
            ->take(6)
            ->get();
    }

    public function getFeaturedCount(): int
    {
        return Destination::where('is_featured', true)->where('is_active', true)->count();
    }

    public function getTotalCount(): int
    {
        return Destination::count();
    }
}
