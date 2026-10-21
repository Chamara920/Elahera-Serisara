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
use UnitEnum;

class HomepageDestinationsPage extends Page implements HasTable
{
    use InteractsWithTable;

    protected static string|UnitEnum|null $navigationGroup = 'මුල් පිටුව';

    protected static ?string $navigationLabel = 'මුල් පිටුවේ දර්ශනය විය යුතු සංචාරක ගමනාන්ත';

    protected static ?string $title = 'මුල් පිටුවේ දර්ශනය වන සංචාරක ගමනාන්ත';

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
                    ->label('රූපය')
                    ->circular(),
                TextColumn::make('title_en')
                    ->label('ගමනාන්තය (English Title)')
                    ->description(fn (Destination $record): ?string => $record->title_si)
                    ->searchable()
                    ->sortable(),
                TextColumn::make('category')
                    ->label('වර්ගය (Category)')
                    ->badge()
                    ->color('info'),
                ToggleColumn::make('is_featured')
                    ->label('මුල් පිටුවේ පෙන්වන්න (Show on Homepage)')
                    ->afterStateUpdated(function ($record, $state) {
                        Notification::make()
                            ->title($state ? 'ගමනාන්තය මුල් පිටුවට එක් කරන ලදී' : 'ගමනාන්තය මුල් පිටුවෙන් ඉවත් කරන ලදී')
                            ->success()
                            ->send();
                    }),
                TextInputColumn::make('featured_priority')
                    ->label('අනුපිළිවෙල (Sort Order)')
                    ->rules(['numeric', 'min:0'])
                    ->afterStateUpdated(function () {
                        Notification::make()
                            ->title('අනුපිළිවෙල සාර්ථකව යාවත්කාලීන විය')
                            ->success()
                            ->send();
                    }),
                TextColumn::make('views_count')
                    ->label('නැරඹුම් (Views)')
                    ->sortable(),
            ])
            ->recordActions([
                \Filament\Actions\Action::make('edit')
                    ->label('සංස්කරණය')
                    ->icon(Heroicon::OutlinedPencilSquare)
                    ->url(fn (Destination $record): string => DestinationResource::getUrl('edit', ['record' => $record])),
            ]);
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('preview_home')
                ->label('මුල් පිටුව නරඹන්න (View Homepage)')
                ->icon(Heroicon::OutlinedEye)
                ->color('gray')
                ->url('/')
                ->openUrlInNewTab(),
            Action::make('create_destination')
                ->label('නව ගමනාන්තයක් එකතු කරන්න (Add Destination)')
                ->icon(Heroicon::OutlinedPlus)
                ->color('primary')
                ->url(DestinationResource::getUrl('create')),
        ];
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
