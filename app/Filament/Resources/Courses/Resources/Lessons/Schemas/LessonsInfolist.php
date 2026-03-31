<?php

namespace App\Filament\Resources\Courses\Resources\Lessons\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class LessonsInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Group::make()
                    ->schema([
                        Section::make('Lesson Content')
                            ->schema([
                                TextEntry::make('title')
                                    ->weight('bold'),

                                TextEntry::make('slug')
                                    ->fontFamily('mono')
                                    ->color('gray'),

                                TextEntry::make('video_url')
                                    ->label('Video')
                                    ->url(fn ($record) => $record->video_url, shouldOpenInNewTab: true)
                                    ->color('primary')
                                    ->icon('heroicon-m-play-circle'),

                                TextEntry::make('content')
                                    ->html()
                                    ->columnSpanFull(),
                            ])->columns(2),
                    ])
                    ->columnSpan(2),

                Group::make()
                    ->schema([
                        Section::make('Metadata')
                            ->schema([
                                TextEntry::make('course.title')
                                    ->label('Course')
                                    ->weight('medium'),

                                TextEntry::make('duration_minutes')
                                    ->label('Duration')
                                    ->suffix(' minutes'),

                                IconEntry::make('is_free')
                                    ->label('Free Preview')
                                    ->boolean(),

                                IconEntry::make('is_published')
                                    ->label('Published')
                                    ->boolean(),
                            ]),
                    ])
                    ->columnSpan(1),
            ])
            ->columns(3);
    }
}
