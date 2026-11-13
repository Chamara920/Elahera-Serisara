<?php

namespace App\Filament\Resources\BlogPosts\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class BlogPostForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Blog Article')
                    ->schema([
                        Select::make('user_id')
                            ->relationship('user', 'name')
                            ->required()
                            ->label('Author / Community Member'),
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
                            ]),
                        Select::make('language')
                            ->label('Article Language')
                            ->options([
                                'all' => 'Both Languages (English & Sinhala)',
                                'en' => 'English Only',
                                'si' => 'Sinhala Only',
                            ])
                            ->default('all')
                            ->required(),
                        TextInput::make('title')
                            ->label('Article Title')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('slug')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->columnSpanFull(),
                        RichEditor::make('content')
                            ->label('Article Content')
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
                            ->directory('blog')
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

                Section::make('Moderation & Status')
                    ->description('Community user blog posts must be approved by admin before appearing on public frontend.')
                    ->schema([
                        Toggle::make('is_approved')
                            ->label('Approved for Publication')
                            ->helperText('Enable this to publish the community article on the website')
                            ->default(false),
                    ]),
            ]);
    }
}
