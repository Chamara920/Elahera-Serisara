<?php

namespace App\Filament\Resources\ForumTopics;

use App\Filament\Resources\ForumTopics\Pages\CreateForumTopic;
use App\Filament\Resources\ForumTopics\Pages\EditForumTopic;
use App\Filament\Resources\ForumTopics\Pages\ListForumTopics;
use App\Filament\Resources\ForumTopics\Schemas\ForumTopicForm;
use App\Filament\Resources\ForumTopics\Tables\ForumTopicsTable;
use App\Models\ForumTopic;
use BackedEnum;
use UnitEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ForumTopicResource extends Resource
{
    protected static ?string $model = ForumTopic::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedChatBubbleLeftRight;

    protected static string|UnitEnum|null $navigationGroup = 'ප්‍රජා අංශය (Community)';

    protected static ?string $navigationLabel = 'ප්‍රජා සංසදය (Forum Topics)';

    protected static ?string $modelLabel = 'සංසද මාතෘකාව';

    protected static ?string $pluralModelLabel = 'ප්‍රජා සංසදය';

    protected static ?int $navigationSort = 2;

    public static function form(Schema $schema): Schema
    {
        return ForumTopicForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ForumTopicsTable::configure($table);
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
            'index' => ListForumTopics::route('/'),
            'create' => CreateForumTopic::route('/create'),
            'edit' => EditForumTopic::route('/{record}/edit'),
        ];
    }
}
