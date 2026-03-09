<?php

namespace App\Enums; // Recommended namespace for Enums

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

enum PostStatus: string implements HasColor, HasIcon, HasLabel
{
    case Draft = 'draft';
    case Published = 'published';
    case Archived = 'archived';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::Draft => 'Draft',
            self::Published => 'Published',
            self::Archived => 'Archived',
        };
    }

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::Draft => 'gray',      // Neutral for unfinished work
            self::Published => 'success', // Green for "live" content
            self::Archived => 'warning',  // Amber/Orange for "stowed away"
        };
    }

    public function getIcon(): ?string
    {
        return match ($this) {
            self::Draft => 'heroicon-m-pencil-square',
            self::Published => 'heroicon-m-check-badge',
            self::Archived => 'heroicon-m-archive-box',
        };
    }
}
