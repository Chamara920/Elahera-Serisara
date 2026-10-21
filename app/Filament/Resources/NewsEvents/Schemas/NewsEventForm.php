<?php

namespace App\Filament\Resources\NewsEvents\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
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
                        Select::make('type')
                            ->options([
                                'news' => 'News (පුවත්)',
                                'event' => 'Event (සිදුවීම්)',
                            ])
                            ->default('news')
                            ->required(),
                        TextInput::make('title')
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
                        Textarea::make('content')
                            ->required()
                            ->rows(8)
                            ->columnSpanFull(),
                        FileUpload::make('featured_image')
                            ->image()
                            ->directory('news')
                            ->columnSpanFull(),
                    ])->columns(2),

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
