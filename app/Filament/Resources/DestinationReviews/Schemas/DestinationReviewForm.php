<?php

namespace App\Filament\Resources\DestinationReviews\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class DestinationReviewForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Review & Rating Details')
                    ->schema([
                        Select::make('destination_id')
                            ->relationship('destination', 'title_en')
                            ->disabled()
                            ->label('Destination'),
                        Select::make('user_id')
                            ->relationship('user', 'name')
                            ->disabled()
                            ->label('User / Member'),
                        Select::make('rating')
                            ->options([
                                5 => '5 Stars (Excellent)',
                                4 => '4 Stars (Very Good)',
                                3 => '3 Stars (Average)',
                                2 => '2 Stars (Poor)',
                                1 => '1 Star (Terrible)',
                            ])
                            ->required()
                            ->label('Rating Score'),
                        Toggle::make('is_approved')
                            ->label('Approved & Published')
                            ->default(true),
                        Textarea::make('comment')
                            ->label('User Comment')
                            ->rows(4)
                            ->columnSpanFull()
                            ->required(),
                    ])->columns(2),
            ]);
    }
}
