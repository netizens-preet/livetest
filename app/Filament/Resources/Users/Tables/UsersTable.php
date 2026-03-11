<?php

namespace App\Filament\Resources\Users\Tables;

use App\Filament\Exports\UserExporter;
use App\Models\User;
use App\Status;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ExportBulkAction;
use Filament\Actions\Exports\Enums\ExportFormat;
use Filament\Actions\ViewAction as ActionsViewAction;
use Filament\Forms\Components\Textarea;
use Filament\Support\Enums\FontWeight;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\Summarizers\Average;
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Grouping\Group;
use Filament\Tables\Table;

class UsersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->reorderable('order')
            ->defaultSort('order', 'asc')
            ->columns([
                ImageColumn::make('profile_photo')
                    ->circular()
                    ->defaultImageUrl('https://ui-avatars.com/api/?name=User')
                    ->disk('public'),
                TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->weight(FontWeight::SemiBold)
                    ->copyable(),

                TextColumn::make('email')
                    ->searchable()
                    ->sortable()
                    ->icon('heroicon-o-envelope')
                    ->copyable(),
                TextColumn::make('role')->badge(),

                // Using the Enum's built-in methods for the badge
                TextColumn::make('status')
                    ->badge(),

                // TextColumn::make('posts_count')->sortable()->counts('posts')
                //     ->summarize([
                //         Sum::make()->label('Total Posts'),
                //         Average::make()->label('Avg Post'),
                //     ]),

                TextColumn::make('posts_count')->sortable()->counts('posts'),
                TextColumn::make('published_posts_count')->sortable()->counts('publishedPosts'),
                // ->summarize([
                //     Sum::make()->label('Total Posts'),
                //     Average::make()->label('Avg Post'),
                // ]),

                IconColumn::make('email_verified_at')
                    ->label('Verified')
                    ->getStateUsing(fn ($record): bool => filled($record->email_verified_at))
                    ->boolean()
                    ->trueIcon('heroicon-o-check-badge')
                    ->falseIcon('heroicon-o-x-circle')
                    ->alignCenter(),

                TextColumn::make('created_at')
                    ->label('Registered')
                    ->dateTime('d M Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('created_at', 'desc')
            ->striped()
            ->poll('60s')
            ->emptyStateHeading('No users found')
            ->emptyStateDescription('Please add Users')
            ->emptyStateIcon('heroicon-o-users')
            ->filtersFormColumns(2)

            ->groups([
                Group::make('role')
                    ->label('User Role')
                    ->collapsible(),

                Group::make('status')
                    ->label('User Status')
                    ->collapsible(),
            ])->defaultGroup('role')

            // 1. Row Actions (Individual user management)
            ->recordActions([
                EditAction::make()->slideOver(),
                ActionsViewAction::make()->modalWidth('2xl'),

                // Verify Email Action
                Action::make('verify_email')
                    ->label('Verify')
                    ->icon('heroicon-o-check-badge')
                    ->color('success')
                    ->visible(fn (User $record) => $record->email_verified_at === null)
                    ->requiresConfirmation()
                    ->action(fn (User $record) => $record->update(['email_verified_at' => now()]))
                    ->successNotificationTitle('Email Verified'),

                // Suspend Action
                Action::make('suspend_account')
                    ->label('Suspend')
                    ->icon('heroicon-m-exclamation-triangle')
                    ->color('danger')
                    ->visible(fn (User $record) => $record->status === Status::Active)
                    ->requiresConfirmation()
                    ->action(fn (User $record) => $record->update(['status' => Status::Suspended]))
                    ->successNotificationTitle('Account Suspended'),

                // Ban Action with Reason Form
                Action::make('ban_account')
                    ->label('Ban')
                    ->icon('heroicon-m-no-symbol')
                    ->color('danger')
                    ->visible(fn (User $record) => $record->status !== Status::Banned)
                    ->form([
                        Textarea::make('ban_reason')
                            ->label('Reason for Ban')
                            ->required()
                            ->minLength(10),
                    ])
                    ->action(function (User $record, array $data): void {
                        $record->update([
                            'status' => Status::Banned,
                            'ban_reason' => $data['ban_reason'],
                        ]);
                    })
                    ->successNotificationTitle('User Banned'),

                DeleteAction::make()
                    ->disabled(fn ($record): bool => $record->id === auth()->id()),
            ])

            // 2. Bulk Actions (Operations on multiple selected rows)
            ->toolbarActions([
                BulkActionGroup::make([
                    ExportBulkAction::make()
                        ->label('Export CSV')
                        ->exporter(UserExporter::class)
                        ->formats([ExportFormat::Csv]),
                    DeleteBulkAction::make(),
                ]),
            ])

            ->filters([
                SelectFilter::make('role')
                    ->options([
                        'admin' => 'Admin',
                        'customer' => 'Customer',
                    ]),
                TernaryFilter::make('email_verified_at')
                    ->label('Verification Status'),
                TrashedFilter::make(),
            ]);
    }
}
