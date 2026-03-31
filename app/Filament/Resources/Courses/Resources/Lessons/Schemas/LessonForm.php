<?php

namespace App\Filament\Resources\Courses\Resources\Lessons\Schemas;

use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class LessonForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Group::make()
                    ->schema([
                        Section::make('Lesson Details')
                            ->schema([
                                TextInput::make('title')
                                    ->required()
                                    ->maxLength(255)
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(fn (Set $set, ?string $state) => $set('slug', Str::slug($state))),

                                TextInput::make('slug')
                                    ->required()
                                    ->disabled(fn (?string $operation) => $operation === 'edit')
                                    ->dehydrated(),

                                TextInput::make('video_url')
                                    ->label('Video URL')
                                    ->url()
                                    ->nullable(),

                                TextInput::make('duration_minutes')
                                    ->label('Duration (minutes)')
                                    ->numeric()
                                    ->minValue(0),

                                TextInput::make('order')
                                    ->label('Order / Position')
                                    ->numeric()
                                    ->minValue(0)
                                    ->default(0),
                            ])->columns(2),

                        Section::make('Content')
                            ->schema([
                                RichEditor::make('content')
                                    ->nullable()
                                    ->columnSpanFull(),
                            ]),
                    ])
                    ->columnSpan(2),

                Group::make()
                    ->schema([
                        Section::make('Settings')
                            ->schema([
                                Toggle::make('is_free')
                                    ->label('Free Preview')
                                    ->helperText('Free lessons are visible without enrollment'),

                                Toggle::make('is_published')
                                    ->label('Published'),
                            ]),
                    ])
                    ->columnSpan(1),
            ])
            ->columns(3);

    }
}
