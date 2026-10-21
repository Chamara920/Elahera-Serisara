<?php

namespace App\Filament\Resources\NewsEvents;

use App\Filament\Resources\NewsEvents\Pages\CreateNewsEvent;
use App\Filament\Resources\NewsEvents\Pages\EditNewsEvent;
use App\Filament\Resources\NewsEvents\Pages\ListNewsEvents;
use App\Filament\Resources\NewsEvents\Schemas\NewsEventForm;
use App\Filament\Resources\NewsEvents\Tables\NewsEventsTable;
use App\Models\NewsEvent;
use BackedEnum;
use UnitEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class NewsEventResource extends Resource
{
    protected static ?string $model = NewsEvent::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBellAlert;

    protected static string|UnitEnum|null $navigationGroup = 'Community';

    protected static ?string $navigationLabel = 'News & Events';

    protected static ?string $modelLabel = 'News / Event';

    protected static ?string $pluralModelLabel = 'News & Events';

    protected static ?int $navigationSort = 3;

    public static function form(Schema $schema): Schema
    {
        return NewsEventForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return NewsEventsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListNewsEvents::route('/'),
            'create' => CreateNewsEvent::route('/create'),
            'edit' => EditNewsEvent::route('/{record}/edit'),
        ];
    }
}
