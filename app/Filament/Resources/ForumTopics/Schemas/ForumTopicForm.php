<?php

namespace App\Filament\Resources\ForumTopics\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ForumTopicForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Forum Discussion')
                    ->schema([
                        Select::make('user_id')
                            ->relationship('user', 'name')
                            ->required()
                            ->label('Posted By (User)'),
                        Select::make('forum_category_id')
                            ->relationship('category', 'name_en')
                            ->label('Forum Category')
                            ->required()
                            ->searchable()
                            ->preload()
                            ->createOptionForm([
                                TextInput::make('name_en')
                                    ->label('Category Name (English)')
                                    ->required()
                                    ->maxLength(255),
                                TextInput::make('name_si')
                                    ->label('Category Name (Sinhala)')
                                    ->maxLength(255),
                                TextInput::make('icon')
                                    ->label('Icon')
                                    ->default('chat-bubble-left-right'),
                            ]),
                        Select::make('language')
                            ->label('Language')
                            ->options([
                                'all' => 'Both Languages (English & Sinhala)',
                                'en' => 'English Only',
                                'si' => 'Sinhala Only',
                            ])
                            ->default('all')
                            ->required(),
                        TextInput::make('title')
                            ->label('Topic Title')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('slug')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->columnSpanFull(),
                        Textarea::make('content')
                            ->label('Topic Content')
                            ->rows(5)
                            ->required()
                            ->columnSpanFull(),
                    ])->columns(2),

                Section::make('Sinhala Content (Optional / විකල්ප)')
                    ->description('Provide Sinhala translation of title and content if desired.')
                    ->schema([
                        TextInput::make('title_si')
                            ->label('Sinhala Title (මාතෘකාව - සිංහලෙන්)')
                            ->maxLength(255)
                            ->columnSpanFull(),
                        Textarea::make('content_si')
                            ->label('Sinhala Content (විස්තරය - සිංහලෙන්)')
                            ->rows(5)
                            ->columnSpanFull(),
                    ])
                    ->collapsible(),
            ]);
    }
}
