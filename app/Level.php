<?php

namespace App;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

enum Level: string implements HasColor, HasIcon, HasLabel
{
    case Beginner = 'beginner';
    case Intermediate = 'intermediate';
    case Advanced = 'advanced';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::Beginner => 'Beginner',
            self::Intermediate => 'Intermediate',
            self::Advanced => 'Advanced',
        };
    }

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::Beginner => 'info',      // Blue
            self::Intermediate => 'warning', // Orange/Yellow
            self::Advanced => 'success',   // Green
        };
    }

    public function getIcon(): ?string
    {
        return match ($this) {
            self::Beginner => 'heroicon-m-sparkles',
            self::Intermediate => 'heroicon-m-bolt',
            self::Advanced => 'heroicon-m-trophy',
        };
    }
}
