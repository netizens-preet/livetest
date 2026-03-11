<?php

namespace App\Filament\Resources\Users\Schemas;

use App\Models\User;
use App\Status;
use Filament\Infolists\Components\ColorEntry;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\KeyValueEntry;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class UserInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Profile')
                    ->schema([
                        Group::make()
                            ->columns(3)
                            ->schema([
                                Group::make()
                                    ->columnSpan(1)
                                    ->schema([
                                        ImageEntry::make('profile_photo')
                                            ->circular()
                                            ->imageSize(80)
                                            ->defaultImageUrl('ui-avatars.com'),

                                    ]),

                                Group::make()
                                    ->columnSpan(2)
                                    ->schema([
                                        Grid::make(1) // Single column grid to stack text vertically
                                            ->schema([
                                                TextEntry::make('name')
                                                    ->weight('bold'),
                                                TextEntry::make('email')
                                                    ->copyable(),
                                                TextEntry::make('phone')
                                                    ->placeholder('No phone added'),
                                            ]),
                                    ]),
                            ]),

                    ]),

                Section::make('Account')
                    ->schema([
                        TextEntry::make('role')->badge(),
                        TextEntry::make('status')->badge(),
                        IconEntry::make('email_verified_at')
                            ->label('Email Verified')
                            ->getStateUsing(fn ($record): bool => filled($record->email_verified_at))
                            ->boolean(),
                        TextEntry::make('email_verified_at')
                            ->label('Verification Date')
                            ->dateTime()
                            ->placeholder('Not verified'),
                        TextEntry::make('ban_reason')
                            ->label('Ban Reason')
                            ->columnSpanFull()
                            ->color('danger')
                            ->visible(fn (User $record): bool => $record->status === Status::Banned),
                    ])->columns(2),

                Section::make('Activity')
                    ->schema([
                        TextEntry::make('orders_count')
                            ->label('Total Orders')
                            ->default(0),
                        TextEntry::make('total_spent')
                            ->label('Total Spent')
                            ->money('USD'),
                        TextEntry::make('created_at')
                            ->label('Joined Date')
                            ->dateTime('d M Y'),
                    ])->columns(3),

                Section::make('Preferences')
                    ->collapsed()
                    ->schema([
                        ColorEntry::make('label_color'),
                        KeyValueEntry::make('preferences'),
                    ]),

                Section::make('Saved Addresses')
                    ->collapsible()
                    ->schema([
                        RepeatableEntry::make('address')
                            ->label('Residency Addresses')
                            ->schema([
                                TextEntry::make('Home Address'),
                                TextEntry::make('Work Address'),
                                TextEntry::make('City'),
                                TextEntry::make('residency_type')->label('Residency'),
                            ])->columns(2),
                    ]),

            ]);
    }
}
