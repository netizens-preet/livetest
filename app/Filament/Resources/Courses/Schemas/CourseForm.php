<?php

namespace App\Filament\Resources\Courses\Schemas;

use App\Level;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class CourseForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Group::make()
                    ->schema([
                        Section::make('Course Details')
                            ->schema([
                                TextInput::make('title')
                                    ->required()
                                    ->maxLength(255)
                                    ->columnSpanFull(),

                                Select::make('Category')
                                    ->options([
                                        'Programming' => 'Programming',
                                        'Design' => 'Design',
                                        'Business' => 'Business',
                                        'Marketing' => 'Marketing',
                                    ])
                                    ->searchable()
                                    ->columnSpan(1),

                                Select::make('level')
                                    ->options(Level::class) // Using your Enum
                                    ->default('beginner')
                                    ->required()
                                    ->columnSpan(1),

                                Textarea::make('description')
                                    ->nullable()
                                    ->rows(5)
                                    ->columnSpanFull(),
                            ])->columns(2),
                    ])
                    ->columnSpan(2),

                // Sidebar Area (Right Side)
                Group::make()
                    ->schema([
                        Section::make('Publishing')
                            ->schema([
                                TextInput::make('price')
                                    ->numeric()
                                    ->prefix('₹')
                                    ->default(0),

                                Toggle::make('is_published')
                                    ->label('Published')
                                    ->helperText('Control visibility on website')
                                    ->default(false),

                                FileUpload::make('thumbnail')
                                    ->image()
                                    ->disk('public')
                                    ->directory('course-thumbnails')
                                    ->imageEditor(), // Allows cropping
                            ]),
                    ])
                    ->columnSpan(1),
            ])
            ->columns(3);

    }
}
