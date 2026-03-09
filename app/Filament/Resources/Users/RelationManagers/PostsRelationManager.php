<?php

namespace App\Filament\Resources\Users\RelationManagers;

use App\Models\Post;
use App\PostStatus;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class PostsRelationManager extends RelationManager
{
    protected static string $relationship = 'posts';

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('status')
                    ->badge(),
                TextColumn::make('published_at')
                    ->label('published_at')
                    ->date('D M Y')
                    ->placeholder('Not Published'),
                TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->sortable(),
                // ...
            ])
            ->headerActions([
                CreateAction::make()
                    ->icon('heroicon-m-plus')
                    ->label('New Post'),
                Action::make('publishAll')
                    ->label('Publish All')
                    ->color('success')
                    ->requiresConfirmation()
                    ->action(function () {
                        $ownerRecord = $this->getOwnerRecord();

                        // Get owner posts
                        $posts = $ownerRecord->draftPosts()->get();

                        $posts->each(function (Post $post) {
                            $post->update([
                                'status' => PostStatus::Published,
                                'published_at' => now(),
                            ]);
                        });

                    }),
            ])
            ->recordActions([
                EditAction::make()
                    ->label('Read Post')
                    ->icon('heroicon-m-book-open')
                    ->color('info'),
                DeleteAction::make(),
                ViewAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->required()
                    ->maxLength(255),

                RichEditor::make('body')
                    ->required()
                    ->columnSpanFull()
                    ->toolbarButtons([
                        'bold',
                        'italic',
                        'bulletList',
                        'link',
                    ]),
                Select::make('status')
                    ->options(
                        // 'Draft'=> 'draft',
                        // 'Published' => 'published',
                        // 'Archived'=> 'archived',
                        PostStatus::class)->default(PostStatus::Draft)->live(),
                DateTimePicker::make('published_at')->nullable()
                ->visible(fn (Get $get) => $get('status') == PostStatus::Published)->live(),
            ]);
    }

    public function Infolist(Schema $infolist): Schema
    {
        return $infolist
            ->schema([
                TextEntry::make('title'),
                TextEntry::make('status')->badge(),
                TextEntry::make('published_at')
                ->date('D M Y'),
                TextEntry::make('body')
                ->label('Content')
                ->html(),


            ]);
    }
}
