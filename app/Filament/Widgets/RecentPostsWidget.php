<?php

namespace App\Filament\Widgets;

use App\Models\Post;
use App\Models\User;
use Filament\Actions\BulkActionGroup;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;

class RecentPostsWidget extends TableWidget
{
    protected int | string | array $columnSpan = 'full';

protected function getTableQuery(): Builder
{

        return Post::query()
            ->latest()
            ->limit(10)
            ->with('user');
    }
    public function table(Table $table): Table
    {
        return $table
            ->query($this->getTableQuery())
            ->paginated(false)
            ->columns([
                TextColumn::make('user.name')
                    ->label('Author')
                    ->searchable(),

                TextColumn::make('title')
                    ->searchable(),

                // Leverages your existing Enum for colors and labels
                TextColumn::make('status')
                    ->badge(),

                TextColumn::make('published_at')
                    ->label('Published')
                    ->dateTime()
                    ->placeholder('Draft'),

                TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                //
            ])
            ->recordActions([
                //
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    //
                ]),
            ]);
    }
}
