<?php

namespace App\Filament\Resources\DestinationReviews;

use App\Filament\Resources\DestinationReviews\Pages\EditDestinationReview;
use App\Filament\Resources\DestinationReviews\Pages\ListDestinationReviews;
use App\Filament\Resources\DestinationReviews\Schemas\DestinationReviewForm;
use App\Filament\Resources\DestinationReviews\Tables\DestinationReviewsTable;
use App\Models\DestinationReview;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class DestinationReviewResource extends Resource
{
    protected static ?string $model = DestinationReview::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedStar;

    protected static string|UnitEnum|null $navigationGroup = 'Destinations & Transport';

    protected static ?string $navigationLabel = 'Reviews & Ratings';

    protected static ?string $modelLabel = 'Review & Rating';

    protected static ?string $pluralModelLabel = 'Reviews & Ratings';

    protected static ?int $navigationSort = 3;

    public static function form(Schema $schema): Schema
    {
        return DestinationReviewForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return DestinationReviewsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListDestinationReviews::route('/'),
            'edit' => EditDestinationReview::route('/{record}/edit'),
        ];
    }
}
