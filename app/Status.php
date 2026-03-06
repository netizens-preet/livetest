<?php

namespace App;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

enum Status: string implements HasColor, HasIcon, HasLabel
{
    case Active = "active";
    case Suspended = "suspended";
    case Banned = "banned";

    public function getLabel(): ?string
    {
        return match ($this) {
            self::Active => 'Active',
            self::Suspended => 'Suspended',
            self::Banned => 'Banned',
        };
    }

    public function getColor(): string | array | null
    {
        return match ($this) {
            self::Active => 'success',   // Green
            self::Suspended => 'warning', // Orange
            self::Banned => 'danger',     // Red
        };
    }

    public function getIcon(): ?string
    {
        return match ($this) {
            self::Active => 'heroicon-m-check-circle',
            self::Suspended => 'heroicon-m-exclamation-triangle',
            self::Banned => 'heroicon-m-x-circle',
        };
    }
}
