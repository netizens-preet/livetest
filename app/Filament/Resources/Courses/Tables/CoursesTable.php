<?php

namespace App\Filament\Resources\Courses\Tables;

use App\Filament\Resources\Courses\Resources\Lessons\LessonResource;
use App\Level;
use App\Models\Course;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class CoursesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('Category')
                    ->badge()
                    ->color('gray'),

                TextColumn::make('level')
                    ->badge()
                    ->colors([
                        'success' => Level::Beginner,
                        'warning' => Level::Intermediate,
                        'danger' => Level::Advanced,
                    ]),

                IconColumn::make('is_published')
                    ->boolean()
                    ->label('Published'),

                TextColumn::make('lessons_count')
                    ->counts('lessons')
                    ->label('Lessons')
                    ->badge()
                    ->color('gray'),

                TextColumn::make('price')
                    ->money('INR'),
            ])
            ->filters([
                TrashedFilter::make(),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make()
                    ->slideOver(),
                // Action::make('viewLessons')
                //     ->url(fn ($record) => LessonResource::getUrl('index', ['parent' => $record->id]))
                //     ->icon('heroicon-o-academic-cap'),
                DeleteAction::make()
                    ->disabled(fn (Course $record): bool => $record->lessons_count > 0)
                    ->tooltip(fn (Course $record): string => $record->lessons_count > 0 ? 'Cannot delete course with lessons' : 'Delete course'),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ]);
    }
}
