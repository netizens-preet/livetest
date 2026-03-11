<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\CodeEditor;
use Filament\Forms\Components\CodeEditor\Enums\Language;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Slider;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Fieldset;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Schema;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Account Information')
                ->icon('heroicon-o-user')
                ->description('Manage the user\'s core account details and credentials.')
                ->schema([
                    TextInput::make('name')
                        ->required(),

                    TextInput::make('email')
                        ->email()
                        ->required()
                        ->unique(ignoreRecord: true)
                        ->maxLength(255),

                    TextInput::make('phone')
                        ->placeholder('Enter phone number')
                        ->nullable(),
                ])
                ->columns(2)
                ->collapsible(),

            Section::make('Account Status & Role')
                ->description('Manage user access levels and verification status.')
                ->icon('heroicon-o-shield-check')
                ->schema([
                    Toggle::make('is_email_verified')
                        ->label('Mark Email as Verified')
                        ->inlineLabel()
                        ->dehydrated(false)
                        ->afterStateHydrated(function ($component, $record) {
                            $component->state($record?->email_verified_at !== null);
                        })
                        ->afterStateUpdated(function ($state, $record) {
                            $record->email_verified_at = $state ? now() : null;
                            $record->save();
                        }),

                    Select::make('role')
                        ->options([
                            'admin' => 'Admin',
                            'customer' => 'Customer',
                        ])
                        ->default('customer')
                        ->required()
                        ->searchable()
                        ->native(false),

                    Radio::make('status')
                        ->label('Account Status')
                        ->options([
                            'active' => 'Active',
                            'suspended' => 'Suspended',
                            'banned' => 'Banned',
                        ])
                        ->descriptions([
                            'active' => 'User can log in normally',
                            'suspended' => 'Temporary restriction',
                            'banned' => 'Permanent restriction',
                        ])
                        ->default('active')
                        ->inline()
                        ->required()
                        ->live(), // Ensures the Fieldset reacts immediately to changes

                    Fieldset::make('Ban Details')
                        ->schema([
                            Textarea::make('ban_reason')
                                ->label('Ban Reason')
                                ->placeholder('Enter the reason for account restriction...')
                                ->columnSpanFull(),
                        ])
                        ->visible(fn ($get) => in_array($get('status'), ['suspended', 'banned'])),
                ])
                ->columns(1)
                ->collapsible(),

            Checkbox::make('terms_accepted')
                ->label('I agree to the terms and conditions')
                ->required()
                ->accepted()
                ->dehydrated(false),

            Section::make('User profile')
                ->description('Additional profile information ')
                ->icon('heroicon-o-user-circle')
                ->schema([
                    FileUpload::make('profile_photo')
                        ->label('Profile Photo')
                        ->disk('public')
                        ->image()
                        ->avatar()
                        ->maxSize(1024)
                        ->directory('avatars')
                        ->imageEditor()
                        ->circleCropper(),
                    DateTimePicker::make('last_login')
                        ->label('Last Login Time')
                        ->displayFormat('d M Y h:i A')
                        ->native(false)
                        ->seconds(false),
                ])
                ->columns(2)
                ->collapsible(),

            Tabs::make('Address Management')
                ->tabs([
                    Tabs\Tab::make('User Addresses')
                        ->icon('heroicon-o-map-pin')
                        ->schema([
                            Repeater::make('address')
                                ->minItems(1)
                                ->maxItems(3)
                                ->schema([
                                    TextInput::make('Home Address')
                                        ->placeholder('Enter home address'),
                                    TextInput::make('Work Address')
                                        ->placeholder('Enter work address'),
                                    TextInput::make('City')
                                        ->placeholder('Enter city'),
                                    Select::make('Residency')
                                        ->options([
                                            'Residential' => 'Residential',
                                            'Commercial' => 'Commercial',
                                        ])
                                        ->placeholder('Select residency type'),
                                ])
                                ->label('Addresses')
                                ->collapsible()
                                ->cloneable(),
                        ]),
                ])
                ->vertical(),

            Section::make('Preferences & Settings')
                ->description('Manage user interests, custom configurations, and trust levels.')
                ->icon('heroicon-o-adjustments-horizontal')
                ->collapsible()
                ->schema([
                    TagsInput::make('interests')
                        ->label('Interests')
                        ->suggestions([
                            'Electronics', 'Clothing', 'Books', 'Food', 'Technology', 'Gaming', 'Travel',
                        ])
                        ->separator(',')
                        ->placeholder('Add an interest...'),

                    KeyValue::make('preferences')
                        ->label('User Preferences')
                        ->addActionLabel('Add New Preference')
                        ->reorderable()
                        ->distinct(true),

                    ColorPicker::make('label_color')
                        ->label('Label Color')
                        ->hsl(),

                    Slider::make('trust_score')
                        ->label('Trust Score')
                        ->minValue(0)
                        ->maxValue(100)
                        ->step(5),
                ])
                ->columns(2),

            Tabs::make('User Content')
                ->tabs([
                    Tabs\Tab::make('Bio')
                        ->icon('heroicon-o-book-open')
                        ->schema([
                            RichEditor::make('bio')
                                ->label('Bio')
                                ->maxLength(500)
                                ->columnSpanFull(),
                        ]),
                    Tabs\Tab::make('Notes')
                        ->icon('heroicon-o-pencil-square')
                        ->schema([
                            MarkdownEditor::make('notes')
                                ->label('Internal Notes')
                                ->columnSpanFull(),
                        ]),
                    Tabs\Tab::make('Custom CSS')
                        ->icon('heroicon-o-code-bracket')
                        ->schema([
                            CodeEditor::make('custom_css')
                                ->label('Custom CSS')
                                ->language(Language::Css)
                                ->columnSpanFull(),
                        ]),
                ])
                ->columnSpanFull(),
        ]);
    }
}
