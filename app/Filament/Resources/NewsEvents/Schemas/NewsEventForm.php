<?php

namespace App\Filament\Resources\NewsEvents\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class NewsEventForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('News / Event Details')
                    ->schema([
                        Select::make('user_id')
                            ->relationship('user', 'name')
                            ->required()
                            ->label('Posted By (User)'),
                        Select::make('category_id')
                            ->relationship('category', 'name_en')
                            ->label('Category')
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
                                    ->label('Icon / Emoji')
                                    ->placeholder('📰'),
                            ]),
                        Select::make('type')
                            ->options([
                                'news' => 'News (පුවත්)',
                                'event' => 'Event (සිදුවීම්)',
                            ])
                            ->default('news')
                            ->required(),
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
                            ->label('Title')
                            ->required()
                            ->maxLength(255)
                            ->columnSpanFull(),
                        TextInput::make('slug')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->columnSpanFull(),
                        DatePicker::make('event_date')
                            ->label('Event / Notice Date'),
                        TextInput::make('location')
                            ->label('Location / Venue')
                            ->placeholder('e.g. Elahera Mahaweli Ground, Bakamuna'),
                        RichEditor::make('content')
                            ->label('News / Event Content')
                            ->required()
                            ->toolbarButtons([
                                'blockquote',
                                'bold',
                                'bulletList',
                                'codeBlock',
                                'h2',
                                'h3',
                                'italic',
                                'link',
                                'orderedList',
                                'redo',
                                'strike',
                                'underline',
                                'undo',
                            ])
                            ->columnSpanFull(),
                        FileUpload::make('featured_image')
                            ->image()
                            ->directory('news')
                            ->columnSpanFull(),
                    ])->columns(2),

                Section::make('Sinhala Content (Optional / විකල්ප)')
                    ->description('Provide Sinhala translation of title and content if desired.')
                    ->schema([
                        TextInput::make('title_si')
                            ->label('Sinhala Title (මාතෘකාව - සිංහලෙන්)')
                            ->maxLength(255)
                            ->columnSpanFull(),
                        RichEditor::make('content_si')
                            ->label('Sinhala Content (සම්පූර්ණ විස්තරය - සිංහලෙන්)')
                            ->toolbarButtons([
                                'blockquote',
                                'bold',
                                'bulletList',
                                'codeBlock',
                                'h2',
                                'h3',
                                'italic',
                                'link',
                                'orderedList',
                                'redo',
                                'strike',
                                'underline',
                                'undo',
                            ])
                            ->columnSpanFull(),
                    ])
                    ->collapsible(),

                Section::make('Admin Approval')
                    ->description('Community-submitted news and events must be reviewed and approved by admin.')
                    ->schema([
                        Toggle::make('is_approved')
                            ->label('Approved & Published')
                            ->default(false),
                    ]),
            ]);
    }
}
