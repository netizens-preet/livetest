<?php

namespace App\Filament\Resources\Courses\RelationManagers;

use App\Filament\Resources\Courses\Resources\Lessons\LessonResource;
use App\Filament\Resources\Courses\Resources\Lessons\Tables\LessonsTable;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;

class LessonsRelationManager extends RelationManager
{
    protected static string $relationship = 'lessons';

    protected static ?string $relatedResource = LessonResource::class;

    public function table(Table $table): Table
    {
        // You can reuse your existing table configuration here
        return LessonsTable::configure($table);
    }
}
