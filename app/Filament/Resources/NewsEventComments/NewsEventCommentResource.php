<?php

namespace App\Filament\Resources\NewsEventComments;

use App\Filament\Resources\NewsEventComments\Pages\EditNewsEventComment;
use App\Filament\Resources\NewsEventComments\Pages\ListNewsEventComments;
use App\Filament\Resources\NewsEventComments\Schemas\NewsEventCommentForm;
use App\Filament\Resources\NewsEventComments\Tables\NewsEventCommentsTable;
use App\Models\NewsEventComment;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class NewsEventCommentResource extends Resource
{
    protected static ?string $model = NewsEventComment::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedChatBubbleLeftEllipsis;

    protected static string|UnitEnum|null $navigationGroup = 'Community';

    protected static ?string $navigationLabel = 'News & Event Comments';

    protected static ?string $modelLabel = 'News / Event Comment';

    protected static ?string $pluralModelLabel = 'News & Event Comments';

    protected static ?int $navigationSort = 6;

    public static function form(Schema $schema): Schema
    {
        return NewsEventCommentForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return NewsEventCommentsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListNewsEventComments::route('/'),
            'edit' => EditNewsEventComment::route('/{record}/edit'),
        ];
    }
}
