<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;


enum TimeZoneEnum: string implements HasLabel
{
    case UTC = 'UTC';
    case UTC1 = 'UTC+1';
    case UTC2 = 'UTC+2';
    case UTC3 = 'UTC+3';
    case UTC4 = 'UTC+4';
    case UTC5 = 'UTC+5';



    public function getLabel(): ?string
    {
        return match ($this) {
            self::UTC => 'UTC',
            self::UTC1 => 'UTC+1',
            self::UTC2 => 'UTC+2',
            self::UTC3 => 'UTC+3',
            self::UTC4 => 'UTC+4',
            self::UTC5 => 'UTC+5',
        };
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
