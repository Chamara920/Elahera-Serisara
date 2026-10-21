<?php

namespace App\Filament\Resources\DestinationCategories;

use App\Filament\Resources\DestinationCategories\Pages\CreateDestinationCategory;
use App\Filament\Resources\DestinationCategories\Pages\EditDestinationCategory;
use App\Filament\Resources\DestinationCategories\Pages\ListDestinationCategories;
use App\Filament\Resources\DestinationCategories\Schemas\DestinationCategoryForm;
use App\Filament\Resources\DestinationCategories\Tables\DestinationCategoriesTable;
use App\Models\DestinationCategory;
use BackedEnum;
use UnitEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class DestinationCategoryResource extends Resource
{
    protected static ?string $model = DestinationCategory::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedTag;

    protected static string|UnitEnum|null $navigationGroup = 'මුල් පිටුව';

    protected static ?string $navigationLabel = 'සංචාරක ගමනාන්ත Category එකතු කිරීමට';

    protected static ?string $modelLabel = 'ගමනාන්ත කාණ්ඩය';

    protected static ?string $pluralModelLabel = 'සංචාරක ගමනාන්ත Categories';

    protected static ?int $navigationSort = 3;

    public static function form(Schema $schema): Schema
    {
        return DestinationCategoryForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return DestinationCategoriesTable::configure($table);
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
            'index' => ListDestinationCategories::route('/'),
            'create' => CreateDestinationCategory::route('/create'),
            'edit' => EditDestinationCategory::route('/{record}/edit'),
        ];
    }
}
